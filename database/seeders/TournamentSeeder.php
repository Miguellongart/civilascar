<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Tournament;
use App\Models\Team;
use App\Models\Fixture;
use App\Models\PositionTable;
use Carbon\Carbon;

class TournamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear un torneo
        $tournament = Tournament::create([
            'name' => 'Liga Cafetera 2024-2',
            'slug' => Str::slug('Liga Cafetera 2024-2'),
            'type' => 'league',
            'max_teams' => 18,
            'min_teams' => 14,
            'description' => 'Torneo de fútbol local',
            'start_date' => Carbon::now()->addDays(7),
            'end_date' => Carbon::now()->addMonths(3),
            'location' => 'Estadio Nacional',
            'status' => 'planned',
            'rules' => 'Reglas del torneo...',
            'prizes' => 'Trofeo y medallas',
            'logo' => 'logos/liga_cafetera.png',
            'limit_teams' => 18,
        ]);

        // Nombres de los equipos
        $teamNames = [
            'VENCEDORES DE AYACUCHO',
            'CALM INFINITY',
            'FLASH COIN',
            'PAGO LINEA',
            'LOS DEL BARRIO',
            'THE NEW',
            'ASOC. SENTIMIENTO PERUANO',
            'TAGUIBEDS',
            'LOCOS X EL MAIZ',
            'CRIPTO TIME',
            'PARCELONA FC',
            'MEDICINA PREDADORA',
            'UNITED FC',
            'AFRICA UNITED',
            'GARAGE ONLINE',
            'RED9',
        ];

        // Crear equipos y asignarlos al torneo
        $teams = [];
        foreach ($teamNames as $index => $name) {
            $team = Team::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'coach' => 'Entrenador ' . ($index + 1),
                'logo' => 'logos/equipo_' . ($index + 1) . '.png',
                'description' => 'Descripción del ' . $name,
                'home_stadium' => 'Estadio ' . ($index + 1),
                'user_id' => null,
            ]);

            // Asociar el equipo al torneo
            $tournament->teams()->attach($team->id);
            $teams[] = $team;
        }

        // Llenar la tabla de posiciones con ceros
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
        $this->generateFixtures($tournament, $teams);
    }

    /**
     * Generate fixtures for the tournament.
     *
     * @param Tournament $tournament
     * @param array $teams
     * @return void
     */
    private function generateFixtures(Tournament $tournament, array $teams)
    {
        $numTeams = count($teams);
        $startDate = Carbon::parse($tournament->start_date);

        for ($i = 0; $i < $numTeams - 1; $i++) {
            for ($j = $i + 1; $j < $numTeams; $j++) {
                Fixture::create([
                    'tournament_id' => $tournament->id,
                    'home_team_id' => $teams[$i]->id,
                    'away_team_id' => $teams[$j]->id,
                    'match_date' => $startDate->addDays(rand(1, 7)), // Fecha aleatoria entre 1 y 7 días a partir de la fecha de inicio
                    'status' => 'scheduled',
                    'home_team_score' => null,
                    'away_team_score' => null,
                    'sport' => 'football',
                    'periods' => 'halves',
                    'period_times' => json_encode(['first_half' => 45, 'second_half' => 45]),
                ]);
                $startDate = Carbon::parse($tournament->start_date); // Reset start date for next fixture
            }
        }
    }
}
