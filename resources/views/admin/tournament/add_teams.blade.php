@extends('layouts.app')

@section('subtitle', 'Añadir Equipos')
@section('content_header_title', 'Añadir Equipos al Torneo')
@section('content_header_subtitle', $tournament->name)

@section('content')
    <x-adminlte-card theme="lime" theme-mode="outline">
        <form action="{{ route('admin.tournament.storeTeams', $tournament) }}" method="post">
            @csrf
            <div class="form-group">
                <label for="teams">Seleccionar Equipos:</label>
                <select name="teams[]" id="teams" class="form-control" multiple>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>
            <x-adminlte-button class="btn-sm" type="submit" label="Añadir Equipos" theme="outline-success" icon="fas fa-plus"/>
        </form>
    </x-adminlte-card>
@stop

@push('css')
@endpush

@push('js')
@endpush
