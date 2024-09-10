<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    
    // public function __construct()
    // {
    //     $this->middleware('can:admin.permissions.index')->only('index');
    //     $this->middleware('can:admin.permissions.edit')->only(['edit','store','show']);
    // }

    public function index()
    {
        $permisions = Permission::all();
        $title = 'Permisos';
        return view('admin.permission.index', compact('permisions', 'title'));
    }

    public function show(Permission $permission)
    {
        $title = 'Actualizar Permiso';
        return view('admin.permission.show', compact('permission', 'title'));
    }
}
