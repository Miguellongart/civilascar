<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    
    // public function __construct()
    // {
    //     $this->middleware('can:admin.rol.index')->only('index');
    //     $this->middleware('can:admin.rol.create')->only(['create','store']);
    //     $this->middleware('can:admin.rol.edit')->only(['edit','store']);
    //     $this->middleware('can:admin.rol.destroy')->only('destroy');
    // }

    public function index()
    {
        $roles = Role::all();
        $title = 'Roles';
        return view('admin.rol.index', compact('roles', 'title'));
    }
    public function create()
    {
        $title = 'Crear Rol';
        return view('admin.rol.create', [
            'row' => new Role(),
            'permissions' => Permission::all(),
            'title' => $title,
        ]);
    }

    public function store(Request $request)
    {
        $row = new Role($request->all());
        $row->save();

        $row->permissions()->sync($request->permission);

        return redirect()->route('admin.rol.show', $row->id)->with('success', 'Guardado con Exito');
    }

    public function show( $id)
    {
        $title = 'Detalles Rol';
        $role = Role::findOrFail($id);
        return view('admin.rol.show', [
            'row' => $role->load('permissions'),
            'title' => $title,
        ]);
    }

    public function edit($id)
    {
        $title = 'Editar Rol';
        $role = Role::findOrFail($id);
        return view('admin.rol.edit', [
            'row' => $role,
            'permissions' => Permission::all(),
            'title' => $title,
        ]);
    }

    public function update(Request $request, $id)
    {
        $row = Role::findOrFail($id);
        $row->update($request->all());
        $row->permissions()->sync($request->permission);

        return redirect()->route('admin.rol.show', $row->id)->with('success', 'Actualizado con Exito');
    }

    public function destroy($id)
    {
        $row = Role::findOrFail($id);
        $row->delete();
        return redirect()->route('admin.rol.index')->with('danger', 'Eliminado con Exito');
    }
}
