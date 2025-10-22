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

        {{-- Estadísticas del torneo --}}
        <div class="row mb-4">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total_fixtures'] }}</h3>
                        <p>Total Partidos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['completed'] }}</h3>
                        <p>Partidos Jugados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['scheduled'] }}</h3>
                        <p>Partidos Programados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['total_goals'] }}</h3>
                        <p>Goles Anotados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-futbol"></i>
                    </div>
                </div>
            </div>
        </div>

        <x-adminlte-card>
            {{-- Botones de acción --}}
            <div class="mb-3">
                <a href="{{ route('admin.tournament.show', $tournament->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Torneo
                </a>
                <a href="{{ route('admin.fixture.createFixture', $tournament->id) }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Generar Fixtures
                </a>
                <a href="{{ route('admin.tournament.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-trophy"></i> Todos los Torneos
                </a>
            </div>

            {{-- Selector de modo de visualización y filtros --}}
            <x-adminlte-card theme="primary" title="Filtros y Visualización" icon="fas fa-filter" collapsible>
                @if($currentRound && $viewMode == 'round')
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle"></i> <strong>Jornada Actual:</strong> Ronda {{ $currentRound }}
                        @if($selectedRound && $selectedRound != $currentRound)
                            <a href="{{ route('admin.fixture.index', ['tournament' => $tournament->id, 'view_mode' => 'round']) }}" class="btn btn-sm btn-outline-primary ml-2">
                                <i class="fas fa-sync"></i> Ver Jornada Actual
                            </a>
                        @endif
                    </div>
                @endif

                <form action="{{ route('admin.fixture.index', $tournament->id) }}" method="GET" class="row">
                    {{-- Modo de visualización --}}
                    <div class="col-md-3 mb-3">
                        <label for="view_mode">Agrupar por:</label>
                        <select class="form-control" id="view_mode" name="view_mode" onchange="toggleFilters()">
                            <option value="round" {{ $viewMode == 'round' ? 'selected' : '' }}>🏆 Por Ronda</option>
                            <option value="date" {{ $viewMode == 'date' ? 'selected' : '' }}>📅 Por Fecha</option>
                        </select>
                    </div>

                    {{-- Filtro por ronda --}}
                    <div class="col-md-4 mb-3" id="round-filter" style="{{ $viewMode == 'round' ? '' : 'display:none;' }}">
                        <label for="round">Filtrar por Ronda:</label>
                        <select class="form-control" id="round" name="round">
                            <option value="">Todas las rondas</option>
                            @foreach($rounds as $round)
                                <option value="{{ $round }}" {{ $selectedRound == $round ? 'selected' : '' }}>
                                    Ronda {{ $round }} {{ $round == $currentRound ? '⭐ (Actual)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtro por fecha --}}
                    <div class="col-md-3 mb-3" id="date-filter" style="{{ $viewMode == 'date' ? '' : 'display:none;' }}">
                        <label for="date">Filtrar por Fecha:</label>
                        <select class="form-control" id="date" name="date">
                            <option value="">Todas las fechas</option>
                            @foreach($dates as $date)
                                <option value="{{ $date }}" {{ $selectedDate == $date ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($date)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        <a href="{{ route('admin.fixture.index', $tournament->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    </div>
                </form>

                {{-- Acceso rápido por rondas (solo visible en modo round) --}}
                @if($viewMode == 'round' && $rounds->isNotEmpty())
                    <hr>
                    <div class="mb-2">
                        <label class="d-block mb-2"><i class="fas fa-bolt"></i> Acceso Rápido por Ronda:</label>
                        <div class="btn-group flex-wrap" role="group">
                            @foreach($rounds as $round)
                                <a href="{{ route('admin.fixture.index', $tournament->id) }}?view_mode=round&round={{ $round }}"
                                   class="btn btn-sm {{ $selectedRound == $round ? 'btn-primary' : 'btn-outline-primary' }}">
                                    @if($round == $currentRound)
                                        <i class="fas fa-star"></i>
                                    @endif
                                    Ronda {{ $round }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </x-adminlte-card>

            {{-- Mostrar fixtures agrupados --}}
            @if($groupedFixtures->isEmpty())
                <x-adminlte-callout theme="warning" title="Sin Partidos">
                    No hay partidos programados para este torneo todavía.
                </x-adminlte-callout>
            @else
                @foreach($groupedFixtures as $groupKey => $fixturesInGroup)
                    <x-adminlte-card
                        theme="success"
                        :title="$viewMode == 'round' ? 'Ronda ' . $groupKey : \Carbon\Carbon::parse($groupKey)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY')"
                        icon="fas fa-calendar-day"
                        collapsible>

                        <div class="row">
                            @foreach($fixturesInGroup as $fixture)
                                <div class="col-md-6 mb-3">
                                    <div class="card fixture-card" style="border-left: 4px solid {{ $fixture->status == 'completed' ? '#28a745' : ($fixture->status == 'scheduled' ? '#ffc107' : '#6c757d') }};">
                                        <div class="card-header" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="fas fa-clock"></i>
                                                    {{ \Carbon\Carbon::parse($fixture->match_date)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::parse($fixture->match_date)->locale('es')->isoFormat('D MMM') }}
                                                </small>
                                                <span class="badge {{ $fixture->status == 'completed' ? 'badge-success' : ($fixture->status == 'scheduled' ? 'badge-warning' : 'badge-secondary') }}">
                                                    {{ $fixture->status == 'completed' ? 'Finalizado' : ($fixture->status == 'scheduled' ? 'Programado' : ucfirst($fixture->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                {{-- Equipo Local --}}
                                                <div class="col-5 text-center">
                                                    @if($fixture->homeTeam->logo)
                                                        <img src="{{ asset('storage/' . $fixture->homeTeam->logo) }}"
                                                             alt="{{ $fixture->homeTeam->name }}"
                                                             class="img-circle mb-2"
                                                             style="width: 50px; height: 50px; object-fit: cover; border: 3px solid #667eea;">
                                                    @else
                                                        <i class="fas fa-shield-alt fa-3x text-primary mb-2"></i>
                                                    @endif
                                                    <p class="mb-0"><strong>{{ $fixture->homeTeam->name }}</strong></p>
                                                </div>

                                                {{-- Marcador --}}
                                                <div class="col-2 text-center">
                                                    @if($fixture->status == 'completed')
                                                        <h3 class="mb-0" style="font-weight: 800; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                                            {{ $fixture->home_team_score }} - {{ $fixture->away_team_score }}
                                                        </h3>
                                                    @else
                                                        <h4 class="mb-0 text-muted">VS</h4>
                                                    @endif
                                                </div>

                                                {{-- Equipo Visitante --}}
                                                <div class="col-5 text-center">
                                                    @if($fixture->awayTeam->logo)
                                                        <img src="{{ asset('storage/' . $fixture->awayTeam->logo) }}"
                                                             alt="{{ $fixture->awayTeam->name }}"
                                                             class="img-circle mb-2"
                                                             style="width: 50px; height: 50px; object-fit: cover; border: 3px solid #667eea;">
                                                    @else
                                                        <i class="fas fa-shield-alt fa-3x text-info mb-2"></i>
                                                    @endif
                                                    <p class="mb-0"><strong>{{ $fixture->awayTeam->name }}</strong></p>
                                                </div>
                                            </div>

                                            {{-- Eventos del partido (goles, tarjetas) --}}
                                            @if($fixture->status == 'completed' && $fixture->playerEvents->isNotEmpty())
                                                <hr>
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        @php
                                                            $goals = $fixture->playerEvents->where('event_type', 'goal');
                                                            $yellowCards = $fixture->playerEvents->where('event_type', 'yellow_card')->count();
                                                            $redCards = $fixture->playerEvents->where('event_type', 'red_card')->count();
                                                        @endphp
                                                        @if($goals->count() > 0)
                                                            <i class="fas fa-futbol text-success"></i> {{ $goals->count() }} gol(es)
                                                        @endif
                                                        @if($yellowCards > 0)
                                                            <i class="fas fa-square text-warning ml-2"></i> {{ $yellowCards }}
                                                        @endif
                                                        @if($redCards > 0)
                                                            <i class="fas fa-square text-danger ml-2"></i> {{ $redCards }}
                                                        @endif
                                                    </small>
                                                </div>
                                            @endif

                                            {{-- Botones de acción --}}
                                            <div class="mt-3 text-center">
                                                @can('admin.fixture.show')
                                                    <a href="{{ route('admin.fixture.show', $fixture->id) }}" class="btn btn-sm btn-info" title="Ver Detalles">
                                                        <i class="fas fa-eye"></i> Ver
                                                    </a>
                                                @endcan
                                                @can('admin.fixture.edit')
                                                    <a href="{{ route('admin.fixture.edit', $fixture->id) }}" class="btn btn-sm btn-primary" title="Editar">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-adminlte-card>
                @endforeach
            @endif
        </x-adminlte-card>
    </x-adminlte-card>
@stop

@push('css')
<style>
    .fixture-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 12px;
        overflow: hidden;
    }

    .fixture-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0,0,0,0.1);
    }

    /* Botones de acceso rápido */
    .btn-group.flex-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .btn-group.flex-wrap .btn {
        margin: 0 !important;
        border-radius: 8px !important;
        transition: all 0.3s ease;
    }

    .btn-group.flex-wrap .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    /* Destacar jornada actual */
    .alert-info {
        border-left: 4px solid #17a2b8;
        background: linear-gradient(135deg, rgba(23, 162, 184, 0.1) 0%, rgba(23, 162, 184, 0.05) 100%);
    }
</style>
@endpush

@push('js')
<script>
    function toggleFilters() {
        const viewMode = document.getElementById('view_mode').value;
        const roundFilter = document.getElementById('round-filter');
        const dateFilter = document.getElementById('date-filter');

        if (viewMode === 'round') {
            roundFilter.style.display = 'block';
            dateFilter.style.display = 'none';
        } else {
            roundFilter.style.display = 'none';
            dateFilter.style.display = 'block';
        }
    }
</script>
@endpush
