<?php

namespace App\Http\Controllers\Admin;

use App\Events\FixtureUpdated;
use App\Http\Controllers\Controller;
use App\Models\Fixture;
use App\Models\Player;
use App\Models\PlayerFixtureEvent;
use App\Models\PositionTable;
use App\Models\Tournament;
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
        $fixture = Fixture::with('playerEvents')->findOrFail($id);
        $players = Player::all(); // Obtener todos los jugadores, puedes filtrar por equipos si es necesario
        return view('admin.fixture.edit', compact('fixture', 'players', 'subtitle', 'content_header_title', 'content_header_subtitle'));
    }

    public function update(Request $request, $id)
    {
        $fixture = Fixture::findOrFail($id);
        $fixture->update($request->only(['match_date', 'status', 'home_team_score', 'away_team_score']));

        // Actualizar eventos existentes y agregar nuevos eventos
        if ($request->has('player_events')) {
            foreach ($request->player_events as $eventId => $eventData) {
                PlayerFixtureEvent::updateOrCreate(
                    ['id' => $eventId, 'fixture_id' => $fixture->id],
                    $eventData
                );
            }
        }

        // Actualizar la tabla de posiciones
        $this->updatePositionTable($fixture);

        return redirect()->route('admin.fixture.index', $fixture->tournament_id)->with('success', 'Fixture updated successfully');
    }

    private function updatePositionTable(Fixture $fixture)
    {
        // Obtener los equipos y sus posiciones
        $homeTeamPosition = PositionTable::where('team_id', $fixture->home_team_id)->where('tournament_id', $fixture->tournament_id)->first();
        $awayTeamPosition = PositionTable::where('team_id', $fixture->away_team_id)->where('tournament_id', $fixture->tournament_id)->first();

        // Inicializar las posiciones si no existen
        if (!$homeTeamPosition) {
            $homeTeamPosition = new PositionTable([
                'tournament_id' => $fixture->tournament_id,
                'team_id' => $fixture->home_team_id,
            ]);
        }

        if (!$awayTeamPosition) {
            $awayTeamPosition = new PositionTable([
                'tournament_id' => $fixture->tournament_id,
                'team_id' => $fixture->away_team_id,
            ]);
        }

        // Actualizar estadísticas basadas en el resultado del partido
        if ($fixture->status == 'completed') {
            $homeTeamScore = $fixture->home_team_score;
            $awayTeamScore = $fixture->away_team_score;

            if ($homeTeamScore > $awayTeamScore) {
                $homeTeamPosition->won += 1;
                $awayTeamPosition->lost += 1;
            } elseif ($homeTeamScore < $awayTeamScore) {
                $awayTeamPosition->won += 1;
                $homeTeamPosition->lost += 1;
            } else {
                $homeTeamPosition->drawn += 1;
                $awayTeamPosition->drawn += 1;
            }

            $homeTeamPosition->played += 1;
            $awayTeamPosition->played += 1;
            $homeTeamPosition->goals_for += $homeTeamScore;
            $homeTeamPosition->goals_against += $awayTeamScore;
            $homeTeamPosition->goal_difference = $homeTeamPosition->goals_for - $homeTeamPosition->goals_against;
            $homeTeamPosition->points = $homeTeamPosition->won * 3 + $homeTeamPosition->drawn;

            $awayTeamPosition->goals_for += $awayTeamScore;
            $awayTeamPosition->goals_against += $homeTeamScore;
            $awayTeamPosition->goal_difference = $awayTeamPosition->goals_for - $awayTeamPosition->goals_against;
            $awayTeamPosition->points = $awayTeamPosition->won * 3 + $awayTeamPosition->drawn;

            $homeTeamPosition->save();
            $awayTeamPosition->save();
        }
    }
}
