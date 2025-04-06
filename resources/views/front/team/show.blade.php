<x-guest-layout>
    <section class="ftco-section">
        <div class="container">
            <h1 class="mb-5 text-center fs-1 fw-bold">{{ $team->name }}</h1>

            {{-- Filtro único por Torneo --}}
            <div class="mb-4">
                <h3 class="fs-4 fw-semibold mb-3">Filtrar por Torneo</h3>
                <select id="globalTournamentFilter" class="form-select">
                    <option value="">Selecciona un torneo</option>
                    @foreach(array_unique(array_merge(
                        $playersByTournament->keys()->toArray(),
                        $fixturesByTournament->keys()->toArray(),
                        array_keys($topScorersByTournament)
                    )) as $tournamentName)
                    <option value="{{ $tournamentName }}" {{ $currentTournament && $currentTournament->name === $tournamentName ? 'selected' : '' }}>
                        {{ $tournamentName }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="row g-4">
                {{-- Jugadores por Torneo --}}
                <div class="col-lg-4">
                    <div class="bg-white rounded shadow p-3 h-100">
                        <h3 class="fs-5 fw-semibold mb-3">Jugadores por Torneo</h3>
                        <div id="playersContainer">
                            @foreach($playersByTournament as $tournamentName => $players)
                            <div class="mb-4 tournament-group" data-tournament="{{ $tournamentName }}" style="{{ $currentTournament && $currentTournament->name !== $tournamentName ? 'display: none;' : '' }}">
                                <h4 class="fs-6 fw-semibold mb-2">{{ $tournamentName }}</h4>
                                <ul class="list-group">
                                    @forelse($players as $player)
                                    <li class="list-group-item">
                                        {{ $player->user->name }} - {{ $player->position }} - Nº {{ $player->number }}
                                    </li>
                                    @empty
                                    <li class="list-group-item">No hay jugadores en este torneo.</li>
                                    @endforelse
                                </ul>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Partidos por Torneo --}}
                <div class="col-lg-4">
                    <div class="bg-white rounded shadow p-3 h-100">
                        <h3 class="fs-5 fw-semibold mb-3">Partidos por Torneo</h3>
                        <div id="fixturesContainer">
                            @forelse($fixturesByTournament as $tournamentName => $fixtures)
                            <div class="mb-4 tournament-group" data-tournament="{{ $tournamentName }}" style="{{ $currentTournament && $currentTournament->name !== $tournamentName ? 'display: none;' : '' }}">
                                <h4 class="fs-6 fw-semibold mb-2">{{ $tournamentName }}</h4>
                                <ul class="list-group">
                                    @foreach($fixtures as $fixture)
                                    @php
                                        $isHome = $fixture->home_team_id === $team->id;
                                        $isAway = $fixture->away_team_id === $team->id;
                                        $resultClass = 'text-muted';

                                        if ($fixture->status === 'completed') {
                                            if ($fixture->home_team_score === $fixture->away_team_score) {
                                                $resultClass = 'text-warning';
                                            } elseif (($isHome && $fixture->home_team_score > $fixture->away_team_score) || ($isAway && $fixture->away_team_score > $fixture->home_team_score)) {
                                                $resultClass = 'text-success';
                                            } else {
                                                $resultClass = 'text-danger';
                                            }
                                        }
                                    @endphp
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-12 mb-1">
                                                <small class="text-secondary">{{ \Carbon\Carbon::parse($fixture->match_date)->format('d/m/Y H:i') }}</small>
                                            </div>
                                            <div class="col-8">
                                                {{ $fixture->homeTeam->name }} vs {{ $fixture->awayTeam->name }}
                                            </div>
                                            <div class="col-4 text-end fw-bold {{ $resultClass }}">
                                                @if ($fixture->status === 'completed')
                                                {{ $fixture->home_team_score }} - {{ $fixture->away_team_score }}
                                                @else
                                                    Por jugar
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @empty
                            <p>No hay partidos registrados.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Goleadores por Torneo --}}
                <div class="col-lg-4">
                    <div class="bg-white rounded shadow p-3 h-100">
                        <h3 class="fs-5 fw-semibold mb-3">Goleadores del equipo por torneo</h3>
                        <div id="scorersContainer">
                            @foreach($topScorersByTournament as $tournamentName => $scorers)
                            <div class="mb-4 tournament-group" data-tournament="{{ $tournamentName }}" style="{{ $currentTournament && $currentTournament->name !== $tournamentName ? 'display: none;' : '' }}">
                                <h4 class="fs-6 fw-semibold mb-2">{{ $tournamentName }}</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th>Jugador</th>
                                                <th>Número</th>
                                                <th>Posición</th>
                                                <th>Goles</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($scorers as $scorer)
                                            <tr>
                                                <td>{{ $scorer->player->user->name }}</td>
                                                <td>{{ $scorer->player->number }}</td>
                                                <td>{{ $scorer->player->position }}</td>
                                                <td><strong>{{ $scorer->goals }}</strong></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.getElementById('globalTournamentFilter').addEventListener('change', function () {
            const selectedTournament = this.value;
            document.querySelectorAll('.tournament-group').forEach(group => {
                group.style.display = group.getAttribute('data-tournament') === selectedTournament || !selectedTournament ? 'block' : 'none';
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const currentTournament = "{{ $currentTournament ? $currentTournament->name : '' }}";
            document.querySelectorAll('.tournament-group').forEach(group => {
                group.style.display = group.getAttribute('data-tournament') === currentTournament || !currentTournament ? 'block' : 'none';
            });
        });
    </script>
</x-guest-layout>
