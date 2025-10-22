@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', $subtitle)
@section('content_header_title', $content_header_title)
@section('content_header_subtitle', $content_header_subtitle)

@section('content_body')
    <x-adminlte-card theme="lime" theme-mode="outline">
        {{-- Estadísticas del torneo --}}
        <div class="row mb-4">
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Equipos Participantes</span>
                        <span class="info-box-number">{{ $stats['total_teams'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-calendar-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Partidos Jugados</span>
                        <span class="info-box-number">{{ $stats['completed_fixtures'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-calendar-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Partidos Programados</span>
                        <span class="info-box-number">{{ $stats['scheduled_fixtures'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="fas fa-futbol"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Goles Anotados</span>
                        <span class="info-box-number">{{ $stats['total_goals'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <x-adminlte-card title="Información General" theme="primary" icon="fas fa-info-circle">
            <div class="row">
                <x-adminlte-input name="name" value="{{$tournament->name}}" label="Nombre del Torneo" placeholder="Nombre del Torneo" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-trophy text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                <x-adminlte-input name="type" value="{{$tournament->type}}" label="Tipo de Torneo" placeholder="Tipo de Torneo" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-list text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </div>
            <div class="row">
                <x-adminlte-input name="location" value="{{$tournament->location}}" label="Ubicación" placeholder="Ubicación" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-map-marker-alt text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                <x-adminlte-input name="status" value="{{$tournament->status}}" label="Estado del Torneo" placeholder="Estado del Torneo" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-info-circle text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </div>
            <div class="row">
                <x-adminlte-textarea name="description" label="Descripción" placeholder="Descripción del torneo" fgroup-class="col-md-12" label-class="text-lightblue" disabled>{{ $tournament->description }}</x-adminlte-textarea>
            </div>
            <div class="row">
                <x-adminlte-input name="start_date" value="{{$tournament->start_date}}" label="Fecha de Inicio" placeholder="Fecha de Inicio" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-calendar text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                <x-adminlte-input name="end_date" value="{{$tournament->end_date}}" label="Fecha de Fin" placeholder="Fecha de Fin" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-calendar text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </div>
            <div class="row">
                <x-adminlte-input name="rules" value="{{$tournament->rules}}" label="Reglas" placeholder="Reglas del torneo" fgroup-class="col-md-12" label-class="text-lightblue" disabled>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-gavel text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </div>
            <div class="row">
                <x-adminlte-input name="prizes" value="{{$tournament->prizes}}" label="Premios" placeholder="Premios del torneo" fgroup-class="col-md-12" label-class="text-lightblue" disabled>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-gift text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </div>
            <div class="row">
                <x-adminlte-input name="limit_teams" value="{{$tournament->limit_teams}}" label="Límite de Equipos" placeholder="Límite de Equipos" fgroup-class="col-md-12" label-class="text-lightblue" disabled>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-users text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </div>

            {{-- Botones de acción --}}
            <div class="row mt-3">
                <div class="col-12">
                    <a href="{{ route('admin.tournament.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver al Listado
                    </a>
                    <a href="{{ route('admin.tournament.edit', $tournament) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Editar Torneo
                    </a>
                    <a href="{{ route('admin.tournament.addTeams', $tournament) }}" class="btn btn-success">
                        <i class="fas fa-users"></i> Gestionar Equipos
                    </a>
                    <a href="{{ route('admin.fixture.index', $tournament) }}" class="btn btn-info">
                        <i class="fas fa-calendar-alt"></i> Ver Partidos
                    </a>
                    <a href="{{ route('admin.gallery.index', $tournament) }}" class="btn btn-warning">
                        <i class="fas fa-photo-video"></i> Ver Galería
                    </a>
                </div>
            </div>
        </x-adminlte-card>

        {{-- Lista de equipos --}}
        @if($tournament->teams->count() > 0)
        <x-adminlte-card title="Equipos Participantes ({{ $tournament->teams->count() }})" theme="success" icon="fas fa-users" collapsible>
            <div class="row">
                @foreach($tournament->teams as $team)
                    <div class="col-md-4 col-sm-6">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-success">
                                @if($team->logo)
                                    <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}" class="img-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <i class="fas fa-shield-alt"></i>
                                @endif
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ $team->name }}</span>
                                <span class="info-box-number text-sm">
                                    <a href="{{ route('admin.team.show', $team) }}" class="btn btn-xs btn-info">
                                        Ver Detalles
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-adminlte-card>
        @endif
    </x-adminlte-card>
@stop

{{-- Push extra CSS --}}
@push('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}
@push('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@endpush
