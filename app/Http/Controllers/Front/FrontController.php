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

class FrontController extends Controller
{
    public function Home()
    {
        return view('front.home.index');
    }

    public function Contact()
    {
        return view('front.contact.index');
    }

    public function About()
    {
        return view('front.about.index');
    }

    public function LittleSchool()
    {
        return view('front.littleSchool.index');
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
            'name' => 'required|string|max:255',
            'dni' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'number' => [
                'required',
                'string',
                'regex:/^\d{1,5}$/',
                Rule::unique('players')->where(function ($query) use ($request) {
                    return $query->where('team_id', $request->team_id);
                }),
            ],
            'photo' => 'nullable|image|max:2048',
            'team_id' => 'required|exists:teams,id',
            'tournament_id' => 'required|exists:tournaments,id',
        ]);

        // Generar correo y contraseña aleatorios si no se proporcionan
        if (!$request->filled('email')) {
            $request->merge(['email' => Str::random(10) . '@example.com']);
        }
        if (!$request->filled('password')) {
            $randomPassword = Str::random(10);
            $request->merge(['password' => $randomPassword, 'password_confirmation' => $randomPassword]);
        }

        $user = User::where('email', $request->email)->orWhere('dni', $request->dni)->first();

        if ($user) {
            $player = Player::where('user_id', $user->id)->where('team_id', $request->team_id)->first();
            if ($player) {
                Alert::info('Info', 'El Jugador ya está registrado y asociado a este equipo en el torneo.');
                return redirect()->back();
            } else {
                // Asociar el usuario existente a un nuevo equipo
                $playerData = $request->only(['position', 'number', 'team_id']);
                $playerData['user_id'] = $user->id;
                if ($request->hasFile('photo')) {
                    $playerData['photo'] = $request->file('photo')->store('players/photos', 'public');
                }
                $player = Player::create($playerData);
                $player->tournaments()->attach($request->tournament_id, ['team_id' => $request->team_id]);

                // Asignar el rol 'player' al usuario
                if (!$user->hasRole('player')) {
                    $user->assignRole('player');
                }

                Alert::success('Éxito', 'Jugador existente asociado exitosamente a un nuevo equipo en el torneo.');
                return redirect()->route('front.inscription');
            }
        } else {
            // Registrar un nuevo usuario
            $userData = $request->only(['name', 'email', 'dni', 'password']);
            $userData['password'] = Hash::make($request->password);
            $user = User::create($userData);

            $playerData = $request->only(['position', 'number', 'team_id']);
            $playerData['user_id'] = $user->id;
            if ($request->hasFile('photo')) {
                $playerData['photo'] = $request->file('photo')->store('players/photos', 'public');
            }
            $player = Player::create($playerData);
            $player->tournaments()->attach($request->tournament_id, ['team_id' => $request->team_id]);

            // Asignar el rol 'player' al usuario
            $user->assignRole('player');

            Alert::success('Éxito', 'Nuevo Jugador registrado exitosamente en el equipo para el torneo.');
            return redirect()->route('front.inscription');
        }
    }

    public function register(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'parent_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'neighborhood' => 'required|string|max:255',
            'children.*.name' => 'required|string|max:255',
            'children.*.age' => 'required|integer|min:1|max:18',
            'children.*.uniform_size' => 'required|string|max:10',
            'guardians.*.name' => 'required|string|max:255',
            'guardians.*.relationship' => 'required|string|max:255',
            'guardians.*.document' => 'required|string|max:255',
        ]);

        // Verificar si el DNI ya existe en la base de datos
        $dni = $request->input('document');
        $existingUser = User::where('dni', $dni)->first();

        if ($existingUser) {
            // Si el usuario ya existe, actualizar datos faltantes
            $existingUser->update([
                'name' => $existingUser->name ?: $request->input('parent_name'),
                'phone' => $existingUser->name ?: $request->input('phone'),
                'email' => $existingUser->email ?: $request->input('email'),
                'password' => $existingUser->password ?: Hash::make($request->input('password')),
                'document' => $existingUser->document ?: $request->input('document'),
                'neighborhood' => $existingUser->neighborhood ?: $request->input('neighborhood'),
                // 'parent_document_path' => $existingUser->parent_document_path ?: ($request->file('parent_document') ? $request->file('parent_document')->store('documents/parents') : $existingUser->parent_document_path),
            ]);

            $user = $existingUser;
        } else {
            // Crear un nuevo usuario si no existe
            $user = User::create([
                'name' => $request->input('parent_name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'document' => $request->input('document'),
                'dni' => $request->input('document'),
                'neighborhood' => $request->input('neighborhood'),
                // 'parent_document_path' => $request->file('parent_document')->store('documents/parents'),
                'phone' => $request->input('phone'),
            ]);
        }

        foreach ($request->input('children') as $index => $childData) {
            $user->children()->create([
                'name' => $childData['name'],
                'age' => $childData['age'],
                'uniform_size' => $childData['uniform_size'],
                'document' => $childData['document'],
                'birthdate' => $childData['birthdate'],
            ]);
        }

        foreach ($request->input('guardians') as $guardianData) {
            $user->guardians()->create([
                'name' => $guardianData['name'],
                'relationship' => $guardianData['relationship'],
                'document' => $guardianData['document'],
            ]);
        }
        // Autenticar al usuario
        Alert::success('Éxito', 'Nuevo Registro de integrante Escuelita.');
        return redirect()->route('front.school');
    }
}
