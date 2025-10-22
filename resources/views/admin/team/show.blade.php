@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', $subtitle)
@section('content_header_title', $content_header_title)
@section('content_header_subtitle', $content_header_subtitle)

@section('content_body')
    <x-adminlte-card theme="lime" theme-mode="outline">
        {{-- Estadísticas del equipo --}}
        <div class="row mb-4">
            <div class="col-lg-4 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-trophy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Torneos Participando</span>
                        <span class="info-box-number">{{ $stats['total_tournaments'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Jugadores Registrados</span>
                        <span class="info-box-number">{{ $stats['total_players'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-calendar-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total de Partidos</span>
                        <span class="info-box-number">{{ $stats['total_fixtures'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Selector de Torneo --}}
        @if($team->tournaments->isNotEmpty())
        <x-adminlte-card theme="primary" title="Seleccionar Torneo" icon="fas fa-filter" collapsible>
            <form action="{{ route('admin.team.show', $team->id) }}" method="GET" class="form-inline">
                <div class="form-group mr-3">
                    <label for="tournament_id" class="mr-2">Torneo:</label>
                    <select name="tournament_id" id="tournament_id" class="form-control" onchange="this.form.submit()">
                        @foreach($team->tournaments as $tournament)
                            <option value="{{ $tournament->id }}" {{ $idTournament == $tournament->id ? 'selected' : '' }}>
                                {{ $tournament->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sync"></i> Actualizar
                </button>
            </form>
        </x-adminlte-card>
        @else
        <x-adminlte-callout theme="warning" title="Sin Torneos">
            Este equipo no está asociado a ningún torneo todavía.
            <a href="{{ route('admin.tournament.index') }}" class="btn btn-sm btn-primary mt-2">
                <i class="fas fa-plus"></i> Agregar a un Torneo
            </a>
        </x-adminlte-callout>
        @endif

        <x-adminlte-card title="Información General" theme="success" icon="fas fa-info-circle">
            <div class="row">
                <div class="col-md-4">
                    <x-adminlte-profile-widget name="{{ $team->name }}" desc="{{ $team->description }}" theme="teal" img="{{ asset('front/images/liga cafetera.png') }}">
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

                    {{-- Botones de acción --}}
                    <div class="row mt-3">
                        <div class="col-12">
                            <a href="{{ route('admin.team.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver al Listado
                            </a>
                            <a href="{{ route('admin.team.edit', $team) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Editar Equipo
                            </a>
                            @if($idTournament)
                            <a href="{{ route('admin.fixture.index', $idTournament) }}" class="btn btn-info">
                                <i class="fas fa-calendar-alt"></i> Ver Partidos del Torneo
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </x-adminlte-card>

        @if($idTournament)
        <x-adminlte-card theme="info" theme-mode="outline" title="Jugadores del Equipo ({{ $players->count() }})">
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
                        {{-- {{ dd($player) }} --}}
                        <tr>
                            <td>{{ $player->user->name }}</td>
                            <td>{{ $player->user->dni }}</td>
                            <td>{{ $player->position }}</td>
                            <td>{{ $player->number }}</td>
                            <td><img src="{{ asset('storage/' . $player->player_photo_path) }}" alt="{{ $player->name }}" width="50"></td>
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
                                <!-- Botón para transferir (abre modal) -->
                                <x-adminlte-button label="Transferir jugador" data-toggle="modal" data-target="#transferModal{{ $player->id }}"/>
                                {{-- Modal de Transferencia --}}
                                <x-adminlte-modal id="transferModal{{ $player->id }}" title="Transferir Jugador" size="lg" theme="success"
                                    icon="fas fa-exchange-alt" v-centered static-backdrop scrollable>

                                    <p class="text-center">
                                        <strong>Jugador:</strong> {{ $player->name }} <br>
                                        <strong>Número:</strong> {{ $player->number }}
                                    </p>

                                    <form method="POST" action="{{ route('admin.team.transferPlayer') }}">
                                        @csrf
                                        <input type="hidden" name="player_id" value="{{ $player->id }}">
                                        <input type="hidden" name="from_team_id" value="{{ $team->id }}">
                                        <input type="hidden" name="from_tournament_id" value="{{ $idTournament }}">

                                        {{-- Equipo destino --}}
                                        <x-adminlte-select2 name="to_team_id" label="Equipo destino" label-class="text-success" data-placeholder="Selecciona un equipo..." igroup-size="lg" required>
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text bg-gradient-success">
                                                    <i class="fas fa-users"></i>
                                                </div>
                                            </x-slot>
                                            <option value="">Selecciona un equipo...</option>
                                            @foreach($allTeams as $destTeam)
                                                <option value="{{ $destTeam->id }}">{{ $destTeam->name }}</option>
                                            @endforeach
                                        </x-adminlte-select2>

                                        {{-- Torneo destino --}}
                                        <x-adminlte-select2 name="to_tournament_id" label="Torneo destino" label-class="text-success" data-placeholder="Selecciona un torneo..." igroup-size="lg" required>
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text bg-gradient-success">
                                                    <i class="fas fa-trophy"></i>
                                                </div>
                                            </x-slot>
                                            <option value="">Selecciona un torneo...</option>
                                            @foreach($allTournaments as $destTourney)
                                                <option value="{{ $destTourney->id }}">{{ $destTourney->name }}</option>
                                            @endforeach
                                        </x-adminlte-select2>

                                        {{-- Posición y número (opcional) --}}
                                        <x-adminlte-input name="new_position" label="Nueva posición (opcional)" placeholder="Ej: Defensa" fgroup-class="mt-3" label-class="text-success">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text bg-gradient-success">
                                                    <i class="fas fa-running"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>

                                        <x-adminlte-input name="new_number" label="Nuevo número (opcional)" placeholder="Ej: 10" type="number" fgroup-class="mt-2" label-class="text-success">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text bg-gradient-success">
                                                    <i class="fas fa-hashtag"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>

                                        {{-- Footer con botones --}}
                                        <x-slot name="footerSlot">
                                            <x-adminlte-button label="Cancelar" theme="secondary" data-dismiss="modal"/>
                                            <button type="submit" class="btn btn-success" onclick="console.log('Form submitted');">Transferir</button>
                                        </x-slot>
                                    </form>
                                </x-adminlte-modal>
                                {{-- Fin del modal de transferencia --}}
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-adminlte-card>
        @else
        <x-adminlte-callout theme="info" title="Información">
            Selecciona un torneo para ver los jugadores registrados en ese torneo.
        </x-adminlte-callout>
        @endif
    </x-adminlte-card>
@stop

@push('css')
@endpush

@push('js')
<script>
    $(document).ready(function() {
        $('#playersTable').DataTable();

        // Debugging form submission
        $('form').on('submit', function(e) {
            console.log('Submitting form:', $(this).attr('action'));
            
            // Verifica si el formulario tiene el atributo action correcto
            if (!$(this).attr('action')) {
                console.error('El formulario no tiene un atributo action definido.');
                e.preventDefault(); // Previene el envío si no hay acción
            }
        });
    });
</script>
@endpush
