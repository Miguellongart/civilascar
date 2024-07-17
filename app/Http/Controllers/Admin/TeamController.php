<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

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
            ['label' => 'Entrenador', 'width' => 20],
            ['label' => 'Estadio', 'width' => 20],
            ['label' => 'Logo', 'width' => 20],
            ['label' => 'Acciones', 'no-export' => true, 'width' => 20],
        ];

        $teams = Team::all();

        $config = [
            'data' => $teams->map(function($team) {

                if (auth()->user()->can('admin.team.edit')) {
                    $btnEdit = '<a href="' . route('admin.team.edit', $team) . '" class="btn btn-sm btn-primary mx-1 shadow" title="Edit">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </a>';
                }
                if (auth()->user()->can('admin.team.destroy')) {
                    $btnDelete = '<form method="POST" action="' . route('admin.team.destroy', $team) . '" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="return confirm(\'Are you sure?\')">
                                    <i class="fa fa-lg fa-fw fa-trash"></i>
                                </button>
                            </form>';
                }
                if (auth()->user()->can('admin.team.show')) {
                $btnDetails = '<a href="' . route('admin.team.show', $team) . '" class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                                <i class="fa fa-lg fa-fw fa-eye"></i>
                            </a>';
                }

                $logo = $team->logo ? '<img src="' . asset('storage/' . $team->logo) . '" alt="Logo" height="50">' : 'No logo';

                return [
                    $team->id,
                    $team->name,
                    $team->coach,
                    $team->home_stadium,
                    $logo,
                    '<nobr>' . $btnEdit . $btnDelete . $btnDetails . '</nobr>'
                ];
            })->toArray(),
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, null, null, ['orderable' => false]],
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
            // Almacenar el archivo en la carpeta 'teams' dentro del sistema de archivos 'public'
            $data['logo'] = $request->file('logo')->store('teams/logos', 'public');
        }
    
        Team::create($data);
    
        return redirect()->route('admin.team.index')->with('success', 'Equipo creado exitosamente.');
    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $team = Team::with('players')->find($id);
        $subtitle = 'Información del Equipo: ' . $team->name;
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Información del Equipo: ' . $team->name;

        return view('admin.team.show', compact('team', 'subtitle', 'content_header_title', 'content_header_subtitle'));
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name,' . $team->id,
            'coach' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'home_stadium' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $data = $request->all();
        
        // Manejar la carga del archivo de logo
        if ($request->hasFile('logo')) {
            // Almacenar el archivo en la carpeta 'teams' dentro del sistema de archivos 'public'
            $data['logo'] = $request->file('logo')->store('teams/logos', 'public');
            // Eliminar el logo antiguo si existe
            if ($team->logo) {
                Storage::disk('public')->delete($team->logo);
            }
        }

        $team->update($data);

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
}
