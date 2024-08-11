<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Fixture;
use App\Models\Player;
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
    
        return view('front.tournament.index', compact('tournaments', 'fixtures', 'filterDate', 'dates'));
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
}
