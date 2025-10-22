@extends('layouts.app')

@section('subtitle', $subtitle)
@section('content_header_title', $content_header_title)
@section('content_header_subtitle', $content_header_subtitle)

@section('content')
    <x-adminlte-card theme="lime" theme-mode="outline">
        @if (session('success'))
            <x-adminlte-callout theme="success" title="Éxito">
                {{ session('success') }}
            </x-adminlte-callout>
        @endif

        {{-- Estadísticas de torneos --}}
        <div class="row mb-3">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $statusCounts['all'] }}</h3>
                        <p>Total Torneos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <a href="{{ route('admin.tournament.index') }}" class="small-box-footer">
                        Ver todos <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $statusCounts['planned'] }}</h3>
                        <p>Planificados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <a href="{{ route('admin.tournament.index', ['status' => 'planned']) }}" class="small-box-footer">
                        Ver planificados <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $statusCounts['in_progress'] }}</h3>
                        <p>En Progreso</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <a href="{{ route('admin.tournament.index', ['status' => 'in_progress']) }}" class="small-box-footer">
                        Ver en progreso <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{ $statusCounts['finished'] }}</h3>
                        <p>Finalizados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-flag-checkered"></i>
                    </div>
                    <a href="{{ route('admin.tournament.index', ['status' => 'finished']) }}" class="small-box-footer">
                        Ver finalizados <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <x-adminlte-card>
            <div class="mb-3">
                <a href="{{ route('admin.tournament.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Crear Nuevo Torneo
                </a>
                @if(request('status'))
                    <a href="{{ route('admin.tournament.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Limpiar Filtro
                    </a>
                    <span class="badge badge-info ml-2">
                        Filtrado por: {{ ucfirst(request('status')) }}
                    </span>
                @endif
            </div>
            <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" striped hoverable bordered compressed beautify>
                @foreach($config['data'] as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td>{!! $cell !!}</td>
                        @endforeach
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </x-adminlte-card>
    </x-adminlte-card>
@stop

@push('css')
@endpush

@push('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@endpush
