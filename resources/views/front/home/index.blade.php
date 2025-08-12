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
            <div class="banner-horizontal mb-4">
                <p>Espacio para Banner Horizontal Superior (970x90px o similar)</p>
            </div>

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
                        <div class="col-lg-4">
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
                                                No hay partidos programados para esta fecha en el torneo actual.
                                            @else
                                                No hay partidos registrados para este torneo.
                                            @endif
                                        </div>
                                    @else
                                        <div class="list-group">
                                            @foreach($fixtures as $fixture)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div class="text-center flex-grow-1"> {{-- Added text-center and flex-grow-1 here --}}
                                                        <h6 class="mb-0"> {{-- Changed h5 to h6 for smaller font --}}
                                                            <strong>{{ $fixture->homeTeam->name ?? 'Equipo Local' }}</strong> vs <strong>{{ $fixture->awayTeam->name ?? 'Equipo Visitante' }}</strong>
                                                        </h6>
                                                        <small class="text-muted d-block mb-1"> {{-- Added d-block and mb-1 for new line and spacing --}}
                                                            <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($fixture->match_date)->format('H:i') }}
                                                        </small>
                                                        <span class="badge {{ $fixture->status == 'completed' ? 'bg-success' : ($fixture->status == 'scheduled' ? 'bg-info' : 'bg-secondary') }}">
                                                            {{ ucfirst($fixture->status) }}
                                                        </span>
                                                    </div>
                                                    @if($fixture->status == 'completed')
                                                        <span class="badge bg-dark fs-5 ms-3">{{ $fixture->home_team_score }} - {{ $fixture->away_team_score }}</span> {{-- Added ms-3 for margin --}}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <span class="mb-0"><i class="bi bi-trophy-fill"></i> Tabla de Posiciones</span>
                                </div>
                                <div class="card-body">
                                    @if($currentTournament->positionTables->isEmpty())
                                        <div class="alert alert-info text-center" role="alert">
                                            La tabla de posiciones aún no está disponible para este torneo.
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-sm">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Equipo</th>
                                                        <th scope="col">PJ</th>
                                                        <th scope="col">PG</th>
                                                        <th scope="col">PE</th>
                                                        <th scope="col">PP</th>
                                                        <th scope="col">GF</th>
                                                        <th scope="col">GC</th>
                                                        <th scope="col">DG</th>
                                                        <th scope="col">Pts</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($currentTournament->positionTables->sortByDesc('points')->values() as $index => $position)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>
                                                                @if($position->team)
                                                                    @php
                                                                        // Define la ruta a la imagen del logo del equipo
                                                                        $logoPath = $position->team->logo ? 'storage/' . $position->team->logo : null;
                                                                        // Verifica si la imagen existe en la ruta pública
                                                                        $logoExists = $logoPath && file_exists(public_path($logoPath));
                                                                    @endphp
                                                                    
                                                                    <img src="{{ $logoExists ? asset($logoPath) : asset('front/images/liga cafetera.png') }}" 
                                                                        alt="{{ $position->team->name }}" width="20" height="20" class="me-2 rounded-circle">
                                                                    
                                                                    <a href="{{ route('front.team.show', ['teamId' => $position->team->id]) }}" class="text-decoration-none text-dark">
                                                                        {{ $position->team->name }}
                                                                    </a>
                                                                @else
                                                                    Equipo Desconocido
                                                                @endif
                                                            </td>
                                                            <td>{{ $position->played ?? 0 }}</td>
                                                            <td>{{ $position->won ?? 0 }}</td>
                                                            <td>{{ $position->drawn ?? 0 }}</td>
                                                            <td>{{ $position->lost ?? 0 }}</td>
                                                            <td>{{ $position->goals_for ?? 0 }}</td>
                                                            <td>{{ $position->goals_against ?? 0 }}</td>
                                                            <td>{{ $position->goal_difference ?? 0 }}</td>
                                                            <td><strong>{{ $position->points ?? 0 }}</strong></td>
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

                    <div class="banner-horizontal mt-5 mb-5">
                        <p>Espacio para Banner Horizontal Medio (728x90px o similar)</p>
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
                </div>
            </div>

            <div class="banner-horizontal mt-5">
                <p>Espacio para Banner Horizontal Inferior (970x90px o similar)</p>
            </div>
        @endif
    </div>
</x-guest-layout>