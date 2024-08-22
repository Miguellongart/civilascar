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
        
        $fixture = Fixture::with(['playerEvents'])->findOrFail($id);
    
        // Obtener los jugadores del equipo local y visitante
        $homeTeamPlayers = Player::where('team_id', $fixture->home_team_id)->get();
        $awayTeamPlayers = Player::where('team_id', $fixture->away_team_id)->get();
    
        // Organizar los eventos por jugador y tipo
        $playerEvents = $fixture->playerEvents->groupBy('player_id')->map(function ($events) {
            return $events->keyBy('event_type');
        });
    
        return view('admin.fixture.edit', compact('fixture', 'homeTeamPlayers', 'awayTeamPlayers', 'playerEvents', 'subtitle', 'content_header_title', 'content_header_subtitle'));
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
                    $eventData['minute'] = 0; // O el valor que consideres apropiado
                }

                // Identificar el tipo de evento y su cantidad asociada
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

                // Si se identificó un evento válido y cantidad mayor que 0, proceder con la actualización/creación
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
                    // Si el evento existe y tiene cantidad 0, eliminarlo
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
        $homeTeamPosition = PositionTable::where('team_id', $fixture->home_team_id)->where('tournament_id', $fixture->tournament_id)->first();
        $awayTeamPosition = PositionTable::where('team_id', $fixture->away_team_id)->where('tournament_id', $fixture->tournament_id)->first();
    
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
    
        // Si el estado del fixture ha cambiado, actualizar las estadísticas
        if ($previousFixture->status == 'completed' && $fixture->status != 'completed') {
            // Restar estadísticas previas
            $this->adjustTeamStats($homeTeamPosition, $previousFixture->home_team_score, $previousFixture->away_team_score, false);
            $this->adjustTeamStats($awayTeamPosition, $previousFixture->away_team_score, $previousFixture->home_team_score, false);
        }
    
        if ($fixture->status == 'completed' && ($previousFixture->home_team_score != $fixture->home_team_score || $previousFixture->away_team_score != $fixture->away_team_score || $previousFixture->won_by_forfeit != $fixture->won_by_forfeit)) {
            // Si el fixture se completó y los puntajes o el estado de ganar por mesa ha cambiado
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
        // Determinar el equipo que debería ganar por mesa (el equipo que no incumplió las reglas)
        $winningTeam = $fixture->home_team_score > $fixture->away_team_score ? $awayTeamPosition : $homeTeamPosition;
        $losingTeam = $fixture->home_team_score > $fixture->away_team_score ? $homeTeamPosition : $awayTeamPosition;
    
        // El equipo ganador por mesa obtiene 3 puntos y se cuenta como una victoria
        $winningTeam->won += 1;
        $winningTeam->points += 3;
    
        // El equipo que incumplió pierde, aunque los goles se mantienen
        $losingTeam->lost += 1;
    
        // Asignar el partido como jugado para ambos equipos
        $winningTeam->played += 1;
        $losingTeam->played += 1;
    
        // Guardar las posiciones
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
