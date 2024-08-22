@extends('layouts.app')

@section('subtitle', $subtitle)
@section('content_header_title', $content_header_title)
@section('content_header_subtitle', $content_header_subtitle)

@section('content')
    <x-adminlte-card theme="lime" theme-mode="outline">
        <form action="{{ route('admin.gallery.store', $tournament->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @php
                // Obtener la fecha actual en el formato deseado
                $currentDate = \Carbon\Carbon::now()->format('Y-m-d');
                $config = ['format' => 'YYYY-MM-DD'];
            @endphp

            {{-- Componente con la fecha actual como valor predeterminado --}}
            <x-adminlte-input-date name="match_date" value="{{ $currentDate }}" :config="$config"/>
            
            {{-- Simple file input for testing --}}
            <x-adminlte-input-file id="photos" name="photos[]" label="Upload files"
                placeholder="Choose multiple files..." multiple>
            </x-adminlte-input-file>

            <x-adminlte-input name="tournamentId" value="{{$tournament->id}}" label="" placeholder="Numero Camisa" fgroup-class="col-md-6" hidden label-class="text-lightblue">
            </x-adminlte-input>

            <x-adminlte-button class="btn-sm" type="submit" label="Crear Equipo" theme="outline-success" icon="fas fa-plus"/>
        </form>
    </x-adminlte-card>
@stop

@push('css')
@endpush

@push('js')
@endpush
