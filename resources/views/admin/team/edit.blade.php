@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', $subtitle)
@section('content_header_title', $content_header_title)
@section('content_header_subtitle', $content_header_subtitle)

@section('content_body')
    <x-adminlte-card theme="lime" theme-mode="outline">
        <x-adminlte-card>
            <div class="row">
                <div class="col-md-4">
                    <x-adminlte-profile-widget name="{{ $team->name }}" desc="{{ $team->description }}" theme="teal" img="{{ asset('storage/' . $team->logo) }}">
                        <x-adminlte-profile-col-item title="Entrenador" text="{{ $team->coach }}" url="#"/>
                        <x-adminlte-profile-col-item title="Estadio" text="{{ $team->home_stadium }}" url="#"/>
                    </x-adminlte-profile-widget>
                </div>
                <div class="col-md-8">
                    {{-- Mensaje de éxito al actualizar --}}
                    @if(session('success'))
                        <x-adminlte-alert theme="success" title="Éxito">
                            {{ session('success') }}
                        </x-adminlte-alert>
                    @endif

                    <form action="{{ route('admin.team.update', $team->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <x-adminlte-input name="name" value="{{ old('name', $team->name) }}" label="Nombre del Equipo" placeholder="Nombre del Equipo" fgroup-class="col-md-6" label-class="text-lightblue">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-users text-lightblue"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            {{-- Muestra el error de validación para el campo 'name' --}}
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                            <x-adminlte-input name="coach" value="{{ old('coach', $team->coach) }}" label="Entrenador" placeholder="Entrenador" fgroup-class="col-md-6" label-class="text-lightblue">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-user text-lightblue"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                             {{-- Muestra el error de validación para el campo 'coach' --}}
                            @error('coach')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                             {{-- Vista previa de la imagen y campo de subida --}}
                            <div class="col-md-6">
                                <label for="logo" class="text-lightblue">Logotipo del Equipo</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-image text-lightblue"></i></span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('logo') is-invalid @enderror" id="logo" name="logo" onchange="previewImage(event);">
                                        <label class="custom-file-label" for="logo">Seleccionar archivo</label>
                                    </div>
                                </div>
                                @error('logo')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="mt-2 text-center">
                                    <img id="logo-preview" src="{{ asset('storage/' . $team->logo) }}" alt="Previsualización del logo" style="max-width: 150px; max-height: 150px; border-radius: 50%;">
                                </div>
                            </div>
                            
                            <x-adminlte-textarea name="description" label="Descripción" placeholder="Descripción del Equipo" fgroup-class="col-md-6" label-class="text-lightblue">{{ old('description', $team->description) }}</x-adminlte-textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <x-adminlte-input name="home_stadium" value="{{ old('home_stadium', $team->home_stadium) }}" label="Estadio Local" placeholder="Estadio Local" fgroup-class="col-md-6" label-class="text-lightblue">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-building text-lightblue"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            @error('home_stadium')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                            <x-adminlte-select2 name="user_id" label="Usuario" fgroup-class="col-md-6" label-class="text-lightblue">
                                <option></option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ (old('user_id', $team->user_id) == $user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </x-adminlte-select2>
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <x-adminlte-button class="btn-sm" type="submit" label="Actualizar" theme="outline-success" icon="fas fa-save"/>
                    </form>
                </div>
            </div>
        </x-adminlte-card>
    </x-adminlte-card>
@stop

@push('css')
@endpush

{{-- Script para la vista previa de la imagen --}}
@push('js')
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('logo-preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endpush
