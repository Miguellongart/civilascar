@extends('layouts.app')

@section('subtitle', $subtitle)
@section('content_header_title', $content_header_title)
@section('content_header_subtitle', $content_header_subtitle)

@section('content')
    <x-adminlte-card theme="lime" theme-mode="outline">
        @if (session('success'))
            <x-adminlte-callout theme="success" title="Success">
                {{ session('success') }}
            </x-adminlte-callout>
        @endif
        <x-adminlte-card>
            <div class="mb-3">
                <a href="{{ route('admin.gallery.create', $tournament->id) }}" class="btn btn-primary">Subir Fotos</a>
            </div>
            <div class="row">
                @foreach($galleries as $gallery)
                    <div class="col-6 col-md-4 col-lg-2 mb-4 position-relative">
                        <form action="{{ route('admin.gallery.destroy', $gallery->id) }}" method="POST" class="position-absolute" style="top: 5px; left: 5px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                        <a href="{{ asset('storage/' . $gallery->photo_path) }}" target="_blank" class="d-block">
                            <img src="{{ asset('storage/' . $gallery->photo_path) }}" alt="Foto" class="img-thumbnail" style="width: 100%; height: auto;">
                        </a>
                        <div class="text-center mt-2">
                            <small>Fecha: {{ \Carbon\Carbon::parse($gallery->match_date)->format('d/m/Y') }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-adminlte-card>
    </x-adminlte-card>
@stop

@push('css')
    <style>
        .img-thumbnail {
            object-fit: cover;
            height: 150px;
        }
        .btn-danger {
            z-index: 2;
        }
    </style>
@endpush

@push('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@endpush
