<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $heads = [
            'ID',
            ['label' => 'Nombre', 'width' => 20],
            ['label' => 'Tipo', 'width' => 20],
            ['label' => 'Inicio', 'width' => 20],
            ['label' => 'Fin', 'width' => 20],
            ['label' => 'Logo', 'width' => 20],
            ['label' => 'Acciones', 'no-export' => true, 'width' => 20],
        ];

        $tournaments = Tournament::all();

        $config = [
            'data' => $tournaments->map(function($tournament) {
                $btnEdit = '<a href="' . route('admin.tournament.edit', $tournament) . '" class="btn btn-sm btn-primary mx-1 shadow" title="Edit">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </a>';
                $btnDelete = '<form method="POST" action="' . route('admin.tournament.destroy', $tournament) . '" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="return confirm(\'Are you sure?\')">
                                    <i class="fa fa-lg fa-fw fa-trash"></i>
                                </button>
                            </form>';
                $btnDetails = '<a href="' . route('admin.tournament.listTeams', $tournament) . '" class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                                <i class="fa fa-lg fa-fw fa-eye"></i>
                            </a>';
                $btnAddTeams = '<a href="' . route('admin.tournament.addTeams', $tournament) . '" class="btn btn-xs btn-default text-teal mx-1 shadow" title="Add Teams">
                                <i class="fa fa-lg fa-fw fa-users"></i>
                            </a>';
                $logo = $tournament->logo ? '<img src="' . asset('storage/' . $tournament->logo) . '" alt="Logo" height="50">' : 'No logo';

                return [
                    $tournament->id,
                    $tournament->name,
                    $tournament->type,
                    $tournament->start_date,
                    $tournament->end_date,
                    $logo,
                    '<nobr>' . $btnEdit . $btnDelete . $btnDetails . $btnAddTeams . '</nobr>'
                ];
            })->toArray(),
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, null, null, null, ['orderable' => false]],
        ];

        $subtitle = 'Listado de Torneos';
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Listado de Torneos';

        return view('admin.tournament.index', compact('heads', 'config', 'subtitle', 'content_header_title', 'content_header_subtitle'));
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
    public function show(string $id)
    {
        $tournament = Tournament::find($id);
        $subtitle = 'Información del Torneo: ' . $tournament->name;
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Información del Torneo: ' . $tournament->name;

        return view('admin.tournament.show', compact('tournament', 'subtitle', 'content_header_title', 'content_header_subtitle'));
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
    public function destroy(string $id)
    {
        $tournament = Tournament::find($id);
        $tournament->delete();

        return redirect()->route('admin.tournament.index')->with('success', 'Torneo eliminado exitosamente.');
    }
}
