<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhotoGallery;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoGalleryController extends Controller
{
    public function index(Request $request, $tournamentId)
    {
        $subtitle = 'Galeria';
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Listado de galeria de fotos';
        $tournament = Tournament::findOrFail($tournamentId);
        $galleries = PhotoGallery::where('tournament_id', $tournamentId)
                                ->orderBy('match_date', 'desc')
                                ->get();

        return view('admin.gallery.index', compact('tournament', 'galleries', 'subtitle', 'content_header_title', 'content_header_subtitle'));
    }

    public function create($tournamentId)
    {
        $subtitle = 'Galeria';
        $content_header_title = 'Dashboard';
        $content_header_subtitle = 'Listado de galeria de fotos';
        $tournament = Tournament::findOrFail($tournamentId);
        return view('admin.gallery.create', compact('tournament','subtitle', 'content_header_title', 'content_header_subtitle'));
    }

    public function store(Request $request, $tournamentId)
    {
        $request->validate([
            'match_date' => 'required|date',
            'photos.*' => 'required|image|max:5120',  // 5120 KB = 5 MB
        ]);
        
        $tournament = Tournament::findOrFail($tournamentId);
    
        if ($request->hasFile('photos')) {
            $photos = $request->file('photos');
            $batchSize = 10;  // Definir el tamaño del lote
            $photoBatches = array_chunk($photos, $batchSize);  // Dividir las fotos en lotes de 10
    
            foreach ($photoBatches as $batch) {
                foreach ($batch as $photo) {
                    $path = $photo->store('galleries/'.$tournamentId, 'public');
    
                    PhotoGallery::create([
                        'tournament_id' => $tournamentId,
                        'match_date' => $request->match_date,
                        'photo_path' => $path,
                    ]);
                }
            }
        }
    
        return redirect()->route('admin.gallery.index', $tournamentId)->with('success', 'Fotos subidas exitosamente.');
    }

    public function destroy($id)
    {
        $gallery = PhotoGallery::findOrFail($id);
        Storage::disk('public')->delete($gallery->photo_path);
        $gallery->delete();

        return redirect()->back()->with('success', 'Foto eliminada exitosamente.');
    }
}
