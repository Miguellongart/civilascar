<x-guest-layout>
    <!-- END nav -->
    {{-- <div class="hero-wrap" style="background-image: url('front/images/littleSchool/banner.jpg');" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
                <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
                    <h1 class="mb-4" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }" style="font-weight: 900;">Liga Cafetera</h1>
                </div>
            </div>
        </div>
    </div> --}}
    <section class="ftco-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="mb-3">Registro de Jugador</h3>
                    <form method="POST" action="{{ route('front.tournament.register') }}" class="volunter-form" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="tournament_id">Selecciona el Torneo</label>
                            <select name="tournament_id" id="tournament_id" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                                <option value="">Selecciona un Torneo</option>
                                @foreach($tournaments as $tournament)
                                    <option value="{{ $tournament->id }}">{{ $tournament->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="team_id">Selecciona el Equipo</label>
                            <select name="team_id" id="team_id" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                                <option value="">Selecciona un Equipo</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" name="name" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                        </div>
                        <div class="form-group">
                            <label for="dni">DNI/Documento</label>
                            <input type="text" name="dni" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                        </div>
                        <div class="form-group">
                            <label for="position">Posición</label>
                            <input type="text" name="position" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                        </div>
                        <div class="form-group">
                            <label for="number">Número</label>
                            <input type="number" name="number" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                        </div>
                        <div class="form-group">
                            <label for="photo">Foto del Jugador</label>
                            <input type="file" name="photo" class="form-control-file" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;">
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Registrar Jugador" class="btn btn-white py-3 px-5">
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <h3 class="mb-3">Información del Torneo</h3>
                    <div id="tournament_info" style="display: none;">
                        <p><strong>Torneo:</strong> <span id="tournament_name"></span></p>
                        <p><strong>Inicio:</strong> <span id="tournament_start_date"></span></p>
                        <p><strong>Fin:</strong> <span id="tournament_end_date"></span></p>
                        <p><strong>Ubicación:</strong> <span id="tournament_location"></span></p>
                        <p><strong>Estado:</strong> <span id="tournament_status"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('guest_js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tournamentSelect = document.getElementById('tournament_id');
            const teamSelect = document.getElementById('team_id');

            tournamentSelect.addEventListener('change', function () {
                const tournamentId = this.value;
                console.log(tournamentId);
                
                // Limpiar los equipos anteriores
                teamSelect.innerHTML = '<option value="">Selecciona un Equipo</option>';

                if (tournamentId) {
                    // Obtener equipos para el torneo seleccionado
                    fetch(`/tournament/${tournamentId}/teams`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            data.forEach(team => {
                                const option = document.createElement('option');
                                option.value = team.id;
                                option.textContent = team.name;
                                teamSelect.appendChild(option);
                            });

                            // Mostrar información del torneo
                            fetch(`/tournament/${tournamentId}`)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Network response was not ok');
                                    }
                                    return response.json();
                                })
                                .then(tournament => {
                                    document.getElementById('tournament_name').textContent = tournament.name;
                                    document.getElementById('tournament_start_date').textContent = tournament.start_date;
                                    document.getElementById('tournament_end_date').textContent = tournament.end_date;
                                    document.getElementById('tournament_location').textContent = tournament.location;
                                    document.getElementById('tournament_status').textContent = tournament.status;
                                    document.getElementById('tournament_info').style.display = 'block';
                                })
                                .catch(error => console.error('Error fetching tournament info:', error));
                        })
                        .catch(error => console.error('Error fetching teams:', error));
                } else {
                    document.getElementById('tournament_info').style.display = 'none';
                }
            });
        });
    </script>
    @endpush

    @push('guest_css')
    @endpush
</x-guest-layout>
