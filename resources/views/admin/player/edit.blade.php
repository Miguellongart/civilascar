@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', $subtitle)
@section('content_header_title', $content_header_title)
@section('content_header_subtitle', $content_header_subtitle)

@section('content_body')
    <x-adminlte-card theme="lime" theme-mode="outline">
        <x-adminlte-card>
            <div class="row">
                <div class="col-md-4">
                    <x-adminlte-profile-widget name="{{$player->name}}" desc="{{$player->name}}" theme="teal" img="https://picsum.photos/id/1/100">
                        <x-adminlte-profile-col-item title="DNI" text="{{$player->number}}" url="#"/>
                        <x-adminlte-profile-col-item title="Following" text="243" url="#"/>
                        <x-adminlte-profile-col-item title="Posts" text="37" url="#"/>
                    </x-adminlte-profile-widget>
                </div>
                <div class="col-md-8">
                    <form action="{{route('admin.player.update', $player->id)}}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <x-adminlte-input name="name" value="{{$player->user->name}}" label="Nombre" placeholder="Nombre" fgroup-class="col-md-6" label-class="text-lightblue">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-user text-lightblue"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="dni" value="{{$player->user->dni}}" label="DNI" placeholder="DNI" fgroup-class="col-md-6" label-class="text-lightblue">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-user text-lightblue"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="row">
                            <x-adminlte-input name="number" value="{{$player->number}}" label="Numero Camisa" placeholder="Numero Camisa" fgroup-class="col-md-6" label-class="text-lightblue">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-user text-lightblue"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="teamName" value="{{$player->team->name}}" label="Equipo" placeholder="Equipo" fgroup-class="col-md-6" label-class="text-lightblue" readonly>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-user text-lightblue"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="row">
                            <x-adminlte-select name="position" label="Posición" fgroup-class="col-md-6" label-class="text-lightblue">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-user text-lightblue"></i>
                                    </div>
                                </x-slot>
                                <option value="Arquero" {{ old('position', $player->position) == 'Arquero' ? 'selected' : '' }}>Arquero (ARQ)</option>
                                <option value="Defensa" {{ old('position', $player->position) == 'Defensa' ? 'selected' : '' }}>Defensa (DF)</option>
                                <option value="Medio Centro" {{ old('position', $player->position) == 'Medio Centro' ? 'selected' : '' }}>Medio Centro (MC)</option>
                                <option value="Delantero" {{ old('position', $player->position) == 'Delantero' ? 'selected' : '' }}>Delantero (D)</option>
                            </x-adminlte-select>
                            <x-adminlte-input name="user_id" value="{{$player->user_id}}" label="Numero Camisa" placeholder="Numero Camisa" fgroup-class="col-md-6" label-class="text-lightblue">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-user text-lightblue"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        
                        <x-adminlte-button class="btn-sm" type="submit" label="Editar" theme="outline-danger" icon="fas fa-pencil-alt"/>
                    </form>
                    
                </div>
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
