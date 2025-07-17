<x-guest-layout>
    <div class="container mt-5 mb-5">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0"><i class="bi bi-person-plus-fill me-2"></i>Inscripción de Jugador</h3>
                <p class="mb-0">Completa los datos para registrar un nuevo jugador.</p>
            </div>
            <div class="card-body p-4">

                {{-- Mostrar mensajes de éxito o error de SweetAlert --}}
                @include('sweetalert::alert')

                {{-- Mostrar errores de validación --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h4 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Error de Validación!</h4>
                        <p>Por favor, corrige los siguientes errores:</p>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('front.tournament.register') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- Sección de Datos Torneo --}}
                    <h5 class="mb-3 text-warning"><i class="bi bi-person-lines-fill me-2"></i>Datos Torneo</h5>

                    {{-- Información del torneo --}}
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="bi bi-trophy me-2"></i> Información del Torneo
                        </div>
                        <div class="card-body">
                            <h5 class="card-title mb-3">{{ $tournament->name ?? 'Torneo no disponible' }}</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Fecha de Inicio:</strong> {{ \Carbon\Carbon::parse($tournament->start_date)->format('d/m/Y') ?? '-' }}</li>
                                <li class="list-group-item"><strong>Fecha de Finalización:</strong> {{ \Carbon\Carbon::parse($tournament->end_date)->format('d/m/Y') ?? '-' }}</li>
                                <li class="list-group-item"><strong>Ubicación:</strong> {{ $tournament->location ?? 'No especificada' }}</li>
                                <li class="list-group-item"><strong>Descripción:</strong> {{ $tournament->description ?? 'Sin descripción' }}</li>
                            </ul>

                            {{-- Campo oculto para enviar ID del torneo --}}
                            <input type="hidden" name="tournament_id" value="{{ $tournament->id ?? '' }}">

                            {{-- Selector de equipos --}}
                            <div class="mt-4">
                                <label for="team_id" class="form-label">Selecciona un equipo:</label>
                                <select name="team_id" id="team_id" class="form-select @error('team_id') is-invalid @enderror" required>
                                    <option value="">-- Selecciona una opción --</option>
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('team_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Sección de Datos Personales --}}
                    <h5 class="mb-3 text-primary"><i class="bi bi-person-lines-fill me-2"></i>Datos Personales</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">Nombre:</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Apellido:</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="date_of_birth" class="form-label">Fecha de Nacimiento:</label>
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone_number" class="form-label">Número de Contacto:</label>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="+54 9 11 1234 5678" required>
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="dni" class="form-label">DNI:</label>
                            <input type="text" class="form-control @error('dni') is-invalid @enderror" id="dni" name="dni" value="{{ old('dni') }}" required>
                            @error('dni')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Correo Electrónico (Opcional):</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="ejemplo@dominio.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Sección de Datos del Jugador --}}
                    <h5 class="mb-3 text-success"><i class="bi bi-dribbble me-2"></i>Datos del Jugador</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="position" class="form-label">Posición:</label>
                            <select name="position" id="position" class="form-select @error('position') is-invalid @enderror" required>
                                <option value="">-- Selecciona una posición --</option>
                                <option value="Arquero" {{ old('position') == 'Arquero' ? 'selected' : '' }}>Arquero</option>
                                <option value="Defensa" {{ old('position') == 'Defensa' ? 'selected' : '' }}>Defensa</option>
                                <option value="Pivote" {{ old('position') == 'Pivote' ? 'selected' : '' }}>Pivote</option>
                                <option value="Delantero" {{ old('position') == 'Delantero' ? 'selected' : '' }}>Delantero</option>
                            </select>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="number" class="form-label">Número de Camiseta:</label>
                            <input type="number" class="form-control @error('number') is-invalid @enderror" id="number" name="number" value="{{ old('number') }}" min="1" max="99" required>
                            @error('number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Sección de Archivos --}}
                    <h5 class="mb-3 text-info"><i class="bi bi-upload me-2"></i>Documentos y Fotos</h5>
                    <div class="mb-3">
                        <label for="player_photo" class="file-input-label">Foto del Jugador (Opcional):</label>
                        <input type="file" class="form-control @error('player_photo') is-invalid @enderror" id="player_photo" name="player_photo" accept="image/*">
                        @error('player_photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Max. 2MB. Formatos: JPG, PNG.</small>
                    </div>
                    <div class="mb-4">
                        <label for="document_photo" class="file-input-label">Foto del Documento de Identidad <span style="color: red; font-weight: bold">(Requeridos)</span>:</label>
                        <input type="file" class="form-control @error('document_photo') is-invalid @enderror" id="document_photo" name="document_photo" accept="image/*" required>
                        @error('document_photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Max. 2MB. Formatos: JPG, PNG. Asegúrate de que sea legible.</small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-send-fill me-2"></i>Registrar Jugador</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @section('script')
        @if(session('swal_success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: '{{ session('swal_success') }}',
                    confirmButtonColor: '#3085d6'
                });
            </script>
        @endif

        @if(session('swal_info'))
            <script>
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: '{{ session('swal_info') }}',
                    confirmButtonColor: '#3085d6'
                });
            </script>
        @endif

        {{-- Otros scripts personalizados --}}
        <script>
            console.log('hola mundo');
        </script>
    @endsection

</x-guest-layout>
