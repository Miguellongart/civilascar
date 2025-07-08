<x-guest-layout>
    <div class="container mt-4">
        @if(!$team)
            <div class="alert alert-danger text-center" role="alert">
                Equipo no encontrado.
            </div>
        @else
            <div class="team-header shadow-sm">
                @if($team->logo)
                    <img src="{{ asset('front/images/liga cafetera.png') }}" alt="{{ $team->name }}" class="team-logo mb-3">
                @else
                    {{-- Usa una imagen por defecto genérica o el logo de la liga --}}
                    <img src="{{ asset('front/images/liga cafetera.png') }}" alt="Logo por defecto" class="team-logo mb-3">
                @endif
                <h1 class="display-4 mb-1 text-primary">{{ $team->name }}</h1>
                <p class="lead text-muted">{{ $team->description ?? 'Sin descripción.' }}</p>

                @if($currentTournament)
                    <p class="mt-3">
                        <span class="badge bg-info text-dark fs-6"><i class="bi bi-trophy-fill"></i> Torneo Actual: {{ $currentTournament->name }}</span>
                    </p>
                @endif
            </div>

            <div class="banner-horizontal mb-4">
                <p>Espacio para Banner Horizontal Superior (970x90px o similar)</p>
            </div>

            <h2 class="text-center mb-4 text-secondary">Historial del Equipo por Torneo</h2>

            <div class="row">
                <div class="col-lg-9 main-content">
                    <div class="accordion" id="tournamentAccordion">
                        @forelse($team->tournaments as $key => $tournament)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $tournament->id }}">
                                    <button class="accordion-button {{ $key == 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $tournament->id }}" aria-expanded="{{ $key == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $tournament->id }}">
                                        <i class="bi bi-trophy-fill me-2"></i> {{ $tournament->name }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $tournament->id }}" class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $tournament->id }}" data-bs-parent="#tournamentAccordion">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <h5 class="mt-2 mb-3 text-primary"><i class="bi bi-person-fill"></i> Plantilla para este Torneo</h5>
                                                @php
                                                    $playersForThisTournament = $playersByTournament[$tournament->name] ?? collect();
                                                @endphp
                                                @if($playersForThisTournament->isEmpty())
                                                    <div class="alert alert-info text-center" role="alert">
                                                        No hay jugadores registrados para este torneo.
                                                    </div>
                                                @else
                                                    <ul class="list-group list-group-flush mb-4">
                                                        @foreach($playersForThisTournament as $player)
                                                            <li class="list-group-item d-flex align-items-center">
                                                                @if($player->user->profile_photo_path)
                                                                    <img src="{{ asset('front/images/liga cafetera.png') }}" alt="{{ $player->user->name }}" class="player-img">
                                                                @else
                                                                    <img src="{{ asset('front/images/liga cafetera.png') }}" alt="Jugador" class="player-img">
                                                                @endif
                                                                <div>
                                                                    <strong>{{ $player->user->name ?? 'Jugador Desconocido' }}</strong>
                                                                    <small class="d-block text-muted">#{{ $player->number ?? 'N/A' }} - {{ $player->position ?? 'Sin posición' }}</small>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                            <div class="col-6">
                                                <h5 class="mt-2 mb-3 text-danger"><i class="bi bi-fire"></i> Goleadores del Equipo en este Torneo</h5>
                                                @php
                                                    $scorersForThisTournament = $topScorersByTournament[$tournament->name] ?? collect();
                                                @endphp
                                                @if($scorersForThisTournament->isEmpty())
                                                    <div class="alert alert-info text-center" role="alert">
                                                        No hay goleadores registrados en este torneo para el equipo.
                                                    </div>
                                                @else
                                                    <ul class="list-group list-group-flush mb-4">
                                                        @foreach($scorersForThisTournament as $scorer)
                                                            <li class="list-group-item scorer-item">
                                                                <div class="player-info">
                                                                    @if($scorer->player->user->profile_photo_path)
                                                                        <img src="{{ asset('front/images/liga cafetera.png') }}" alt="{{ $scorer->player->user->name }}" class="player-img">
                                                                    @else
                                                                        <img src="{{ asset('front/images/liga cafetera.png') }}" alt="Goleador" class="player-img">
                                                                    @endif
                                                                    <strong>{{ $scorer->player->user->name ?? 'Jugador Desconocido' }}</strong>
                                                                </div>
                                                                <span class="badge bg-danger rounded-pill goals-badge">{{ $scorer->goals }} goles</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <h5 class="mt-2 mb-3 text-info"><i class="bi bi-calendar-event"></i> Partidos Jugados en este Torneo</h5>
                                        @php
                                            $fixturesForThisTournament = $fixturesByTournament[$tournament->name] ?? collect();
                                        @endphp
                                        @if($fixturesForThisTournament->isEmpty())
                                            <div class="alert alert-info text-center" role="alert">
                                                No hay partidos registrados para este torneo.
                                            </div>
                                        @else
                                            <div class="row">
                                                @foreach($fixturesForThisTournament as $fixture)
                                                    <div class="col-md-6 col-lg-4 mb-3">
                                                        <div class="card fixture-card">
                                                            <div class="fixture-header">
                                                                <small class="text-muted">{{ \Carbon\Carbon::parse($fixture->match_date)->locale('es')->isoFormat('D MMM YYYY') }}</small>
                                                                <span class="badge {{ $fixture->status == 'completed' ? 'bg-success' : ($fixture->status == 'scheduled' ? 'bg-primary' : 'bg-secondary') }}">
                                                                    {{ ucfirst($fixture->status) }}
                                                                </span>
                                                            </div>
                                                            <div class="fixture-body text-center">
                                                                <small class="text-muted d-block mb-1">
                                                                    <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($fixture->match_date)->format('H:i') }}
                                                                </small>
                                                                <div class="d-flex justify-content-center align-items-center mb-2">
                                                                    <div class="text-center mx-2">
                                                                        @if($fixture->homeTeam->logo)
                                                                            <img src="{{ asset('front/images/liga cafetera.png') }}" alt="{{ $fixture->homeTeam->name }}" width="40" height="40" class="rounded-circle">
                                                                        @else
                                                                            <img src="{{ asset('front/images/liga cafetera.png') }}" alt="Local" width="40" height="40" class="rounded-circle">
                                                                        @endif
                                                                        <p class="mb-0 team-name-small mt-1"><strong>{{ $fixture->homeTeam->name ?? 'Local' }}</strong></p>
                                                                    </div>
                                                                    <span class="team-vs mx-2">VS</span>
                                                                    <div class="text-center mx-2">
                                                                        @if($fixture->awayTeam->logo)
                                                                            <img src="{{ asset('front/images/liga cafetera.png') }}" alt="{{ $fixture->awayTeam->name }}" width="40" height="40" class="rounded-circle">
                                                                        @else
                                                                            <img src="{{ asset('front/images/liga cafetera.png') }}" alt="Visitante" width="40" height="40" class="rounded-circle">
                                                                        @endif
                                                                        <p class="mb-0 team-name-small mt-1"><strong>{{ $fixture->awayTeam->name ?? 'Visitante' }}</strong></p>
                                                                    </div>
                                                                </div>
                                                                @if($fixture->status == 'completed')
                                                                    <div class="score text-dark">{{ $fixture->home_team_score }} - {{ $fixture->away_team_score }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info text-center" role="alert">
                                Este equipo no ha participado en ningún torneo registrado.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="col-lg-3 sidebar-banners">
                    <h4 class="text-center mb-3 text-secondary">Publicidad</h4>
                    <div class="banner-vertical">
                        <p>Banner Vertical (300x250px o similar)</p>
                    </div>
                    <div class="banner-vertical">
                        <p>Banner Vertical (300x250px o similar)</p>
                    </div>
                    <div class="banner-vertical">
                        <p>Banner Vertical (300x250px o similar)</p>
                    </div>
                </div>
            </div>

            <div class="banner-horizontal mt-5">
                <p>Espacio para Banner Horizontal Inferior (970x90px o similar)</p>
            </div>
        @endif
    </div>
</x-guest-layout>
