<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Children;
use Illuminate\Http\Request;

class LittleSchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subtitle = 'Fixture';
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Editar Fixture';
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
