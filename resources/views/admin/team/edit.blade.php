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
                    <x-adminlte-profile-widget name="{{$team->name}}" desc="{{$team->description}}" theme="teal" img="{{ asset('storage/' . $team->logo) }}">
                        <x-adminlte-profile-col-item title="Entrenador" text="{{$team->coach}}" url="#"/>
                        <x-adminlte-profile-col-item title="Estadio" text="{{$team->home_stadium}}" url="#"/>
                    </x-adminlte-profile-widget>
                </div>
                <div class="col-md-8">
                    <form action="{{route('admin.team.update', $team->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <x-adminlte-input name="name" value="{{$team->name}}" label="Nombre del Equipo" placeholder="Nombre del Equipo" fgroup-class="col-md-6" label-class="text-lightblue">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-users text-lightblue"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="coach" value="{{$team->coach}}" label="Entrenador" placeholder="Entrenador" fgroup-class="col-md-6" label-class="text-lightblue">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-user text-lightblue"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="row">
                            <x-adminlte-input-file name="logo" label="Logotipo del Equipo" fgroup-class="col-md-6">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-image text-lightblue"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input-file>
                            <x-adminlte-textarea name="description" label="Descripción" placeholder="Descripción del Equipo" fgroup-class="col-md-6" label-class="text-lightblue">{{ $team->description }}</x-adminlte-textarea>
                        </div>
                        <div class="row">
                            <x-adminlte-input name="home_stadium" value="{{$team->home_stadium}}" label="Estadio Local" placeholder="Estadio Local" fgroup-class="col-md-6" label-class="text-lightblue">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-building text-lightblue"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-select2 name="user_id" label="Usuario" fgroup-class="col-md-6" label-class="text-lightblue">
                                <option></option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ $team->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </x-adminlte-select2>
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

@push('js')
@endpush
