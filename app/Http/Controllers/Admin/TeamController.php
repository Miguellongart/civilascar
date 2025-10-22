<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $heads = [
            'ID',
            ['label' => 'Nombre', 'width' => 20],
            ['label' => 'Entrenador', 'width' => 15],
            ['label' => 'Estadio', 'width' => 15],
            ['label' => 'Torneos', 'width' => 15],
            ['label' => 'Logo', 'width' => 15],
            ['label' => 'Acciones', 'no-export' => true, 'width' => 20],
        ];

        $teams = Team::withCount('tournaments')->with('tournaments')->get();

        $config = [
            'data' => $teams->map(function($team) {
                $btnEdit = '';
                $btnDelete = '';
                $btnDetails = '';

                if (auth()->user()->can('admin.team.edit')) {
                    $btnEdit = '<a href="' . route('admin.team.edit', $team) . '" class="btn btn-sm btn-primary mx-1 shadow" title="Editar">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </a>';
                }

                if (auth()->user()->can('admin.team.destroy')) {
                    $btnDelete = '<form method="POST" action="' . route('admin.team.destroy', $team) . '" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow" title="Eliminar" onclick="return confirm(\'¿Estás seguro?\')">
                                    <i class="fa fa-lg fa-fw fa-trash"></i>
                                </button>
                            </form>';
                }

                if (auth()->user()->can('admin.team.show')) {
                    $btnDetails = '<a href="' . route('admin.team.show', $team->id) . '" class="btn btn-xs btn-default text-info mx-1 shadow" title="Ver Detalles">
                                    <i class="fa fa-lg fa-fw fa-eye"></i>
                                </a>';
                }

                $logo = $team->logo ? '<img src="' . asset('storage/' . $team->logo) . '" alt="Logo" height="50" class="rounded-circle" style="object-fit: cover;">' : '<span class="badge badge-secondary">Sin logo</span>';

                // Mostrar torneos asociados
                $tournamentsInfo = '';
                if ($team->tournaments_count > 0) {
                    $tournamentNames = $team->tournaments->take(2)->pluck('name')->implode(', ');
                    $tournamentsInfo = '<span class="badge badge-info">' . $team->tournaments_count . ' torneos</span><br><small>' . $tournamentNames . ($team->tournaments_count > 2 ? '...' : '') . '</small>';
                } else {
                    $tournamentsInfo = '<span class="badge badge-warning">Sin torneos</span>';
                }

                return [
                    $team->id,
                    '<strong>' . $team->name . '</strong>',
                    $team->coach ?? '<span class="text-muted">Sin entrenador</span>',
                    $team->home_stadium ?? '<span class="text-muted">Sin estadio</span>',
                    $tournamentsInfo,
                    $logo,
                    '<nobr>' . $btnEdit . $btnDelete . $btnDetails . '</nobr>'
                ];
            })->toArray(),
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, null, null, null, ['orderable' => false]],
        ];

        $subtitle = 'Listado de Equipos';
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Listado de Equipos';

        return view('admin.team.index', compact('heads', 'config', 'subtitle', 'content_header_title', 'content_header_subtitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subtitle = 'Crear Equipo';
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Crear Equipo';
        $users = User::all();

        return view('admin.team.create', compact('subtitle', 'content_header_title', 'content_header_subtitle', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name',
            'coach' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'home_stadium' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
        ]);
    
        $data = $request->all();

        // Manejar la carga del archivo de logo
        if ($request->hasFile('logo')) {
            // Almacenar el archivo en la carpeta 'teams/logos' dentro del sistema de archivos 'public'
            $data['logo'] = $request->file('logo')->store('teams/logos', 'public');
        }

        // Generar slug si no existe
        if (!isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        Team::create($data);

        return redirect()->route('admin.team.index')->with('success', 'Equipo creado exitosamente.');
    }
    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $idTeam)
    {
        // Obtener el equipo con sus relaciones
        $team = Team::with(['tournaments', 'players.user'])->findOrFail($idTeam);
        $allTeams = Team::all();
        $allTournaments = Tournament::all();

        // Determinar el torneo a mostrar
        $idTournament = $request->get('tournament_id');

        // Si no viene torneo, intentar usar el primer torneo del equipo
        if (!$idTournament && $team->tournaments->isNotEmpty()) {
            $idTournament = $team->tournaments->first()->id;
        }

        // Obtener jugadores según el torneo seleccionado
        $players = collect();
        if ($idTournament) {
            $players = Player::select('players.*')
                ->join('player_team_tournament', 'players.id', '=', 'player_team_tournament.player_id')
                ->where('player_team_tournament.team_id', $idTeam)
                ->where('player_team_tournament.tournament_id', $idTournament)
                ->with('user')
                ->get();
        }

        // Estadísticas del equipo
        $stats = [
            'total_tournaments' => $team->tournaments->count(),
            'total_players' => $players->count(),
            'total_fixtures' => $team->fixturesHome->count() + $team->fixturesAway->count(),
        ];

        $subtitle = 'Información del Equipo: ' . $team->name;
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Información del Equipo: ' . $team->name;

        return view('admin.team.show', compact(
            'team',
            'players',
            'allTeams',
            'allTournaments',
            'idTournament',
            'stats',
            'subtitle',
            'content_header_title',
            'content_header_subtitle'
        ));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $team = Team::find($id);
        $subtitle = 'Editando Equipo: ' . $team->name;
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Editando Equipo: ' . $team->name;
        $users = User::all();

        return view('admin.team.edit', compact('team', 'subtitle', 'content_header_title', 'content_header_subtitle', 'users'));
    }

    public function update(Request $request, Team $team)
    {
        // Validación de datos
        $request->validate([
            'name' => 'required|string|max:255',
            'coach' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'home_stadium' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Obtener todos los datos del request excepto el logo
        $data = $request->except('logo');

        // Manejar la carga del archivo de logo
        if ($request->hasFile('logo')) {
            // Almacenar el archivo en la carpeta 'teams/logos' dentro del sistema de archivos 'public'
            $logoPath = $request->file('logo')->store('teams/logos', 'public');
            $data['logo'] = $logoPath;

            // Eliminar el logo antiguo si existe
            if ($team->logo) {
                Storage::disk('public')->delete($team->logo);
            }
        }

        // Generar un slug único
        if (!isset($data['slug'])) {
            $slug = Str::slug($data['name']);
            $originalSlug = $slug;
            $counter = 1;
            
            while (Team::where('slug', $slug)->where('id', '!=', $team->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $data['slug'] = $slug;
        }

        // Actualizar los datos del equipo directamente
        $team->update($data);

        // Redireccionar con un mensaje de éxito
        return redirect()->route('admin.team.index')->with('success', 'Equipo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $team = Team::find($id);
        if ($team->logo) {
            Storage::disk('public')->delete($team->logo);
        }
        $team->delete();

        return redirect()->route('admin.team.index')->with('success', 'Equipo eliminado exitosamente.');
    }

    public function transferPlayer(Request $request)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'from_team_id' => 'required|exists:teams,id',
            'from_tournament_id' => 'required|exists:tournaments,id',
            'to_team_id' => 'required|exists:teams,id',
            'to_tournament_id' => 'required|exists:tournaments,id',
        ]);

        $exists = DB::table('player_team_tournament')
            ->where('player_id', $request->player_id)
            ->where('team_id', $request->to_team_id)
            ->where('tournament_id', $request->to_tournament_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Este jugador ya está inscrito en ese equipo y torneo.');
        }

        DB::table('player_team_tournament')->insert([
            'player_id' => $request->player_id,
            'team_id' => $request->to_team_id,
            'tournament_id' => $request->to_tournament_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Jugador transferido correctamente.');
    }
}
