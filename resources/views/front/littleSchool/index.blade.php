<x-guest-layout>
    
    <!-- END nav -->
    <div class="hero-wrap" style="background-image: url('front/images/littleSchool/banner.jpg');" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
                <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
                    <h1 class="mb-4" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Escuelita</h1>
                    {{-- <p class="mb-5" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Created by <a href="#">Colorlib.com</a></p> --}}
        
                    <p data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><a href="https://vimeo.com/45830194" class="btn btn-white btn-outline-white px-4 py-3 popup-vimeo"><span class="icon-play mr-2"></span>Watch Video</a></p>
                </div>
            </div>
        </div>
    </div>
  
    <section class="ftco-section">
        <div class="container">
            <div class="row">
                <form action="{{ route('registration.register') }}" method="POST" enctype="multipart/form-data" class="volunter-form">
                    @csrf
                
                    <!-- Datos del padre/madre -->
                    <h3>Datos del Padre o Madre</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parent_name">Nombre del Padre o Madre</label>
                                <input type="text" id="parent_name" name="parent_name" class="form-control" value="{{ old('parent_name') }}" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                                @error('parent_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Correo</label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="document">Documento</label>
                                <input type="text" id="document" name="document" class="form-control" value="{{ old('document') }}" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                                @error('document')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="neighborhood">Barrio</label>
                                <input type="text" id="neighborhood" name="neighborhood" class="form-control" value="{{ old('neighborhood') }}" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                                @error('neighborhood')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parent_document">Subir Documento del Padre o Madre</label>
                                <input type="file" id="parent_document" name="parent_document" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                                @error('parent_document')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Teléfono</label>
                                <input 
                                    type="text" 
                                    id="phone" 
                                    name="phone" 
                                    class="form-control" 
                                    value="{{ old('phone') }}" 
                                    style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" 
                                    required 
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    maxlength="15"> <!-- Puedes ajustar el máximo de caracteres que quieras permitir -->
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                
                    <!-- Datos de los hijos -->
                    <h3>Datos de los Hijos</h3>
                    <div id="children-container">
                        <div class="child">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="children[0][name]">Nombre del Hijo</label>
                                        <input type="text" name="children[0][name]" class="form-control" value="{{ old('children.0.name') }}" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                                        @error('children.0.name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="children[0][age]">Edad</label>
                                        <input type="number" name="children[0][age]" class="form-control" value="{{ old('children.0.age') }}" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                                        @error('children.0.age')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="children[0][document]">Documento</label>
                                        <input type="text" name="children[0][document]" class="form-control" value="{{ old('children.0.document') }}" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                                        @error('children.0.document')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="children[0][uniform_size]">Talle de Uniforme</label>
                                        <input type="text" name="children[0][uniform_size]" class="form-control" value="{{ old('children.0.uniform_size') }}" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                                        @error('children.0.uniform_size')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="children[0][birthdate]">Fecha de Nacimiento</label>
                                        <input 
                                            type="text" 
                                            name="children[0][birthdate]" 
                                            class="form-control" 
                                            value="{{ old('children.0.birthdate') }}" 
                                            style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" 
                                            required 
                                            placeholder="12/02/2024" 
                                            oninput="this.value = this.value.replace(/[^0-9\/]/g, '').replace(/(\..*)\./g, '$1');" 
                                            maxlength="10">
                                        @error('children.0.birthdate')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <button type="button" id="add-child" class="btn btn-danger">Agregar Otro Hijo</button>
                
                    <!-- Datos de los responsables adicionales -->
                    <h3>Persona Responsable Adicional</h3>
                    <div id="guardians-container">
                        <div class="guardian">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guardians[0][name]">Nombre del Responsable</label>
                                        <input type="text" name="guardians[0][name]" class="form-control" value="{{ old('guardians.0.name') }}" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                                        @error('guardians.0.name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guardians[0][relationship]">Relación</label>
                                        <input type="text" name="guardians[0][relationship]" class="form-control" value="{{ old('guardians.0.relationship') }}" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                                        @error('guardians.0.relationship')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guardians[0][document]">Documento</label>
                                        <input type="text" name="guardians[0][document]" class="form-control" value="{{ old('guardians.0.document') }}" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                                        @error('guardians.0.document')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-guardian" class="btn btn-danger mt-3"">Agregar Otro Responsable</button>
                
                    <button type="submit" class="btn btn-primary mt-3">Registrar</button>
                </form>
            </div>
        </div>
    </section>

    <section class="ftco-section bg-light">
    	<div class="container-fluid">
    		<div class="row justify-content-center mb-5 pb-3">
            <div class="col-md-5 heading-section ftco-animate text-center">
                <h2 class="mb-4">Our Causes</h2>
                <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
            </div>
            </div>
    		<div class="row">
    			<div class="col-md-12 ftco-animate">
    				<div class="carousel-cause owl-carousel">
	    				<div class="item">
	    					<div class="cause-entry">
		    					<a href="#" class="img" style="background-image: url(images/cause-1.jpg);"></a>
		    					<div class="text p-3 p-md-4">
		    						<h3><a href="#">Clean water for the urban area</a></h3>
		    						<p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life</p>
		    						<span class="donation-time mb-3 d-block">Last donation 1w ago</span>
                                    <div class="progress custom-progress-success">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 28%" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="fund-raised d-block">$12,000 raised of $30,000</span>
		    					</div>
		    				</div>
	    				</div>
	    				<div class="item">
	    					<div class="cause-entry">
		    					<a href="#" class="img" style="background-image: url(images/cause-2.jpg);"></a>
		    					<div class="text p-3 p-md-4">
		    						<h3><a href="#">Clean water for the urban area</a></h3>
		    						<p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life</p>
		    						<span class="donation-time mb-3 d-block">Last donation 1w ago</span>
                                    <div class="progress custom-progress-success">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 28%" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="fund-raised d-block">$12,000 raised of $30,000</span>
		    					</div>
		    				</div>
	    				</div>
	    				<div class="item">
	    					<div class="cause-entry">
		    					<a href="#" class="img" style="background-image: url(images/cause-3.jpg);"></a>
		    					<div class="text p-3 p-md-4">
		    						<h3><a href="#">Clean water for the urban area</a></h3>
		    						<p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life</p>
		    						<span class="donation-time mb-3 d-block">Last donation 1w ago</span>
                                    <div class="progress custom-progress-success">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 28%" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="fund-raised d-block">$12,000 raised of $30,000</span>
		    					</div>
		    				</div>
	    				</div>
	    				<div class="item">
	    					<div class="cause-entry">
		    					<a href="#" class="img" style="background-image: url(images/cause-4.jpg);"></a>
		    					<div class="text p-3 p-md-4">
		    						<h3><a href="#">Clean water for the urban area</a></h3>
		    						<p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life</p>
		    						<span class="donation-time mb-3 d-block">Last donation 1w ago</span>
                                    <div class="progress custom-progress-success">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 28%" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="fund-raised d-block">$12,000 raised of $30,000</span>
		    					</div>
		    				</div>
	    				</div>
	    				<div class="item">
	    					<div class="cause-entry">
		    					<a href="#" class="img" style="background-image: url(images/cause-5.jpg);"></a>
		    					<div class="text p-3 p-md-4">
		    						<h3><a href="#">Clean water for the urban area</a></h3>
		    						<p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life</p>
		    						<span class="donation-time mb-3 d-block">Last donation 1w ago</span>
                                    <div class="progress custom-progress-success">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 28%" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="fund-raised d-block">$12,000 raised of $30,000</span>
		    					</div>
		    				</div>
	    				</div>
	    				<div class="item">
	    					<div class="cause-entry">
		    					<a href="#" class="img" style="background-image: url(images/cause-6.jpg);"></a>
		    					<div class="text p-3 p-md-4">
		    						<h3><a href="#">Clean water for the urban area</a></h3>
		    						<p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life</p>
		    						<span class="donation-time mb-3 d-block">Last donation 1w ago</span>
                                    <div class="progress custom-progress-success">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 28%" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="fund-raised d-block">$12,000 raised of $30,000</span>
		    					</div>
		    				</div>
	    				</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </section>

    @push('guest_js')
        <script>
            document.getElementById('add-child').addEventListener('click', function () {
                const container = document.getElementById('children-container');
                const index = container.children.length;
                const childDiv = document.createElement('div');
                childDiv.classList.add('child');
                childDiv.innerHTML = `
                <div class="child">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="children[${index}][name]">Nombre del Hijo</label>
                                <input type="text" name="children[${index}][name]" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                            </div>
                            <div class="form-group">
                                <label for="children[${index}][age]">Edad</label>
                                <input type="number" name="children[${index}][age]" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="children[${index}][birthdate]">Fecha de Nacimiento</label>
                                <input type="text" name="children[${index}][birthdate]" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" placeholder="dd/mm/yyyy" required oninput="this.value = this.value.replace(/[^0-9\/]/g, '').replace(/(\..*)\./g, '$1');" maxlength="10">
                            </div>
                            <div class="form-group">
                                <label for="children[${index}][uniform_size]">Talle de Uniforme</label>
                                <input type="text" name="children[${index}][uniform_size]" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                            </div>
                            <div class="form-group">
                                <label for="children[${index}][document]">Documento</label>
                                <input type="text" name="children[${index}][document]" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                            </div>
                        </div>
                    </div>
                </div>
                `;
                container.appendChild(childDiv);
            });
        
            document.getElementById('add-guardian').addEventListener('click', function () {
                const container = document.getElementById('guardians-container');
                const index = container.children.length;
                const guardianDiv = document.createElement('div');
                guardianDiv.classList.add('guardian');
                guardianDiv.innerHTML = `
                    <div class="form-group">
                        <label for="guardians[${index}][name]">Nombre del Responsable</label>
                        <input type="text" name="guardians[${index}][name]" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                    </div>
                    <div class="form-group">
                        <label for="guardians[${index}][relationship]">Relación</label>
                        <input type="text" name="guardians[${index}][relationship]" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                    </div>
                    <div class="form-group">
                        <label for="guardians[${index}][document]">Documento</label>
                        <input type="text" name="guardians[${index}][document]" class="form-control" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" required>
                    </div>
                `;
                container.appendChild(guardianDiv);
            });

            document.querySelector('input[name="children[0][birthdate]"]').addEventListener('input', function (e) {
                let input = e.target.value;
                if (/\d{2}\/\d{2}\/\d{4}/.test(input)) {
                    // Valid format
                    e.target.setCustomValidity('');
                } else {
                    // Invalid format
                    e.target.setCustomValidity('La fecha debe tener el formato dd/mm/yyyy');
                }
            });
        </script>
    @endpush
</x-guest-layout>
