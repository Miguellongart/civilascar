<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $heads = [
            'ID',
            ['label' => 'Nombre', 'width' => 20],
            ['label' => 'DNI', 'width' => 20],
            ['label' => 'Correo', 'width' => 20],
            ['label' => 'Actions', 'no-export' => true, 'width' => 20],
        ];
        // Aquí puedes obtener los datos de la base de datos
        $users = User::all();

        $config = [
            'data' => $users->map(function($user) {
                
                if (auth()->user()->can('admin.user.edit')) {
                    $btnEdit = '<a href="' . route('admin.user.edit', $user) . '" class="btn btn-sm btn-primary mx-1 shadow" title="Edit">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </a>';
                }
                if (auth()->user()->can('admin.user.destroy')) {
                    $btnDelete = '<form method="POST" action="' . route('admin.user.destroy', $user) . '" style="display:inline;">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="return confirm(\'Are you sure?\')">
                                        <i class="fa fa-lg fa-fw fa-trash"></i>
                                    </button>
                                </form>';
                }
                if (auth()->user()->can('admin.user.show')) {
                    $btnDetails = '<a href="' . route('admin.user.show', $user) . '" class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                                    <i class="fa fa-lg fa-fw fa-eye"></i>
                                </a>';
                }
                return [
                    $user->id,
                    $user->name,
                    $user->dni,
                    $user->email,
                    '<nobr>' . $btnEdit . $btnDelete . $btnDetails . '</nobr>'
                ];
            })->toArray(),
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, ['orderable' => false]],
        ];

        $subtitle = 'Listado usuarios';
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Listado usuarios';

        return view('admin.user.index', compact('heads', 'config', 'subtitle', 'content_header_title', 'content_header_subtitle'));
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
        dd($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $idUserLogueado = Auth()->id();
        $userlogueado = User::find($idUserLogueado);
        $user = User::findOrFail($id);
        $subtitle = 'Informacion de '.$user->name;
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Informacion de '.$user->name;
        
        return view('admin.user.show', [
            'user' => $user,
            'roles' => $userlogueado->hasRole('admin') ? Role::orderBy('name')->get() : Role::where('name','!=', 'admin')->orderBy('name')->get(),
            'permissions' => Permission::orderBy('name')->get(),
            'subtitle' => $subtitle,
            'content_header_title' => $content_header_title,
            'content_header_subtitle' => $content_header_subtitle
        ]);

        return view('admin.user.show', compact('user', 'subtitle', 'content_header_title', 'content_header_subtitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        $subtitle = 'Editando a '.$user->name;
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Editando a '.$user->name;
        return view('admin.user.edit', compact('user', 'subtitle', 'content_header_title', 'content_header_subtitle'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'dni' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            // Otros campos y reglas de validación...
        ]);

        $user->update($request->all());

        return redirect()->route('admin.user.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $row = User::findOrFail($id);
        $row->delete();
        return redirect()->route('admin.user.index')->with('success', 'User updated successfully.');
    }


    public function role(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->roles()->sync($request->roles);

        return redirect()->route('admin.user.index', $user->id)->with('success', 'Actualizado con Exito');
    }

    public function permission(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Obtener nombres desde los IDs
        $permissionNames = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();

        // Sincronizar permisos
        $user->syncPermissions($permissionNames);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Permisos actualizados con éxito');
    }
}
