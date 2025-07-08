<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\Fixture;
use App\Models\PlayerFixtureEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TournamentService
{
    /**
     * Retrieves all planned tournaments.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPlannedTournaments()
    {
        return Tournament::with(['teams', 'fixtures', 'positionTables'])->where('status', 'planned')->get();
    }

    /**
     * Determines the current tournament based on request or defaults to the first available.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Collection $allTournaments
     * @return \App\Models\Tournament|null
     */
    public function getCurrentTournament(Request $request, $allTournaments)
    {
        $tournamentId = $request->get('tournament_id');
        if ($tournamentId) {
            $currentTournament = $allTournaments->firstWhere('id', $tournamentId);
            if ($currentTournament) {
                return $currentTournament;
            }
        }
        return $allTournaments->first();
    }

    /**
     * Retrieves distinct match dates for a given tournament.
     *
     * @param int $tournamentId
     * @return \Illuminate\Support\Collection
     */
    public function getFixtureDates(int $tournamentId)
    {
        return Fixture::where('tournament_id', $tournamentId)
            ->selectRaw('DATE(match_date) as match_date')
            ->distinct()
            ->orderBy('match_date')
            ->get()
            ->pluck('match_date');
    }

    /**
     * Determina la fecha de filtro para los fixtures.
     * Si no se especifica una fecha o la fecha es anterior a hoy,
     * busca la próxima fecha de partido disponible para el torneo.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Support\Collection $dates // Colección de fechas de partidos disponibles
     * @return string|null La fecha de filtro en formato 'YYYY-MM-DD' o null si no hay fechas futuras.
     */
    public function getFilterDate(Request $request, $dates)
    {
        $requestedDate = $request->get('filter_date');
        $today = Carbon::now()->format('Y-m-d');

        // Si se solicitó una fecha y es una fecha válida en el futuro o hoy
        if ($requestedDate && Carbon::parse($requestedDate)->format('Y-m-d') >= $today && $dates->contains($requestedDate)) {
            return $requestedDate;
        }

        // Si la fecha solicitada no es válida o no está presente,
        // o si es una fecha pasada, buscar la próxima fecha futura.
        $nextAvailableDate = null;

        // Primero, buscar una fecha que sea hoy o en el futuro
        $nextAvailableDate = $dates->first(function ($date) use ($today) {
            return $date >= $today;
        });

        // Si no se encontró ninguna fecha hoy o en el futuro,
        // significa que todos los partidos son pasados. En ese caso,
        // tomar la última fecha de partido disponible (la más reciente en el pasado).
        if (is_null($nextAvailableDate) && $dates->isNotEmpty()) {
            $nextAvailableDate = $dates->last(); // La última fecha disponible será la más reciente.
        }

        return $nextAvailableDate;
    }

    /**
     * Retrieves fixtures for a specific tournament and date.
     *
     * @param int $tournamentId
     * @param string $filterDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFixturesForDate(int $tournamentId, string $filterDate)
    {
        return Fixture::with(['homeTeam', 'awayTeam'])
            ->where('tournament_id', $tournamentId)
            ->whereDate('match_date', $filterDate)
            ->orderBy(DB::raw('TIME(match_date)'))
            ->orderByRaw("FIELD(status, 'completed', 'scheduled', 'canceled')")
            ->get();
    }

    /**
     * Sorts the position table of a tournament.
     *
     * @param \App\Models\Tournament $tournament
     * @return \App\Models\Tournament
     */
    public function sortTournamentPositionTable(Tournament $tournament)
    {
        $tournament->positionTables = $tournament->positionTables->sortByDesc(function ($position) {
            return [$position->points, $position->goal_difference, $position->goals_for];
        });
        return $tournament;
    }

    /**
     * Retrieves top players based on an event type for a given tournament.
     *
     * @param string $eventType
     * @param int $tournamentId
     * @param \Illuminate\Support\Collection $teamIds
     * @param string $sumColumn
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getTopPlayersByEvent(string $eventType, int $tournamentId, $teamIds, string $sumColumn, int $limit = 10)
    {
        return PlayerFixtureEvent::where('event_type', $eventType)
            ->whereHas('fixture', function ($q) use ($tournamentId) {
                $q->where('tournament_id', $tournamentId);
            })
            ->whereHas('player', function ($q) use ($teamIds) {
                $q->whereIn('team_id', $teamIds);
            })
            ->select('player_id', DB::raw("SUM(quantity) as {$sumColumn}"))
            ->groupBy('player_id')
            ->having($sumColumn, '>', 0)
            ->orderBy($sumColumn, 'desc')
            ->with('player.user', 'player.team')
            ->take($limit)
            ->get();
    }

    /**
     * Retrieves the top goal scorers for a tournament.
     *
     * @param int $tournamentId
     * @param \Illuminate\Support\Collection $teamIds
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopScorers(int $tournamentId, $teamIds)
    {
        return $this->getTopPlayersByEvent('goal', $tournamentId, $teamIds, 'goals');
    }

    /**
     * Retrieves the players with the most yellow cards for a tournament.
     *
     * @param int $tournamentId
     * @param \Illuminate\Support\Collection $teamIds
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getYellowCards(int $tournamentId, $teamIds)
    {
        return $this->getTopPlayersByEvent('yellow_card', $tournamentId, $teamIds, 'yellow_cards');
    }

    /**
     * Retrieves the players with the most red cards for a tournament.
     *
     * @param int $tournamentId
     * @param \Illuminate\Support\Collection $teamIds
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRedCards(int $tournamentId, $teamIds)
    {
        return $this->getTopPlayersByEvent('red_card', $tournamentId, $teamIds, 'red_cards');
    }
}