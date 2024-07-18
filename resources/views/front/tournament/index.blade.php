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
                        {{-- <form method="GET" action="{{ route('front.tournament.index') }}" class="mb-4">
                            <div class="form-group">
                                <label for="filter_date">Filtrar por fecha</label>
                                <input type="date" name="filter_date" id="filter_date" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;">
                            </div>
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </form> --}}
                    </div>
                    <div class="col-md-4">
                        <a href="{{route('front.inscription')}}" class="btn btn-white px-3 py-2 mt-2">Registrar Jugador</a>
                    </div>
                </div>


                <div class="container">
                    <div class="row mb-6">
                        <!-- Columna izquierda para banners de publicidad -->
                        <div class="col-md-2">
                            <div class="banner">
                                <img src="{{ asset('front/images/banners/publicidad.png') }}" alt="Publicidad" class="img-fluid">
                            </div>
                            <div class="banner mt-4">
                                <img src="{{ asset('front/images/banners/publicidad.png') }}" alt="Publicidad" class="img-fluid">
                            </div>
                        </div>
                
                        <!-- Contenido principal -->
                        <div class="col-md-8">
                            <div class="mb-3">
                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapsePositions" aria-expanded="true" aria-controls="collapsePositions">
                                    Tabla de Posiciones
                                </button>
                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseFixtures" aria-expanded="false" aria-controls="collapseFixtures">
                                    Fixtures
                                </button>
                            </div>
                
                            <div id="collapsePositions" class="collapse show">
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
                                                <td>
                                                    {{-- <img src="{{ asset('storage/'.$position->team->logo) }}" alt="Logo" height="30"> --}}
                                                    {{ $position->team->name }}
                                                </td>
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
                
                            <div id="collapseFixtures" class="collapse">
                                <h4>Fixtures</h4>
                                <div class="fixtures">
                                    @foreach ($fixtures as $fixture)
                                        <div class="card mb-2" style="border: #3e0171 solid 1px">
                                            <div class="row no-gutters">
                                                <div class="col-md-12">
                                                    <div class="card-body p-2">
                                                        <h6 class="card-title mb-1">{{ \Carbon\Carbon::parse($fixture->match_date)->format('d/m/Y H:i') }}</h6>
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
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                
                        <!-- Columna derecha para banners de publicidad -->
                        <div class="col-md-2">
                            <div class="banner">
                                <img src="{{ asset('front/images/banners/publicidad.png') }}" alt="Publicidad" class="img-fluid">
                            </div>
                            <div class="banner mt-4">
                                <img src="{{ asset('front/images/banners/publicidad.png') }}" alt="Publicidad" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var positionsButton = document.querySelector('[data-target="#collapsePositions"]');
            var fixturesButton = document.querySelector('[data-target="#collapseFixtures"]');
            var collapsePositions = document.getElementById('collapsePositions');
            var collapseFixtures = document.getElementById('collapseFixtures');
    
            positionsButton.addEventListener('click', function () {
                if (!collapsePositions.classList.contains('show')) {
                    $('#collapsePositions').collapse('show');
                    $('#collapseFixtures').collapse('hide');
                }
            });
    
            fixturesButton.addEventListener('click', function () {
                if (!collapseFixtures.classList.contains('show')) {
                    $('#collapseFixtures').collapse('show');
                    $('#collapsePositions').collapse('hide');
                }
            });
    
            // Ensure only one section is visible on page load
            if (collapsePositions.classList.contains('show')) {
                collapseFixtures.classList.remove('show');
            } else if (collapseFixtures.classList.contains('show')) {
                collapsePositions.classList.remove('show');
            }
        });
    </script>
    @endsection

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
        </style>  
    @endpush
</x-guest-layout>