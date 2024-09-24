<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ChildrenExport;
use App\Http\Controllers\Controller;
use App\Models\Children;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LittleSchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subtitle = 'Escuelita';
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Listado de participantes';
        $children = Children::with('parent')->get();
        return view('admin.children.index', compact('children','subtitle', 'content_header_title', 'content_header_subtitle'));
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
        $subtitle = 'Escuelita';
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Listado de participantes';
        // Cargar al niño con su padre y guardianes
        $child = Children::with('parent', 'parent.guardians')->findOrFail($id);

        return view('admin.children.show', compact('child', 'subtitle', 'content_header_title', 'content_header_subtitle'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subtitle = 'Editar';
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Editar Alumno';
        $child = Children::findOrFail($id);
        return view('admin.children.edit', compact('child','subtitle', 'content_header_title', 'content_header_subtitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validar los datos recibidos
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:1',
            'uniform_size' => 'required|string|max:50',
            'document' => 'required|string|max:255|unique:childrens,document,' . $id, // asegurarse de que el documento es único excepto para el niño actual
            'child_document_path' => 'nullable|string|max:255',
        ]);

        // Buscar al niño por su ID
        $child = Children::findOrFail($id);

        // Actualizar los datos del niño
        $child->update($validatedData);

        // Redirigir con un mensaje de éxito
        return redirect()->route('children.index')->with('success', 'Los datos del niño han sido actualizados correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    { 
        $child = Children::findOrFail($id);
        $child->delete();
        return redirect()->route('admin.littleSchool.index')->with('success', 'El niño ha sido eliminado correctamente.');
    }

    // Función para exportar la lista de niños a Excel
    public function exportChildren()
    {
        return Excel::download(new ChildrenExport, 'lista_niños_escuelita_2024.xlsx');
    }
}
