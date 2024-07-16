@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', 'Crear Torneo')
@section('content_header_title', 'Nuevo Torneo')
@section('content_header_subtitle', 'Rellene los detalles del nuevo torneo')

@section('content_body')
<x-adminlte-card theme="lime" theme-mode="outline">
    <x-adminlte-card>
        <form action="{{ route('admin.tournament.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <x-adminlte-input name="name" label="Nombre del Torneo" placeholder="Nombre del Torneo" fgroup-class="col-md-6" label-class="text-lightblue" required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-trophy text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                <x-adminlte-select name="type" label="Tipo de Torneo" fgroup-class="col-md-6" label-class="text-lightblue" required>
                    <option value="">Seleccione el tipo de torneo</option>
                    <option value="league">Liga</option>
                    <option value="cup">Copa</option>
                    <option value="quadrangular">Cuadrangular</option>
                </x-adminlte-select>
            </div>
            <div class="row">
                <x-adminlte-input name="max_teams" type="number" label="Número Máximo de Equipos" placeholder="Número Máximo de Equipos" fgroup-class="col-md-6" label-class="text-lightblue" required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-users text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                <x-adminlte-input name="min_teams" type="number" label="Número Mínimo de Equipos" placeholder="Número Mínimo de Equipos" fgroup-class="col-md-6" label-class="text-lightblue" required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-users text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </div>
            <div class="row">
                <x-adminlte-textarea name="description" label="Descripción" placeholder="Descripción del torneo" fgroup-class="col-md-12" label-class="text-lightblue" required/>
            </div>
            <div class="row">
                <x-adminlte-input name="start_date" type="date" label="Fecha de Inicio" placeholder="Fecha de Inicio" fgroup-class="col-md-6" label-class="text-lightblue" required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-calendar text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                <x-adminlte-input name="end_date" type="date" label="Fecha de Fin" placeholder="Fecha de Fin" fgroup-class="col-md-6" label-class="text-lightblue" required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-calendar text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </div>
            <div class="row">
                <x-adminlte-input name="location" label="Ubicación" placeholder="Ubicación" fgroup-class="col-md-6" label-class="text-lightblue" required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-map-marker-alt text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                <x-adminlte-select name="status" label="Estado" fgroup-class="col-md-6" label-class="text-lightblue" required>
                    <option value="planned">Planificado</option>
                    <option value="in_progress">En Progreso</option>
                    <option value="finished">Finalizado</option>
                </x-adminlte-select>
            </div>
            <div class="row">
                <x-adminlte-textarea name="rules" label="Reglas" placeholder="Reglas del torneo" fgroup-class="col-md-12" label-class="text-lightblue" required/>
            </div>
            <div class="row">
                <x-adminlte-input name="prizes" label="Premios" placeholder="Premios del torneo" fgroup-class="col-md-12" label-class="text-lightblue" required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-gift text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </div>
            <div class="row">
                <x-adminlte-input name="limit_teams" label="Límite de Equipos" placeholder="Límite de Equipos" fgroup-class="col-md-6" label-class="text-lightblue" required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-users text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                <x-adminlte-input-file name="logo" label="Logotipo del Torneo" fgroup-class="col-md-6" placeholder="Seleccionar archivo..." label-class="text-lightblue"/>
            </div>
            <div class="form-group">
                <x-adminlte-button class="btn-sm" type="submit" label="Crear Torneo" theme="outline-success" icon="fas fa-plus"/>
            </div>
        </form>
    </x-adminlte-card>
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
