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
                            <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position') }}" placeholder="Ej: Delantero, Defensa" required>
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
                        <label for="document_photo" class="file-input-label">Foto del Documento de Identidad (Opcional):</label>
                        <input type="file" class="form-control @error('document_photo') is-invalid @enderror" id="document_photo" name="document_photo" accept="image/*">
                        @error('document_photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Max. 2MB. Formatos: JPG, PNG. Asegúrate de que sea legible.</small>
                    </div>

                    {{-- Campos ocultos para Team ID y Tournament ID --}}
                    {{-- Estos valores deberían ser pasados desde el controlador, por ejemplo: --}}
                    {{-- return view('your.view', ['teamId' => $team->id, 'tournamentId' => $tournament->id]); --}}
                    <input type="hidden" name="team_id" value="{{ $teamId ?? '' }}">
                    <input type="hidden" name="tournament_id" value="{{ $tournamentId ?? '' }}">

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-send-fill me-2"></i>Registrar Jugador</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-guest-layout>
