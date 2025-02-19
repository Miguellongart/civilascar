@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', $subtitle)
@section('content_header_title', $content_header_title)
@section('content_header_subtitle', $content_header_subtitle)

@section('content_body')
    <x-adminlte-card theme="lime" theme-mode="outline">
        <x-adminlte-card>
            <a href="{{ route('admin.tournament.index') }}" class="btn btn-sm btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Volver a Torneos
            </a>
            <x-adminlte-datatable id="table2" :heads="['ID', 'Nombre del Equipo', 'Entrenador', 'Estadio Local', 'Acciones']">
                @foreach($teams as $team)
                    <tr>
                        <td>{{ $team->id }}</td>
                        <td>{{ $team->name }}</td>
                        <td>{{ $team->coach }}</td>
                        <td>{{ $team->home_stadium }}</td>
                        <td>
                            @canany('admin.team.show')
                                <a href="{{ route('admin.team.show', ['idTeam' => $team->id, 'idTournament' => $tournament->id]) }}" class="btn btn-xs btn-default text-teal mx-1 shadow" title="Ver Detalles">
                                    <i class="fa fa-lg fa-fw fa-eye"></i>
                                </a>
                            @endcanany
                            @canany('admin.team.edit')
                                <a href="{{ route('admin.team.edit', $team->id) }}" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </a>
                            @endcanany
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </x-adminlte-card>
    </x-adminlte-card>
@stop

{{-- Push extra CSS --}}
@push('css')
@endpush

{{-- Push extra scripts --}}
@push('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@endpush
