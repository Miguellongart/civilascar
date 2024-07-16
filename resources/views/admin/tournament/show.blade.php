@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', $subtitle)
@section('content_header_title', $content_header_title)
@section('content_header_subtitle', $content_header_subtitle)

@section('content_body')
    <x-adminlte-card theme="lime" theme-mode="outline">
        <x-adminlte-card>
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
