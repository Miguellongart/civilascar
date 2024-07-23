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

        // Guardar el estado anterior del fixture
        $previousFixture = $fixture->replicate();

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
        $this->updatePositionTable($previousFixture, $fixture);

        return redirect()->route('admin.fixture.index', $fixture->tournament_id)->with('success', 'Fixture updated successfully');
    }

    private function updatePositionTable(Fixture $previousFixture, Fixture $fixture)
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

        // Si el fixture estaba completado previamente, restar las estadísticas previas
        if ($previousFixture->status == 'completed') {
            $this->adjustTeamStats($homeTeamPosition, $previousFixture->home_team_score, $previousFixture->away_team_score, false);
            $this->adjustTeamStats($awayTeamPosition, $previousFixture->away_team_score, $previousFixture->home_team_score, false);
        }

        // Si el fixture está completado ahora, sumar las nuevas estadísticas
        if ($fixture->status == 'completed') {
            $this->adjustTeamStats($homeTeamPosition, $fixture->home_team_score, $fixture->away_team_score, true);
            $this->adjustTeamStats($awayTeamPosition, $fixture->away_team_score, $fixture->home_team_score, true);
        }

        $homeTeamPosition->save();
        $awayTeamPosition->save();
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
}
