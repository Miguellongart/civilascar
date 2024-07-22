<?php

namespace App\Listeners;

use App\Events\FixtureUpdated;
use App\Models\PlayerPositionTable;
use App\Models\PositionTable;

class UpdatePlayerPositionTable
{
    public function handle(FixtureUpdated $event)
    {
        $fixture = $event->fixture;
        
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
