<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index(Request $request)
    {
        $heads = [
            'ID',
            ['label' => 'Nombre', 'width' => 20],
            ['label' => 'Tipo', 'width' => 15],
            ['label' => 'Estado', 'width' => 15],
            ['label' => 'Equipos', 'width' => 10],
            ['label' => 'Inicio', 'width' => 15],
            ['label' => 'Fin', 'width' => 15],
            ['label' => 'Acciones', 'no-export' => true, 'width' => 40],
        ];

        // Filtrar torneos por status si se proporciona
        $query = Tournament::withCount('teams');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tournaments = $query->orderBy('created_at', 'desc')->get();

        // Contadores para estadísticas
        $statusCounts = [
            'all' => Tournament::count(),
            'planned' => Tournament::where('status', 'planned')->count(),
            'in_progress' => Tournament::where('status', 'in_progress')->count(),
            'finished' => Tournament::where('status', 'finished')->count(),
        ];

        $config = [
            'data' => $tournaments->map(function($tournament) {
                $btnEdit = '';
                $btnDetails = '';
                $btnAddTeams = '';
                $btnFixtures = '';
                $btnGallery = '';

                if (auth()->user()->can('admin.tournament.edit')) {
                    $btnEdit = '<a href="' . route('admin.tournament.edit', $tournament) . '" class="btn btn-sm btn-primary mx-1 shadow" title="Editar">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </a>';
                }

                $btnDetails = '<a href="' . route('admin.tournament.show', $tournament) . '" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Ver Detalles">
                                <i class="fa fa-lg fa-fw fa-eye"></i>
                            </a>';

                if (auth()->user()->can('admin.tournament.edit')) {
                    $btnAddTeams = '<a href="' . route('admin.tournament.addTeams', $tournament) . '" class="btn btn-xs btn-default text-success mx-1 shadow" title="Agregar Equipos">
                                    <i class="fa fa-lg fa-fw fa-users"></i>
                                </a>';
                }
                if (auth()->user()->can('admin.fixture.index')) {
                    $btnFixtures = '<a href="' . route('admin.fixture.index', $tournament) . '" class="btn btn-xs btn-default text-info mx-1 shadow" title="Ver Partidos">
                                    <i class="fa fa-lg fa-fw fa-calendar-alt"></i>
                                </a>';
                }

                $btnGallery = '<a href="' . route('admin.gallery.index', $tournament) . '" class="btn btn-xs btn-default text-warning mx-1 shadow" title="Ver Galería">
                                <i class="fas fa-photo-video"></i>
                            </a>';

                // Badge de status con colores
                $statusBadge = '';
                switch ($tournament->status) {
                    case 'planned':
                        $statusBadge = '<span class="badge badge-info">Planificado</span>';
                        break;
                    case 'in_progress':
                        $statusBadge = '<span class="badge badge-success">En Progreso</span>';
                        break;
                    case 'finished':
                        $statusBadge = '<span class="badge badge-secondary">Finalizado</span>';
                        break;
                    default:
                        $statusBadge = '<span class="badge badge-light">' . ucfirst($tournament->status) . '</span>';
                }

                // Tipo de torneo traducido
                $typeTranslated = '';
                switch ($tournament->type) {
                    case 'league':
                        $typeTranslated = 'Liga';
                        break;
                    case 'cup':
                        $typeTranslated = 'Copa';
                        break;
                    case 'quadrangular':
                        $typeTranslated = 'Cuadrangular';
                        break;
                    default:
                        $typeTranslated = ucfirst($tournament->type);
                }

                return [
                    $tournament->id,
                    '<strong>' . $tournament->name . '</strong>',
                    $typeTranslated,
                    $statusBadge,
                    '<span class="badge badge-primary">' . $tournament->teams_count . ' equipos</span>',
                    date('d/m/Y', strtotime($tournament->start_date)),
                    date('d/m/Y', strtotime($tournament->end_date)),
                    '<nobr>' . $btnEdit . $btnDetails . $btnAddTeams . $btnFixtures . $btnGallery . '</nobr>'
                ];
            })->toArray(),
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, null, null, null, null, ['orderable' => false]],
        ];

        $subtitle = 'Listado de Torneos';
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Listado de Torneos';

        return view('admin.tournament.index', compact('heads', 'config', 'subtitle', 'content_header_title', 'content_header_subtitle', 'statusCounts'));
    }

    public function addTeams(Tournament $tournament)
    {
        $teams = Team::all();
        return view('admin.tournament.add_teams', compact('tournament', 'teams'));
    }

    public function storeTeams(Request $request, Tournament $tournament)
    {
        $request->validate([
            'teams' => 'required|array',
            'teams.*' => 'exists:teams,id',
        ]);

        $tournament->teams()->sync($request->teams);

        return redirect()->route('admin.tournament.index')->with('success', 'Equipos añadidos exitosamente.');
    }
    
    public function listTeams(Tournament $tournament)
    {
        $teams = $tournament->teams()->get();
        $subtitle = 'Listado de Equipos en ' . $tournament->name;
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Listado de Equipos en ' . $tournament->name;
    
        return view('admin.tournament.list_teams', compact('teams', 'tournament', 'subtitle', 'content_header_title', 'content_header_subtitle'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subtitle = 'Crear Torneo';
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Crear Torneo';

        return view('admin.tournament.create', compact('subtitle', 'content_header_title', 'content_header_subtitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50|in:league,cup,quadrangular',
            'max_teams' => 'required|integer|min:1',
            'min_teams' => 'required|integer|min:1|lte:max_teams',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'status' => 'required|string|max:50|in:planned,in_progress,finished',
            'rules' => 'nullable|string',
            'prizes' => 'nullable|string|max:255',
            'limit_teams' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);
    
        $data = $request->all();
    
        // Manejar la carga del archivo de logo
        if ($request->hasFile('logo')) {
            // Almacenar el archivo en la carpeta 'tournament' dentro del sistema de archivos 'public'
            $data['logo'] = $request->file('logo')->store('tournament/logos', 'public');
        }
    
        Tournament::create($data);
    
        return redirect()->route('admin.tournament.index')->with('success', 'Torneo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tournament $tournament)
    {
        // Cargar relaciones y estadísticas
        $tournament->load(['teams', 'fixtures', 'positionTables']);

        // Estadísticas del torneo
        $stats = [
            'total_teams' => $tournament->teams->count(),
            'total_fixtures' => $tournament->fixtures->count(),
            'completed_fixtures' => $tournament->fixtures->where('status', 'completed')->count(),
            'scheduled_fixtures' => $tournament->fixtures->where('status', 'scheduled')->count(),
            'total_goals' => $tournament->fixtures->where('status', 'completed')
                ->sum(function($fixture) {
                    return $fixture->home_team_score + $fixture->away_team_score;
                }),
        ];

        $subtitle = 'Información del Torneo: ' . $tournament->name;
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Información del Torneo: ' . $tournament->name;

        return view('admin.tournament.show', compact('tournament', 'subtitle', 'content_header_title', 'content_header_subtitle', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tournament = Tournament::find($id);
        $subtitle = 'Editando Torneo: ' . $tournament->name;
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Editando Torneo: ' . $tournament->name;

        return view('admin.tournament.edit', compact('tournament', 'subtitle', 'content_header_title', 'content_header_subtitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tournament $tournament)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'max_teams' => 'required|integer',
            'min_teams' => 'required|integer',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'status' => 'required|string|max:50',
            'rules' => 'nullable|string',
            'prizes' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        $tournament->update($request->all());

        return redirect()->route('admin.tournament.index')->with('success', 'Torneo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tournament $tournament)
    {
        // $tournament->delete();

        return redirect()->route('admin.tournament.index')->with('success', 'Torneo eliminado exitosamente.');
    }
}
