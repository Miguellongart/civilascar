<x-guest-layout>
    <div class="container mt-4">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(!$currentTournament)
            <div class="alert alert-info text-center" role="alert">
                No hay torneos planificados disponibles en este momento.
            </div>
        @else
            {{-- Banner Horizontal Superior - Google AdSense --}}
            <x-adsense slot="auto" format="horizontal" style="min-height: 90px; margin-bottom: 1.5rem;" />

            <div class="filter-section shadow-sm">
                <h3 class="mb-3 text-info"><i class="bi bi-funnel-fill"></i> Filtrar Torneo y Fecha</h3>
                <form action="{{ route('front.home') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label for="tournament_id" class="form-label">Seleccionar Torneo:</label>
                        <select name="tournament_id" id="tournament_id" class="form-select">
                            @foreach($allTournaments as $tournament)
                                <option value="{{ $tournament->id }}" {{ $currentTournament->id == $tournament->id ? 'selected' : '' }}>
                                    {{ $tournament->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="filter_date" class="form-label">Seleccionar Fecha:</label>
                        <select name="filter_date" id="filter_date" class="form-select">
                            @forelse($dates as $date)
                                <option value="{{ $date }}" {{ $filterDate == $date ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($date)->locale('es')->isoFormat('dddd, D [de] MMMM [de]YYYY') }}
                                </option>
                            @empty
                                <option value="">No hay fechas disponibles</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Aplicar Filtro</button>
                    </div>
                </form>
            </div>

            <div class="row mb-5">
                <div class="col-md-8">
                </div>
                <div class="col-md-4">
                    <a href="{{route('front.inscription')}}" class="btn btn-primary">Registrar Jugador</a>
                </div>
            </div>

            <h2 class="section-title text-center">{{ $currentTournament->name }}</h2>
            <p class="text-center lead">Aquí puedes ver los partidos, la tabla de posiciones y las estadísticas de jugadores.</p>

            <div class="row mt-5">
                <div class="col-lg-9 main-content">
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <span class="mb-0 fw-bold">
                                        @if($filterDate)
                                            <i class="bi bi-calendar-event"></i> Partidos del {{ \Carbon\Carbon::parse($filterDate)->locale('es')->isoFormat('dddd, D [de] MMMM [de]YYYY') }}
                                        @else
                                            <i class="bi bi-calendar-event"></i> No hay fechas de partidos disponibles
                                        @endif
                                    </span>
                                </div>
                                <div class="card-body">
                                    @if($fixtures->isEmpty())
                                        <div class="alert alert-warning text-center" role="alert">
                                            @if($filterDate)
                                                <i class="bi bi-info-circle"></i> No hay partidos programados para esta fecha en el torneo actual.
                                            @else
                                                <i class="bi bi-info-circle"></i> No hay partidos registrados para este torneo.
                                            @endif
                                        </div>
                                    @else
                                        <div class="row">
                                            @foreach($fixtures as $fixture)
                                                <div class="col-md-6 col-lg-4 mb-3">
                                                    <div class="fixture-card">
                                                        <div class="fixture-header">
                                                            <small class="text-muted">
                                                                <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($fixture->match_date)->format('H:i') }}
                                                            </small>
                                                            <span class="badge {{ $fixture->status == 'completed' ? 'bg-success' : ($fixture->status == 'scheduled' ? 'bg-info' : 'bg-secondary') }}">
                                                                {{ $fixture->status == 'completed' ? 'Finalizado' : ($fixture->status == 'scheduled' ? 'Programado' : ucfirst($fixture->status)) }}
                                                            </span>
                                                        </div>
                                                        <div class="fixture-body text-center">
                                                            <div class="d-flex justify-content-center align-items-center mb-3">
                                                                <div class="text-center mx-2 flex-fill">
                                                                    @php
                                                                        $homeLogoPath = $fixture->homeTeam->logo ? 'storage/' . $fixture->homeTeam->logo : null;
                                                                        $homeLogoExists = $homeLogoPath && file_exists(public_path($homeLogoPath));
                                                                    @endphp
                                                                    <img src="{{ $homeLogoExists ? asset($homeLogoPath) : asset('front/images/liga cafetera.png') }}"
                                                                         alt="{{ $fixture->homeTeam->name ?? 'Local' }}"
                                                                         width="50"
                                                                         height="50"
                                                                         class="rounded-circle mb-2"
                                                                         style="object-fit: cover; border: 3px solid #667eea;">
                                                                    <p class="mb-0 team-name-small"><strong>{{ $fixture->homeTeam->name ?? 'Local' }}</strong></p>
                                                                </div>

                                                                @if($fixture->status == 'completed')
                                                                    <div class="score mx-2">{{ $fixture->home_team_score }} - {{ $fixture->away_team_score }}</div>
                                                                @else
                                                                    <span class="team-vs mx-2">VS</span>
                                                                @endif

                                                                <div class="text-center mx-2 flex-fill">
                                                                    @php
                                                                        $awayLogoPath = $fixture->awayTeam->logo ? 'storage/' . $fixture->awayTeam->logo : null;
                                                                        $awayLogoExists = $awayLogoPath && file_exists(public_path($awayLogoPath));
                                                                    @endphp
                                                                    <img src="{{ $awayLogoExists ? asset($awayLogoPath) : asset('front/images/liga cafetera.png') }}"
                                                                         alt="{{ $fixture->awayTeam->name ?? 'Visitante' }}"
                                                                         width="50"
                                                                         height="50"
                                                                         class="rounded-circle mb-2"
                                                                         style="object-fit: cover; border: 3px solid #667eea;">
                                                                    <p class="mb-0 team-name-small"><strong>{{ $fixture->awayTeam->name ?? 'Visitante' }}</strong></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <span class="mb-0"><i class="bi bi-trophy-fill"></i> Tabla de Posiciones</span>
                                </div>
                                <div class="card-body">
                                    @if($currentTournament->positionTables->isEmpty())
                                        <div class="alert alert-info text-center" role="alert">
                                            <i class="bi bi-info-circle"></i> La tabla de posiciones aún no está disponible para este torneo.
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th scope="col" class="text-center" style="width: 50px;">#</th>
                                                        <th scope="col">Equipo</th>
                                                        <th scope="col" class="text-center">PJ</th>
                                                        <th scope="col" class="text-center">PG</th>
                                                        <th scope="col" class="text-center">PE</th>
                                                        <th scope="col" class="text-center">PP</th>
                                                        <th scope="col" class="text-center">GF</th>
                                                        <th scope="col" class="text-center">GC</th>
                                                        <th scope="col" class="text-center">DG</th>
                                                        <th scope="col" class="text-center"><strong>Pts</strong></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($currentTournament->positionTables->sortByDesc('points')->values() as $index => $position)
                                                        <tr style="{{ $index < 3 ? 'background: linear-gradient(90deg, rgba(102, 126, 234, 0.05) 0%, rgba(255, 255, 255, 1) 100%);' : '' }}">
                                                            <td class="text-center">
                                                                @if($index === 0)
                                                                    <span style="font-size: 1.5rem;">🥇</span>
                                                                @elseif($index === 1)
                                                                    <span style="font-size: 1.5rem;">🥈</span>
                                                                @elseif($index === 2)
                                                                    <span style="font-size: 1.5rem;">🥉</span>
                                                                @else
                                                                    <strong>{{ $index + 1 }}</strong>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($position->team)
                                                                    @php
                                                                        $logoPath = $position->team->logo ? 'storage/' . $position->team->logo : null;
                                                                        $logoExists = $logoPath && file_exists(public_path($logoPath));
                                                                    @endphp

                                                                    <img src="{{ $logoExists ? asset($logoPath) : asset('front/images/liga cafetera.png') }}"
                                                                        alt="{{ $position->team->name }}"
                                                                        width="30"
                                                                        height="30"
                                                                        class="me-2 rounded-circle"
                                                                        style="object-fit: cover; border: 2px solid #667eea;">

                                                                    <a href="{{ route('front.team.show', ['teamId' => $position->team->id]) }}"
                                                                       class="text-decoration-none fw-bold"
                                                                       style="color: #2c3e50;">
                                                                        {{ $position->team->name }}
                                                                    </a>
                                                                @else
                                                                    Equipo Desconocido
                                                                @endif
                                                            </td>
                                                            <td class="text-center">{{ $position->played ?? 0 }}</td>
                                                            <td class="text-center text-success fw-bold">{{ $position->won ?? 0 }}</td>
                                                            <td class="text-center text-secondary">{{ $position->drawn ?? 0 }}</td>
                                                            <td class="text-center text-danger">{{ $position->lost ?? 0 }}</td>
                                                            <td class="text-center">{{ $position->goals_for ?? 0 }}</td>
                                                            <td class="text-center">{{ $position->goals_against ?? 0 }}</td>
                                                            <td class="text-center {{ ($position->goal_difference ?? 0) > 0 ? 'text-success' : (($position->goal_difference ?? 0) < 0 ? 'text-danger' : '') }}">
                                                                {{ ($position->goal_difference ?? 0) > 0 ? '+' : '' }}{{ $position->goal_difference ?? 0 }}
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge bg-primary" style="font-size: 1rem; min-width: 40px;">
                                                                    {{ $position->points ?? 0 }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Banner Horizontal Medio - Google AdSense --}}
                    <div class="mt-5 mb-5">
                        <x-adsense slot="auto" format="horizontal" style="min-height: 90px;" />
                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-4">
                            <div class="card shadow-sm">
                                <div class="card-header bg-danger text-white">
                                    <span class="mb-0"><i class="bi bi-fire"></i> Máximos Goleadores</span>
                                </div>
                                <div class="card-body">
                                    @if($topScorers->isEmpty())
                                        <div class="alert alert-info text-center" role="alert">
                                            Aún no hay goleadores registrados.
                                        </div>
                                    @else
                                        <ul class="list-group list-group-flush">
                                            @foreach($topScorers as $scorer)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $scorer->player->user->name ?? 'Jugador Desconocido' }}</strong>
                                                        <br><small class="text-muted">{{ $scorer->player->team->name ?? 'Equipo Desconocido' }}</small>
                                                    </div>
                                                    <span class="badge bg-danger rounded-pill fs-6">{{ $scorer->goals }} goles</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card shadow-sm">
                                <div class="card-header bg-warning text-dark">
                                    <span class="mb-0"><i class="bi bi-journal-x"></i> Más Tarjetas Amarillas</span>
                                </div>
                                <div class="card-body">
                                    @if($yellowCards->isEmpty())
                                        <div class="alert alert-info text-center" role="alert">
                                            Aún no hay tarjetas amarillas registradas.
                                        </div>
                                    @else
                                        <ul class="list-group list-group-flush">
                                            @foreach($yellowCards as $player)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $player->player->user->name ?? 'Jugador Desconocido' }}</strong>
                                                        <br><small class="text-muted">{{ $player->player->team->name ?? 'Equipo Desconocido' }}</small>
                                                    </div>
                                                    <span class="badge bg-warning text-dark rounded-pill fs-6">{{ $player->yellow_cards }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card shadow-sm">
                                <div class="card-header bg-dark text-white">
                                    <span class="mb-0"><i class="bi bi-journal-x-fill"></i> Más Tarjetas Rojas</span>
                                </div>
                                <div class="card-body">
                                    @if($redCards->isEmpty())
                                        <div class="alert alert-info text-center" role="alert">
                                            Aún no hay tarjetas rojas registradas.
                                        </div>
                                    @else
                                        <ul class="list-group list-group-flush">
                                            @foreach($redCards as $player)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $player->player->user->name ?? 'Jugador Desconocido' }}</strong>
                                                        <br><small class="text-muted">{{ $player->player->team->name ?? 'Equipo Desconocido' }}</small>
                                                    </div>
                                                    <span class="badge bg-dark rounded-pill fs-6">{{ $player->red_cards }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 sidebar-banners">
                    <span class="text-center mb-3 text-secondary">Publicidad</span>
                    <div class="banner-vertical mb-3">
                        <a href="https://www.instagram.com/ascar.oficial/" target="_blank" rel="noopener">
                            <img src="{{ asset('front/images/ascar.png') }}" alt="Ascar" class="img-fluid" style="width: 100%; max-width: 300px; height: auto; aspect-ratio: 6/5; object-fit: contain;">
                        </a>
                    </div>
                    <div class="banner-vertical mb-3">
                        <a href="https://www.instagram.com/calm.infinity/?igsh=MWdicXlyanNnamY1Mw%3D%3D#" target="_blank" rel="noopener">
                            <img src="{{ asset('front/images/callinfinyt.png') }}" alt="Callinfinyt" class="img-fluid" style="width: 100%; max-width: 300px; height: auto; aspect-ratio: 6/5; object-fit: contain;">
                        </a>
                    </div>
                    <div class="banner-vertical mb-3">
                        <a href="https://www.instagram.com/alambiquebar.arg?igsh=NzIzcnF3aWNhMDBo" target="_blank" rel="noopener">
                            <img src="{{ asset('front/images/alambique.png') }}" alt="Alambique" class="img-fluid" style="width: 100%; max-width: 300px; height: auto; aspect-ratio: 6/5; object-fit: contain;">
                        </a>
                    </div>
                    <div class="banner-vertical mb-3">
                        <a href="https://www.instagram.com/red9envios?igsh=MXhtb3RuNWx0cTh6Zg==" target="_blank" rel="noopener">
                            <img src="{{ asset('front/images/red9.png') }}" alt="Alambique" class="img-fluid" style="width: 100%; max-width: 300px; height: auto; aspect-ratio: 6/5; object-fit: contain;">
                        </a>
                    </div>
                </div>
            </div>

            {{-- Banner Horizontal Inferior - Google AdSense --}}
            <div class="mt-5">
                <x-adsense slot="auto" format="horizontal" style="min-height: 90px;" />
            </div>
        @endif
    </div>
</x-guest-layout>