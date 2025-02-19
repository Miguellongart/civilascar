<?php

namespace App\Http\Controllers\Admin;

use App\Events\FixtureUpdated;
use App\Http\Controllers\Controller;
use App\Models\Fixture;
use App\Models\Player;
use App\Models\PlayerFixtureEvent;
use App\Models\PositionTable;
use App\Models\Tournament;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FixtureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);
        $subtitle = 'Fixture';
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Listado de Fixture';

        $dates = Fixture::where('tournament_id', $tournamentId)
            ->selectRaw('DATE(match_date) as match_date')
            ->distinct()
            ->orderBy('match_date')
            ->get()
            ->pluck('match_date');

        // Determinar la siguiente fecha disponible
        $nextDate = $dates->firstWhere(function ($date) {
            return $date >= now()->format('Y-m-d');
        });

        $selectedDate = $request->get('date', $nextDate);

        $query = Fixture::with(['homeTeam', 'awayTeam'])->where('tournament_id', $tournamentId);

        if ($selectedDate) {
            $query->whereDate('match_date', $selectedDate);
        }

        $fixtures = $query->get();

        return view('admin.fixture.index', compact('subtitle', 'content_header_title', 'content_header_subtitle', 'fixtures', 'tournament', 'dates', 'selectedDate'));
    }

    public function edit($id)
    {
        $subtitle = 'Fixture';
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Editar Fixture';
    
        // Obtener el fixture y sus eventos
        $fixture = Fixture::with(['playerEvents'])->findOrFail($id);
    
        // Se asume que el fixture tiene el campo tournament_id
        $tournamentId = $fixture->tournament_id;
    
        // Obtener los jugadores del equipo local filtrados por torneo
        $homeTeamPlayers = Player::select('players.*')
            ->join('player_team_tournament', 'players.id', '=', 'player_team_tournament.player_id')
            ->where('player_team_tournament.team_id', $fixture->home_team_id)
            ->where('player_team_tournament.tournament_id', $tournamentId)
            ->get();
    
        // Obtener los jugadores del equipo visitante filtrados por torneo
        $awayTeamPlayers = Player::select('players.*')
            ->join('player_team_tournament', 'players.id', '=', 'player_team_tournament.player_id')
            ->where('player_team_tournament.team_id', $fixture->away_team_id)
            ->where('player_team_tournament.tournament_id', $tournamentId)
            ->get();
    
        // Organizar los eventos por jugador y tipo
        $playerEvents = $fixture->playerEvents->groupBy('player_id')->map(function ($events) {
            return $events->keyBy('event_type');
        });
    
        return view('admin.fixture.edit', compact(
            'fixture',
            'homeTeamPlayers',
            'awayTeamPlayers',
            'playerEvents',
            'subtitle',
            'content_header_title',
            'content_header_subtitle'
        ));
    }

    public function update(Request $request, $id)
    {
        $fixture = Fixture::findOrFail($id);

        // Guardar el estado anterior del fixture
        $previousFixture = $fixture->replicate();

        // Actualizar el fixture incluyendo el campo 'won_by_forfeit'
        $fixture->update($request->only(['match_date', 'status', 'home_team_score', 'away_team_score', 'won_by_forfeit']));

        // Actualizar eventos existentes y agregar nuevos eventos
        if ($request->has('player_events')) {
            foreach ($request->player_events as $eventId => $eventData) {
                // Asegurar que 'minute' siempre tenga un valor
                if (!isset($eventData['minute']) || $eventData['minute'] === null) {
                    $eventData['minute'] = 0;
                }

                $eventType = null;
                $quantity = null;

                if (!empty($eventData['goals']) && $eventData['goals'] > 0) {
                    $eventType = 'goal';
                    $quantity = $eventData['goals'];
                } elseif (!empty($eventData['yellow_cards']) && $eventData['yellow_cards'] > 0) {
                    $eventType = 'yellow_card';
                    $quantity = $eventData['yellow_cards'];
                } elseif (!empty($eventData['red_cards']) && $eventData['red_cards'] > 0) {
                    $eventType = 'red_card';
                    $quantity = $eventData['red_cards'];
                }

                if ($eventType && $quantity !== null && $quantity > 0) {
                    PlayerFixtureEvent::updateOrCreate(
                        [
                            'fixture_id' => $fixture->id,
                            'player_id' => $eventData['player_id'],
                            'event_type' => $eventType,
                        ],
                        [
                            'minute' => $eventData['minute'],
                            'quantity' => $quantity,
                            'comment' => $eventData['comment'] ?? '',
                        ]
                    );
                } else {
                    PlayerFixtureEvent::where([
                        ['fixture_id', '=', $fixture->id],
                        ['player_id', '=', $eventData['player_id']],
                        ['event_type', '=', $eventType],
                    ])->delete();
                }
            }
        }

        // Actualizar la tabla de posiciones
        $this->updatePositionTable($previousFixture, $fixture);

        return redirect()->route('admin.fixture.index', $fixture->tournament_id)->with('success', 'Fixture updated successfully');
    }

    private function updatePositionTable(Fixture $previousFixture, Fixture $fixture)
    {
        $homeTeamPosition = PositionTable::firstOrNew(['team_id' => $fixture->home_team_id, 'tournament_id' => $fixture->tournament_id]);
        $awayTeamPosition = PositionTable::firstOrNew(['team_id' => $fixture->away_team_id, 'tournament_id' => $fixture->tournament_id]);

        // Si el resultado o la decisión de mesa no cambia, no actualizamos la tabla
        $resultChanged = ($previousFixture->home_team_score !== $fixture->home_team_score || $previousFixture->away_team_score !== $fixture->away_team_score);
        $forfeitChanged = ($previousFixture->won_by_forfeit !== $fixture->won_by_forfeit);

        if (!$resultChanged && !$forfeitChanged) {
            return; // No se actualiza la tabla si no hubo cambios en el resultado o decisión de mesa
        }

        // Revertir los efectos del resultado anterior si el fixture ya estaba completado
        if ($previousFixture->status == 'completed') {
            $this->adjustTeamStats($homeTeamPosition, $previousFixture->home_team_score, $previousFixture->away_team_score, false);
            $this->adjustTeamStats($awayTeamPosition, $previousFixture->away_team_score, $previousFixture->home_team_score, false);
        }

        // Aplicar el nuevo resultado si el fixture está marcado como completado
        if ($fixture->status == 'completed') {
            if ($fixture->won_by_forfeit) {
                $this->assignForfeitWin($fixture, $homeTeamPosition, $awayTeamPosition);
            } else {
                $this->adjustTeamStats($homeTeamPosition, $fixture->home_team_score, $fixture->away_team_score, true);
                $this->adjustTeamStats($awayTeamPosition, $fixture->away_team_score, $fixture->home_team_score, true);
            }
        }

        $homeTeamPosition->save();
        $awayTeamPosition->save();
    }

    private function assignForfeitWin(Fixture $fixture, PositionTable $homeTeamPosition, PositionTable $awayTeamPosition)
    {
        $winningTeam = $fixture->home_team_score > $fixture->away_team_score ? $homeTeamPosition : $awayTeamPosition;
        $losingTeam = $fixture->home_team_score > $fixture->away_team_score ? $awayTeamPosition : $homeTeamPosition;

        $winningTeam->won += 1;
        $winningTeam->points += 3;
        $winningTeam->played += 1;

        $losingTeam->lost += 1;
        $losingTeam->played += 1;

        $winningTeam->save();
        $losingTeam->save();
    }

    private function adjustTeamStats(PositionTable $teamPosition, $goalsFor, $goalsAgainst, $isAdding)
    {
        $factor = $isAdding ? 1 : -1;

        $teamPosition->played += $factor;
        $teamPosition->goals_for += $goalsFor * $factor;
        $teamPosition->goals_against += $goalsAgainst * $factor;
        $teamPosition->goal_difference = $teamPosition->goals_for - $teamPosition->goals_against;

        if ($goalsFor > $goalsAgainst) {
            $teamPosition->won += $factor;
            $teamPosition->points += 3 * $factor;
        } elseif ($goalsFor < $goalsAgainst) {
            $teamPosition->lost += $factor;
        } else {
            $teamPosition->drawn += $factor;
            $teamPosition->points += 1 * $factor;
        }
    }

    public function createFixture($tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);
        $teams = $tournament->teams()->get();

        // Generar la tabla de posiciones
        foreach ($teams as $team) {
            PositionTable::create([
                'tournament_id' => $tournament->id,
                'team_id' => $team->id,
                'played' => 0,
                'won' => 0,
                'drawn' => 0,
                'lost' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'goal_difference' => 0,
                'points' => 0,
            ]);
        }

        // Generar fixtures
        $this->generateFixtures($tournament, $teams->toArray());

        // Redirigir al listado de fixtures del torneo
        return redirect()->route('admin.fixture.index', $tournamentId)->with('success', 'Fixtures created successfully');
    }

    /**
     * Genera los fixtures (partidos) para un torneo con un número PAR de equipos.
     *
     * @param  Tournament  $tournament  El torneo para el que se generarán los fixtures
     * @param  array       $teams       Lista de equipos (objetos Eloquent o stdClass) con un número PAR
     * @return void
     *
     * @throws \Exception Si la cantidad de equipos es impar
     */
    private function generateFixtures(Tournament $tournament, array $teams)
    {
        $numTeams = count($teams);

        // Verificar que el número de equipos sea par
        if ($numTeams % 2 !== 0) {
            throw new \Exception('El número de equipos debe ser par para generar fixtures.');
        }

        // Fecha inicial para los partidos
        $startDate = Carbon::parse('2025-02-23');

        // Número de rondas = (número de equipos - 1)
        // Cada ronda tendrá (número de equipos / 2) partidos
        $round = 1;
        $fixtures = [];

        // Método del círculo para Round Robin
        for ($roundIndex = 0; $roundIndex < $numTeams - 1; $roundIndex++) {
            for ($matchIndex = 0; $matchIndex < $numTeams / 2; $matchIndex++) {
                $homeIndex = ($roundIndex + $matchIndex) % ($numTeams - 1);
                $awayIndex = ($numTeams - 1 - $matchIndex + $roundIndex) % ($numTeams - 1);

                // El último equipo (índice = $numTeams - 1) siempre juega en casa en la última ronda
                if ($matchIndex === 0) {
                    $awayIndex = $numTeams - 1;
                }
                // Crear el fixture (partido)
                $fixtures[] = [
                    'tournament_id'     => $tournament->id,
                    'round'             => $round,
                    'home_team_id'      => $teams[$homeIndex]['id'],
                    'away_team_id'      => $teams[$awayIndex]['id'],
                    'match_date'        => $startDate->copy()->addWeeks($round - 1),
                    'status'            => 'scheduled',
                    'home_team_score'   => null,
                    'away_team_score'   => null,
                    'sport'             => 'football',
                    'periods'           => 'halves',
                    'period_times'      => json_encode(['first_half' => 20, 'second_half' => 20]),
                ];
            }
            $round++;
        }

        // Insertar todos los fixtures en la base de datos
        foreach ($fixtures as $fixture) {
            Fixture::create($fixture);
        }
    }
}
