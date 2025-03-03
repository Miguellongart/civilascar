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
                    <x-adminlte-profile-widget name="{{ $team->name }}" desc="{{ $team->description }}" theme="teal" img="{{ asset('storage/' . $team->logo) }}">
                        <x-adminlte-profile-col-item title="Entrenador" text="{{ $team->coach }}" url="#"/>
                        <x-adminlte-profile-col-item title="Estadio" text="{{ $team->home_stadium }}" url="#"/>
                    </x-adminlte-profile-widget>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <x-adminlte-input name="name" value="{{ $team->name }}" label="Nombre del Equipo" placeholder="Nombre del Equipo" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-users text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <x-adminlte-input name="coach" value="{{ $team->coach }}" label="Entrenador" placeholder="Entrenador" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-user text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-textarea name="description" label="Descripción" placeholder="Descripción del Equipo" fgroup-class="col-md-6" label-class="text-lightblue" disabled>{{ $team->description }}</x-adminlte-textarea>
                        <x-adminlte-input name="home_stadium" value="{{ $team->home_stadium }}" label="Estadio Local" placeholder="Estadio Local" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-building text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="user_id" value="{{ $team->user->name ?? 'N/A' }}" label="Usuario" placeholder="Usuario" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-user text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                </div>
            </div>
        </x-adminlte-card>
        
        <x-adminlte-card theme="info" theme-mode="outline" title="Jugadores del Equipo">
            <table id="playersTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Documento</th>
                        <th>Posición</th>
                        <th>Número</th>
                        <th>Foto</th>
                        <th>Acciones</th> <!-- Nueva columna para los botones -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($players as $player)
                        <tr>
                            <td>{{ $player->user->name }}</td>
                            <td>{{ $player->user->dni }}</td>
                            <td>{{ $player->position }}</td>
                            <td>{{ $player->number }}</td>
                            <td><img src="{{ asset('storage/' . $player->photo) }}" alt="{{ $player->name }}" width="50"></td>
                            <td>
                                <!-- Botón para actualizar -->
                                <a href="{{ route('admin.player.edit', $player->id) }}" class="btn btn-sm btn-primary mx-1 shadow" title="Actualizar">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </a>
                                
                                <!-- Botón para eliminar -->
                                <form method="POST" action="{{ route('admin.player.destroy', $player->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger mx-1 shadow" title="Eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este jugador?')">
                                        <i class="fa fa-lg fa-fw fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-adminlte-card>
    </x-adminlte-card>
@stop

@push('css')
@endpush

@push('js')
<script>
    $(document).ready(function() {
        $('#playersTable').DataTable();
    });
</script>
@endpush
