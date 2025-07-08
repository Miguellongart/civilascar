<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Fixture;
use App\Models\Player;
use App\Models\PlayerFixtureEvent;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Services\TournamentService;

class FrontController extends Controller
{    
    protected $tournamentService;

    public function __construct(TournamentService $tournamentService)
    {
        $this->tournamentService = $tournamentService;
    }

    

    public function Home(Request $request)
    {
        // Obtener todos los torneos para el filtro
        $allTournaments = $this->tournamentService->getAllPlannedTournaments();

        // Determinar el torneo actual basado en el filtro (si se envía tournament_id)
        $currentTournament = $this->tournamentService->getCurrentTournament($request, $allTournaments);

        // Si no hay torneo actual, redirigir o mostrar un mensaje de error
        if (!$currentTournament) {
            // Se mantiene la redirección si NO HAY NINGÚN TORNEO planificado en absoluto.
            return redirect()->back()->with('error', 'No se encontraron torneos planificados disponibles.');
        }

        // Obtener los IDs de los equipos que participan en el torneo actual
        $teamIds = $currentTournament->teams->pluck('id');

        // Obtener las fechas disponibles de los fixtures del torneo actual
        $dates = $this->tournamentService->getFixtureDates($currentTournament->id);

        // Determinar la siguiente fecha disponible o usar la del filtro
        // Este método ahora es más inteligente y devuelve la fecha más apropiada
        $filterDate = $this->tournamentService->getFilterDate($request, $dates);

        // Si no hay ninguna fecha de partido (el torneo no tiene fixtures), inicializar fixtures como vacío
        // y el filterDate permanecerá null para indicar a la vista que no hay fechas.
        if (is_null($filterDate)) {
            $fixtures = collect(); // Colección vacía
            // Las demás estadísticas también deberían ser colecciones vacías si no hay partidos
            $topScorers = collect();
            $yellowCards = collect();
            $redCards = collect();
            // La tabla de posiciones se puede intentar ordenar igual si hay datos, o también inicializar vacía
            $currentTournament = $this->tournamentService->sortTournamentPositionTable($currentTournament);
        } else {
            // Solo obtener fixtures si se encontró una fecha válida
            $fixtures = $this->tournamentService->getFixturesForDate($currentTournament->id, $filterDate);

            // Ordenar la tabla de posiciones del torneo actual
            $currentTournament = $this->tournamentService->sortTournamentPositionTable($currentTournament);

            // Filtrar eventos de jugadores considerando solo aquellos de equipos del torneo actual
            $topScorers = $this->tournamentService->getTopScorers($currentTournament->id, $teamIds);
            $yellowCards = $this->tournamentService->getYellowCards($currentTournament->id, $teamIds);
            $redCards = $this->tournamentService->getRedCards($currentTournament->id, $teamIds);
        }

        return view('front.home.index', compact(
            'allTournaments',
            'currentTournament',
            'fixtures',
            'filterDate',
            'dates',
            'topScorers',
            'yellowCards',
            'redCards'
        ));
    }

    public function tournament(Request $request)
    {
        // Obtener todos los torneos para el filtro
        $allTournaments = Tournament::with(['teams', 'fixtures', 'positionTables'])->where('status', 'planned')->get();

        // Determinar el torneo actual basado en el filtro (si se envía tournament_id)
        $tournamentId = $request->get('tournament_id');
        if ($tournamentId) {
            $currentTournament = $allTournaments->firstWhere('id', $tournamentId);
            if (!$currentTournament) {
                // Si no se encuentra, se asigna el primero como valor por defecto
                $currentTournament = $allTournaments->first();
            }
        } else {
            $currentTournament = $allTournaments->first();
        }

        // Obtener los IDs de los equipos que participan en el torneo actual
        $teamIds = $currentTournament->teams->pluck('id');

        // Obtener las fechas disponibles de los fixtures del torneo actual
        $dates = Fixture::where('tournament_id', $currentTournament->id)
            ->selectRaw('DATE(match_date) as match_date')
            ->distinct()
            ->orderBy('match_date')
            ->get()
            ->pluck('match_date');

        // Determinar la siguiente fecha disponible o usar la del filtro
        $nextDate = $dates->firstWhere(function ($date) {
            return $date >= now()->format('Y-m-d');
        });
        $filterDate = $request->get('filter_date', $nextDate);

        // Obtener los fixtures del torneo actual correspondientes a la fecha seleccionada
        $fixtures = Fixture::with(['homeTeam', 'awayTeam'])
            ->where('tournament_id', $currentTournament->id)
            ->whereDate('match_date', $filterDate)
            ->orderBy(DB::raw('TIME(match_date)'))
            ->orderByRaw("FIELD(status, 'completed', 'scheduled', 'canceled')")
            ->get();

        // Ordenar la tabla de posiciones del torneo actual
        $currentTournament->positionTables = $currentTournament->positionTables->sortByDesc(function ($position) {
            return [$position->points, $position->goal_difference, $position->goals_for];
        });

        // Filtrar eventos de jugadores considerando solo aquellos de equipos del torneo actual

        // 1. Goleadores: obtener los 10 jugadores con la suma de goles mayor a 0
        $topScorers = PlayerFixtureEvent::where('event_type', 'goal')
            ->whereHas('fixture', function ($q) use ($currentTournament) {
                $q->where('tournament_id', $currentTournament->id);
            })
            ->whereHas('player', function ($q) use ($teamIds) {
                $q->whereIn('team_id', $teamIds);
            })
            ->select('player_id', DB::raw('SUM(quantity) as goals'))
            ->groupBy('player_id')
            ->having('goals', '>', 0)
            ->orderBy('goals', 'desc')
            ->with('player.user', 'player.team')
            ->take(10)
            ->get();

        // 2. Tarjetas Amarillas: obtener los 10 jugadores con mayor suma de tarjetas amarillas
        $yellowCards = PlayerFixtureEvent::where('event_type', 'yellow_card')
            ->whereHas('fixture', function ($q) use ($currentTournament) {
                $q->where('tournament_id', $currentTournament->id);
            })
            ->whereHas('player', function ($q) use ($teamIds) {
                $q->whereIn('team_id', $teamIds);
            })
            ->select('player_id', DB::raw('SUM(quantity) as yellow_cards'))
            ->groupBy('player_id')
            ->orderBy('yellow_cards', 'desc')
            ->with('player.user', 'player.team')
            ->take(10)
            ->get();

        // 3. Tarjetas Rojas: obtener los 10 jugadores con mayor suma de tarjetas rojas
        $redCards = PlayerFixtureEvent::where('event_type', 'red_card')
            ->whereHas('fixture', function ($q) use ($currentTournament) {
                $q->where('tournament_id', $currentTournament->id);
            })
            ->whereHas('player', function ($q) use ($teamIds) {
                $q->whereIn('team_id', $teamIds);
            })
            ->select('player_id', DB::raw('SUM(quantity) as red_cards'))
            ->groupBy('player_id')
            ->orderBy('red_cards', 'desc')
            ->with('player.user', 'player.team')
            ->take(10)
            ->get();

        return view('front.tournament.index', compact(
            'allTournaments',
            'currentTournament',
            'fixtures',
            'filterDate',
            'dates',
            'topScorers',
            'yellowCards',
            'redCards'
        ));
    }

    public function inscription()
    {
        $tournaments = Tournament::where('status', 'planned')->get();
        return view('front.tournament.inscription', compact('tournaments'));
    }

    public function getTeamsByTournament($tournamentId)
    {
        $teams = Tournament::findOrFail($tournamentId)->teams;
        return response()->json($teams);
    }

    public function getinfoByTournament($tournamentId)
    {
        $teams = Tournament::findOrFail($tournamentId);
        return response()->json($teams);
    }

    public function registerTeam(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string|max:20',
            'dni' => 'required|string|max:255|unique:users,dni',
            'email' => 'nullable|email|unique:users,email', // Email nullable pero único si existe
            'position' => 'required|string|max:255',
            'number' => [
                'required',
                'numeric',
                'between:1,99999',
                Rule::unique('players')->where(function ($query) use ($request) {
                    return $query->where('team_id', $request->team_id);
                }),
            ],
            'player_photo' => 'nullable|image|max:2048', // Campo del formulario para la foto del jugador
            'document_photo' => 'nullable|image|max:2048', // Campo del formulario para la foto del documento
            'team_id' => 'required|exists:teams,id',
            'tournament_id' => 'required|exists:tournaments,id',
        ]);

        // Concatenar nombre y apellido para la columna 'name' del User
        $fullName = $request->first_name . ' ' . $request->last_name;
        $request->merge(['name' => $fullName]); // Agrega 'name' al request para usarlo fácilmente

        // Generar correo y contraseña aleatorios si no se proporcionan
        if (!$request->filled('email')) {
            $generatedEmail = Str::random(10) . '@mi-liga.com';
            $request->merge(['email' => $generatedEmail]);
        }
        while (User::where('email', $request->email)->exists()) {
            $request->merge(['email' => Str::random(10) . '@mi-liga.com']);
        }

        if (!$request->filled('password')) {
            $randomPassword = Str::random(10);
            $request->merge(['password' => $randomPassword, 'password_confirmation' => $randomPassword]);
        }

        // Buscar usuario existente por email o DNI
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $user = User::where('dni', $request->dni)->first();
        }

        if ($user) {
            // Un usuario existente ha sido encontrado
            // Verificar si el jugador ya está registrado con ese equipo para ese torneo
            $player = Player::select('players.*')
                ->join('player_team_tournament', 'players.id', '=', 'player_team_tournament.player_id')
                ->where('player_team_tournament.team_id', $request->team_id)
                ->where('player_team_tournament.tournament_id', $request->tournament_id)
                ->where('players.user_id', $user->id)
                ->first();

            if ($player) {
                Alert::info('Info', 'El Jugador ya está registrado y asociado a este equipo en el torneo.');
                return redirect()->back();
            } else {
                // Asociar el usuario existente a un nuevo registro de jugador
                $playerData = $request->only(['position', 'number', 'team_id']);
                $playerData['user_id'] = $user->id;

                // Manejo de la foto del jugador
                if ($request->hasFile('player_photo')) {
                    $playerData['player_photo_path'] = $request->file('player_photo')->store('players/photos', 'public');
                }
                // Manejo de la foto del documento
                if ($request->hasFile('document_photo')) {
                    $playerData['document_photo_path'] = $request->file('document_photo')->store('players/documents', 'public');
                }

                $player = Player::create($playerData);
                $player->tournaments()->attach($request->tournament_id, ['team_id' => $request->team_id]);

                // Asignar el rol 'player' al usuario si no lo tiene
                if (!$user->hasRole('player')) {
                    $user->assignRole('player');
                }

                Alert::success('Éxito', 'Jugador existente asociado exitosamente a un nuevo equipo en el torneo.');
                return redirect()->route('front.inscription');
            }
        } else {
            // No se encontró un usuario existente, registrar un nuevo usuario y jugador
            $userData = $request->only(['name', 'email', 'dni', 'phone_number', 'date_of_birth']); // 'name' ya contiene nombre y apellido
            $userData['password'] = Hash::make($request->password);
            $user = User::create($userData);

            $playerData = $request->only(['position', 'number', 'team_id']);
            $playerData['user_id'] = $user->id;

            // Manejo de la foto del jugador
            if ($request->hasFile('player_photo')) {
                $playerData['player_photo_path'] = $request->file('player_photo')->store('players/photos', 'public');
            }
            // Manejo de la foto del documento
            if ($request->hasFile('document_photo')) {
                $playerData['document_photo_path'] = $request->file('document_photo')->store('players/documents', 'public');
            }

            $player = Player::create($playerData);
            $player->tournaments()->attach($request->tournament_id, ['team_id' => $request->team_id]);

            // Asignar el rol 'player' al nuevo usuario
            $user->assignRole('player');

            Alert::success('Éxito', 'Nuevo Jugador registrado exitosamente en el equipo para el torneo.');
            return redirect()->route('front.inscription');
        }
    }

    public function teamPage($teamId)
    {
        $team = Team::with([
            'players.user',
            'fixturesHome.tournament',
            'fixturesAway.tournament',
            'tournaments'
        ])->findOrFail($teamId);
    
        // Torneo actual activo o más reciente no finalizado
        $currentTournament = $team->tournaments()
            ->where('status', true)
            ->orWhere('end_date', '>=', now())
            ->latest('start_date')
            ->first();
    
        // Agrupamos los jugadores por torneo
        $playersByTournament = $team->tournaments->mapWithKeys(function ($tournament) use ($team) {
            $players = $team->tournamentPlayers($tournament->id)->with('user')->get();
            return [$tournament->name => $players];
        });
    
        // Partidos (local + visitante), ordenados por fecha
        $allFixtures = $team->fixturesHome->merge($team->fixturesAway)->sortByDesc('match_date');
    
        // Agrupamos los partidos por torneo
        $fixturesByTournament = $allFixtures->groupBy(function ($fixture) {
            return $fixture->tournament->name ?? 'Sin torneo';
        });
    
        // IDs de jugadores del equipo
        $teamIds = [$team->id];
    
        // Goleadores del equipo agrupados por torneo
        $topScorersByTournament = [];
    
        foreach ($team->tournaments as $tournament) {
            $scorers = PlayerFixtureEvent::where('event_type', 'goal')
                ->whereHas('fixture', function ($q) use ($tournament) {
                    $q->where('tournament_id', $tournament->id);
                })
                ->whereHas('player', function ($q) use ($teamIds) {
                    $q->whereIn('team_id', $teamIds);
                })
                ->select('player_id', DB::raw('SUM(quantity) as goals'))
                ->groupBy('player_id')
                ->having('goals', '>', 0)
                ->orderBy('goals', 'desc')
                ->with('player.user', 'player.team')
                ->get();
    
            if ($scorers->isNotEmpty()) {
                $topScorersByTournament[$tournament->name] = $scorers;
            }
        }
    
        return view('front.team.show', compact(
            'team',
            'playersByTournament',
            'fixturesByTournament',
            'currentTournament',
            'topScorersByTournament'
        ));
    }
}
