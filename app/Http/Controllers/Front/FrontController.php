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
use Illuminate\Support\Facades\Hash;

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
    
        $firstFixtureDate = Fixture::orderBy('match_date')->first()->match_date;
        $fixtures = Fixture::where('match_date', $firstFixtureDate)->orderBy('match_date')->get();
    
        if ($request->has('filter_date')) {
            $fixtures = Fixture::where('match_date', $request->filter_date)->orderBy('match_date')->get();
        }
    
        return view('front.tournament.index', compact('tournaments', 'fixtures'));
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
            'email' => 'required|string|email|max:255',
            'dni' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'position' => 'required|string|max:255',
            'number' => 'required|integer',
            'photo' => 'nullable|image|max:2048',
            'team_id' => 'required|exists:teams,id',
            'tournament_id' => 'required|exists:tournaments,id',
        ]);
    
        $user = User::where('email', $request->email)->orWhere('dni', $request->dni)->first();
    
        if ($user) {
            $player = Player::where('user_id', $user->id)->where('team_id', $request->team_id)->first();
            if ($player) {
                return redirect()->back()->with('error', 'El usuario ya está registrado y asociado a este equipo.');
            } else {
                // Asociar el usuario existente a un nuevo equipo
                $playerData = $request->only(['position', 'number', 'team_id']);
                $playerData['user_id'] = $user->id;
                if ($request->hasFile('photo')) {
                    $playerData['photo'] = $request->file('photo')->store('players/photos', 'public');
                }
                $player = Player::create($playerData);
                $player->tournaments()->attach($request->tournament_id, ['team_id' => $request->team_id]);
    
                return redirect()->route('front.inscription')->with('success', 'Usuario existente asociado exitosamente a un nuevo equipo en el torneo.');
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
    
            return redirect()->route('front.inscription')->with('success', 'Nuevo usuario registrado y equipo inscrito exitosamente en el torneo.');
        }
    }
}
