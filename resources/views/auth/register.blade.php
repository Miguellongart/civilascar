<x-guest-layout>
    <section class="ftco-section">
    	<div class="container">
    		
            <div class="row d-md-flex justify-content-center">
                <div class="d-flex ftco-animate">
                    <div class="img img-2 align-self-stretch" style="background-image: url(images/bg_4.jpg);"></div>
                </div>
                <div class="volunteer pl-md-5 ftco-animate">
                    <h3 class="mb-3">Registro</h3>
                    <form method="POST" action="{{ route('register') }}" class="volunter-form">
                        @csrf
                        <div class="form-group">
                            <x-input-label for="name" :value="__('Nombre')" />
                            <input type="text" name="name" :value="old('name')" required style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" class="form-control" placeholder="Email">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <x-input-label for="dni" :value="__('DNI/Documento')" />
                            <input type="text" name="dni" :value="old('dni')" required style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" class="form-control" placeholder="Email">
                            <x-input-error :messages="$errors->get('dni')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <x-input-label for="email" :value="__('Email')" />
                            <input type="text" name="email" :value="old('email')" required style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" class="form-control" placeholder="Email">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <x-input-label for="password" :value="__('Contraseña')" />
                            <input type="password" name="password" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" class="form-control" placeholder="Contraseña">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                            <input type="password" name="password_confirmation" style="border: 2px solid rgba(0, 0, 0, 0.7); color:rgba(0, 0, 0, 0.7) !important;" class="form-control" placeholder="Contraseña">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                        <!-- Remember Me -->
                        <div class="block mt-4">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                            </label>
                        </div>
                        <div class="form-group">
                            
                            <input type="submit" value="Registrar" class="btn btn-white py-3 px-5">
                        </div>
                    </form>
                </div>    			
            </div>
                
    	</div>
    </section>
</x-guest-layout>
  
