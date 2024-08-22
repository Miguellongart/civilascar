<x-guest-layout>
    <section class="ftco-section">
        <div class="container">
            @foreach ($tournaments as $tournament)
                <div class="container">
                    <div class="row mb-5">
                        <!-- Información del torneo -->
                        <div class="col-md-7 d-flex flex-column align-items-start order-2 order-md-1">
                            <h3>{{ $tournament->name }}</h3>
                            <p><strong>Inicio:</strong> {{ $tournament->start_date }}</p>
                            <p><strong>Fin:</strong> {{ $tournament->end_date }}</p>
                            <p><strong>Ubicación:</strong> {{ $tournament->location }}</p>
                            <p><strong>Estado:</strong> {{ $tournament->status }}</p>
                        </div>
                
                        <!-- Banners de publicidad -->
                        <div class="col-md-5 d-flex flex-column order-1 order-md-2">
                            <div class="d-flex flex-row align-items-center mb-3 flex-wrap">
                                <img src="{{ asset('front/images/marcas/pl.png') }}" alt="Publicidad" class="publicidad img-fluid mr-2 mb-2 mb-md-0">
                                <img src="{{ asset('front/images/marcas/ci.jpg') }}" alt="Publicidad" style="border-radius: 50%" class="publicidad img-fluid">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-8">
                    </div>
                    <div class="col-md-4">
                        <a href="{{route('front.inscription')}}" class="btn btn-white px-3 py-2 mt-2">Registrar Jugador</a>
                    </div>
                </div>

                <div class="container">
                    <div class="row mb-6">
                        <!-- Columna izquierda para banners de publicidad -->
                        <div class="col-md-2">
                            {{-- Publicidad --}}
                        </div>
                
                        <!-- Contenido principal -->
                        <div class="col-md-8">
                            <div class="mb-3">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-primary" id="positionsButton">
                                            Tabla de Posiciones
                                        </button>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-primary" id="fixturesButton">
                                            Fixtures
                                        </button>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-primary" id="topScorersButton">
                                            Goleadores
                                        </button>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-primary" id="cardsButton">
                                            Tarjetas Acumuladas
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="col-auto">
                                    <form method="GET" action="{{ route('front.tournament', $tournament->id) }}" class="form-inline">
                                        <div class="form-group">
                                            <label for="filter_date" class="sr-only">Filtrar por fecha</label>
                                            <input type="date" name="filter_date" id="filter_date" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" value="{{ $filterDate }}">
                                        </div>
                                        <button type="submit" class="btn btn-primary ml-2">Filtrar</button>
                                    </form>
                                </div>s
                            </div>
                
                            <!-- Tabla de Posiciones -->
                            <div id="positionsSection" class="content-section">
                                <h4>Tabla de Posiciones</h4>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Equipo</th>
                                            <th>PJ</th>
                                            <th>PG</th>
                                            <th>PE</th>
                                            <th>PP</th>
                                            <th>GF</th>
                                            <th>GC</th>
                                            <th>DG</th>
                                            <th>PT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tournament->positionTables as $position)
                                            <tr>
                                                <td>{{ $position->team->name }}</td>
                                                <td>{{ $position->played }}</td>
                                                <td>{{ $position->won }}</td>
                                                <td>{{ $position->drawn }}</td>
                                                <td>{{ $position->lost }}</td>
                                                <td>{{ $position->goals_for }}</td>
                                                <td>{{ $position->goals_against }}</td>
                                                <td>{{ $position->goal_difference }}</td>
                                                <td>{{ $position->points }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Fixtures -->
                            <div id="fixturesSection" class="content-section" style="display: none;">
                                <h4>Fixtures</h4>
                                <div class="fixtures">
                                    @foreach ($fixtures as $fixture)
                                        <div class="card mb-2" style="border: #3e0171 solid 1px">
                                            <div class="row no-gutters">
                                                <div class="col-md-12">
                                                    <div class="card-body p-2">
                                                        <h6 class="card-title mb-1">
                                                            {{ \Carbon\Carbon::parse($fixture->match_date)->format('d/m/Y H:i') }}
                                                        </h6>
                                                        <p class="card-text mb-1">
                                                            <span class="team" style="color: #3e0171">{{ $fixture->homeTeam->name }}</span>
                                                            <span class="score" style="color: #000">{{ $fixture->home_team_score ?? '0' }}</span>
                                                            -
                                                            <span class="score" style="color: #000">{{ $fixture->away_team_score ?? '0' }}</span>
                                                            <span class="team" style="color: #3e0171">{{ $fixture->awayTeam->name }}</span>
                                                        </p>
                                                        <p class="card-text">
                                                            <small class="text-muted">
                                                                @if ($fixture->status == 'completed')
                                                                    Jugado
                                                                @else
                                                                    Por jugar
                                                                @endif
                                                            </small>
                                                        </p>
                            
                                                        <!-- Botón para desplegar eventos -->
                                                        @if ($fixture->playerEvents->isNotEmpty())
                                                            <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#events_{{ $fixture->id }}" aria-expanded="false" aria-controls="events_{{ $fixture->id }}">
                                                                Mostrar Eventos
                                                            </button>
                            
                                                            <!-- Eventos en dos columnas, con collapsible -->
                                                            <div class="collapse mt-2" id="events_{{ $fixture->id }}">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <h6 class="card-subtitle mb-1">Equipo Local:</h6>
                                                                        <ul class="list-unstyled">
                                                                            @foreach ($fixture->playerEvents->where('player.team_id', $fixture->home_team_id) as $event)
                                                                                <li>
                                                                                    {{ $event->player->user->name }} - 
                                                                                    {{ ucfirst(str_replace('_', ' ', $event->event_type)) }}: 
                                                                                    {{ $event->quantity }}
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <h6 class="card-subtitle mb-1">Equipo Visitante:</h6>
                                                                        <ul class="list-unstyled">
                                                                            @foreach ($fixture->playerEvents->where('player.team_id', $fixture->away_team_id) as $event)
                                                                                <li>
                                                                                    {{ $event->player->user->name }} - 
                                                                                    {{ ucfirst(str_replace('_', ' ', $event->event_type)) }}: 
                                                                                    {{ $event->quantity }}
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Tabla de Goleadores -->
                            <div id="topScorersSection" class="content-section" style="display: none;">
                                <h4>Goleadores</h4>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Jugador</th>
                                            <th>Equipo</th>
                                            <th>Goles</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topScorers as $scorer)
                                            <tr>
                                                <td>{{ $scorer->player->user->name }}</td>
                                                <td>{{ $scorer->player->team->name }}</td>
                                                <td>{{ $scorer->goals }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Tabla de Tarjetas Acumuladas -->
                            <div id="cardsSection" class="content-section" style="display: none;">
                                <h4>Tarjetas Acumuladas</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Tarjetas Amarillas</h5>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Jugador</th>
                                                    <th>Equipo</th>
                                                    <th>Tarjetas Amarillas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($yellowCards as $yellowCard)
                                                    <tr>
                                                        <td>{{ $yellowCard->player->user->name }}</td>
                                                        <td>{{ $yellowCard->player->team->name }}</td>
                                                        <td>{{ $yellowCard->yellow_cards }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Tarjetas Rojas</h5>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Jugador</th>
                                                    <th>Equipo</th>
                                                    <th>Tarjetas Rojas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($redCards as $redCard)
                                                    <tr>
                                                        <td>{{ $redCard->player->user->name }}</td>
                                                        <td>{{ $redCard->player->team->name }}</td>
                                                        <td>{{ $redCard->red_cards }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                
                        <!-- Columna derecha para banners de publicidad -->
                        <div class="col-md-2">
                            {{-- Publicidad --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    @push('guest_js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var positionsButton = document.getElementById('positionsButton');
            var fixturesButton = document.getElementById('fixturesButton');
            var topScorersButton = document.getElementById('topScorersButton');
            var cardsButton = document.getElementById('cardsButton');

            var positionsSection = document.getElementById('positionsSection');
            var fixturesSection = document.getElementById('fixturesSection');
            var topScorersSection = document.getElementById('topScorersSection');
            var cardsSection = document.getElementById('cardsSection');

            // Inicialmente mostrar la tabla de posiciones y ocultar las demás
            positionsSection.style.display = 'block';
            fixturesSection.style.display = 'none';
            topScorersSection.style.display = 'none';
            cardsSection.style.display = 'none';

            // Mostrar/Ocultar secciones al hacer clic en los botones
            positionsButton.addEventListener('click', function () {
                positionsSection.style.display = 'block';
                fixturesSection.style.display = 'none';
                topScorersSection.style.display = 'none';
                cardsSection.style.display = 'none';
            });

            fixturesButton.addEventListener('click', function () {
                positionsSection.style.display = 'none';
                fixturesSection.style.display = 'block';
                topScorersSection.style.display = 'none';
                cardsSection.style.display = 'none';
            });

            topScorersButton.addEventListener('click', function () {
                positionsSection.style.display = 'none';
                fixturesSection.style.display = 'none';
                topScorersSection.style.display = 'block';
                cardsSection.style.display = 'none';
            });

            cardsButton.addEventListener('click', function () {
                positionsSection.style.display = 'none';
                fixturesSection.style.display = 'none';
                topScorersSection.style.display = 'none';
                cardsSection.style.display = 'block';
            });
        });
    </script>
    @endpush

    @push('guest_css')
        <style>
            .fixtures {
                display: flex;
                flex-direction: column;
            }

            .card {
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 0.25rem;
                padding: 1rem;
                margin-bottom: 1rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .card-body {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .team {
                font-weight: bold;
            }

            .score {
                margin: 0 0.5rem;
                font-size: 1.25rem;
            }

            .publicidad {
                max-width: 48%; /* Ajusta este valor según sea necesario para que los logos quepan uno al lado del otro */
            }

            @media (max-width: 767.98px) {
                .d-flex.flex-row img {
                    margin-right: 10px;
                }
            }

            .content-section {
                display: none;
            }
        </style>  
    @endpush
</x-guest-layout>
