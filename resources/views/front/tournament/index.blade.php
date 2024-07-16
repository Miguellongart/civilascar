<x-guest-layout>
    <section class="ftco-section">
        <div class="container">
            @foreach ($tournaments as $tournament)
                <div class="row mb-5">
                    <div class="col-md-12">
                        <h3>{{ $tournament->name }}</h3>
                        <p><strong>Inicio:</strong> {{ $tournament->start_date }}</p>
                        <p><strong>Fin:</strong> {{ $tournament->end_date }}</p>
                        <p><strong>Ubicación:</strong> {{ $tournament->location }}</p>
                        <p><strong>Estado:</strong> {{ $tournament->status }}</p>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-12">
                        {{-- <form method="GET" action="{{ route('front.tournament.index') }}" class="mb-4">
                            <div class="form-group">
                                <label for="filter_date">Filtrar por fecha</label>
                                <input type="date" name="filter_date" id="filter_date" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;">
                            </div>
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </form> --}}
                    </div>
                </div>

                <div class="row mb-5" style="background-color: ">
                    <div class="col-md-6">
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

                    <div class="col-md-6"  style="background-color: ">
                        <h4>Fixtures</h4>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>EL</th>
                                    <th>GOLES</th>
                                    <th>EV</th>
                                    <th>GOLES</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fixtures as $fixture)
                                    <tr>
                                        <td style="font-size: 12px">{{ \Carbon\Carbon::parse($fixture->match_date)->format('d/m/Y H:i') }}</td>
                                        <td style="font-size: 12px">{{ $fixture->homeTeam->name }}</td>
                                        <td style="font-size: 12px">{{ $fixture->home_team_score ?? '0' }}</td>
                                        <td style="font-size: 12px">{{ $fixture->awayTeam->name }}</td>
                                        <td style="font-size: 12px">{{ $fixture->away_team_score ?? '0' }}</td>
                                        <td style="font-size: 12px">
                                            @if ($fixture->status == 'completed')
                                                Jugado
                                            @else
                                                Por jugar
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</x-guest-layout>
