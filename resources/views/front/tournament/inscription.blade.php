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
                                    <option value="{{ $tournament->id }}" {{ old('tournament_id') == $tournament->id ? 'selected' : '' }}>
                                        {{ $tournament->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tournament_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="team_id">Selecciona el Equipo</label>
                            <select name="team_id" id="team_id" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                                <option value="">Selecciona un Equipo</option>
                            </select>
                            @error('team_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="dni">DNI/Documento</label>
                            <input type="text" name="dni" class="form-control" value="{{ old('dni') }}" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                            @error('dni')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Otros campos omitidos --}}
                        <div class="form-group">
                            <x-adminlte-select name="position" label="Vehicle" label-class="text-lightblue"
                                igroup-size="lg">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text bg-gradient-info">
                                        <i class="fas fa-car-side"></i>
                                    </div>
                                </x-slot>
                                <option value="Arquero" {{ old('position') == 'Arquero' ? 'selected' : '' }}>Arquero (ARQ)</option>
                                <option value="Defensa" {{ old('position') == 'Defensa' ? 'selected' : '' }}>Defensa (DF)</option>
                                <option value="Medio Centro" {{ old('position') == 'Medio Centro' ? 'selected' : '' }}>Medio Centro (MC)</option>
                                <option value="Delantero" {{ old('position') == 'Delantero' ? 'selected' : '' }}>Delantero (D)</option>
                            </x-adminlte-select>
                            @error('position')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="number">Número Camiseta</label>
                            <input type="number" name="number" class="form-control" value="{{ old('number') }}" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                            @error('number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="photo">Foto del Jugador</label>
                            <input type="file" name="photo" class="form-control-file" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;">
                            @error('photo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
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
