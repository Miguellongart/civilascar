<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    // Método para mostrar el formulario de edición
    public function edit($id)
    {
        $player = Player::find($id); 
        // dd($player);
        $subtitle = 'Editando a '.$player->user->name;
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Editando a '.$player->user->name;
        return view('admin.player.edit', compact('player','subtitle', 'content_header_title', 'content_header_subtitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
                })->ignore($id), // Ignora el ID del jugador actual al validar la unicidad
            ],
        ]);
    
        // Encontrar el jugador y el usuario asociado
        $player = Player::findOrFail($id);
        $user = User::findOrFail($request->user_id);
    
        // Actualizar los datos del usuario
        $user->update([
            'name' => $request->input('name'),
            'dni' => $request->input('dni'),
        ]);
    
        // Actualizar los datos del jugador
        $player->update([
            'position' => $request->input('position'),
            'number' => $request->input('number'),
        ]);
    
        // Redirigir al detalle del equipo
    return redirect()->route('admin.team.show', $player->team_id)->with('success', 'Jugador y usuario actualizados exitosamente.');
    }


    // Método para eliminar el jugador
    public function destroy($id)
    {
        $player = Player::find($id);
        if ($player->photo) {
            Storage::disk('public')->delete($player->photo);
        }
        $player->delete();

    
        Alert::success('Éxito', 'Jugador eliminado exitosamente.');
        return redirect()->back();
    }
}
