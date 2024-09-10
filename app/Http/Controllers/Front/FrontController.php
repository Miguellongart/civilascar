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
    public function Home(){
        return view('front.home.index');
    }

    public function Contact(){
        return view('front.contact.index');
    }

    public function About(){
        return view('front.about.index');
    }

    public function LittleSchool(){
        return view('front.littleSchool.index');

    }
    
    public function tournament(Request $request)
    {
        $tournaments = Tournament::with(['teams', 'fixtures', 'positionTables'])->get();
    
        // Obtener las fechas de los fixtures
        $dates = Fixture::selectRaw('DATE(match_date) as match_date')
                        ->distinct()
                        ->orderBy('match_date')
                        ->get()
                        ->pluck('match_date');
    
        // Determinar la siguiente fecha disponible o usar la fecha del filtro
        $nextDate = $dates->firstWhere(function ($date) {
            return $date >= now()->format('Y-m-d');
        });
    
        $filterDate = $request->get('filter_date', $nextDate);
    
        // Obtener los fixtures correspondientes a la fecha seleccionada
        $fixtures = Fixture::with(['homeTeam', 'awayTeam'])
            ->whereDate('match_date', $filterDate)
            ->orderBy(DB::raw('TIME(match_date)'))
            ->orderByRaw("FIELD(status, 'completed', 'scheduled', 'canceled')")
            ->get();
    
        // Ordenar la tabla de posiciones
        foreach ($tournaments as $tournament) {
            $tournament->positionTables = $tournament->positionTables->sortByDesc(function ($position) {
                return [$position->points, $position->goal_difference, $position->goals_for];
            });
        }
       
        // Obtener los jugadores con la suma de goles mayores a 0
        $topScorers = PlayerFixtureEvent::where('event_type', 'goal')
            ->select('player_id', DB::raw('SUM(quantity) as goals'))
            ->groupBy('player_id')
            ->having('goals', '>', 0)
            ->orderBy('goals', 'desc')
            ->with('player.user', 'player.team')
            ->get();

        // Obtener los jugadores con la suma de tarjetas amarillas
        $yellowCards = PlayerFixtureEvent::where('event_type', 'yellow_card')
            ->select('player_id', DB::raw('SUM(quantity) as yellow_cards'))
            ->groupBy('player_id')
            ->orderBy('yellow_cards', 'desc')
            ->with('player.user', 'player.team')
            ->get();

        // Obtener los jugadores con la suma de tarjetas rojas
        $redCards = PlayerFixtureEvent::where('event_type', 'red_card')
            ->select('player_id', DB::raw('SUM(quantity) as red_cards'))
            ->groupBy('player_id')
            ->orderBy('red_cards', 'desc')
            ->with('player.user', 'player.team')
            ->get();

    
        return view('front.tournament.index', compact('tournaments', 'fixtures', 'filterDate', 'dates','topScorers', 'yellowCards', 'redCards'));
    }

    public function inscription()
    {
        $tournaments = Tournament::all();
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
                Alert::error('Error', 'El usuario ya está registrado y asociado a este equipo.');
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
    
                Alert::success('Éxito', 'Usuario existente asociado exitosamente a un nuevo equipo en el torneo.');
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
    
            Alert::success('Éxito', 'Nuevo usuario registrado y equipo inscrito exitosamente en el torneo.');
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
            'parent_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png,heic|max:10240',
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
                'email' => $existingUser->email ?: $request->input('email'),
                'password' => $existingUser->password ?: Hash::make($request->input('password')),
                'document' => $existingUser->document ?: $request->input('document'),
                'neighborhood' => $existingUser->neighborhood ?: $request->input('neighborhood'),
                'parent_document_path' => $existingUser->parent_document_path ?: ($request->file('parent_document') ? $request->file('parent_document')->store('documents/parents') : $existingUser->parent_document_path),
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
                'parent_document_path' => $request->file('parent_document')->store('documents/parents'),
            ]);
        }

        foreach ($request->input('children') as $index => $childData) {    
            $user->children()->create([
                'name' => $childData['name'],
                'age' => $childData['age'],
                'uniform_size' => $childData['uniform_size'],
                'document' => $childData['document'],
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
