@extends('layouts.app')

@section('subtitle', $subtitle)
@section('content_header_title', $content_header_title)
@section('content_header_subtitle', $content_header_subtitle)

@section('content')
    <x-adminlte-card theme="lime" theme-mode="outline">
        <form action="{{ route('admin.team.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <x-adminlte-input name="name" label="Nombre del Equipo" placeholder="Nombre del Equipo" fgroup-class="col-md-6" required>
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-users text-lightblue"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
            <x-adminlte-input name="coach" label="Entrenador" placeholder="Entrenador" fgroup-class="col-md-6">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-user text-lightblue"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
            <x-adminlte-input-file name="logo" label="Logotipo del Equipo" fgroup-class="col-md-6">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-image text-lightblue"></i>
                    </div>
                </x-slot>
            </x-adminlte-input-file>
            <x-adminlte-textarea name="description" label="Descripción" placeholder="Descripción del Equipo" fgroup-class="col-md-6"/>
            <x-adminlte-input name="home_stadium" label="Estadio Local" placeholder="Estadio Local" fgroup-class="col-md-6">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-building text-lightblue"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
            <x-adminlte-select2 name="user_id" label="Usuario" fgroup-class="col-md-6">
                <option></option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </x-adminlte-select2>
            <x-adminlte-button class="btn-sm" type="submit" label="Crear Equipo" theme="outline-success" icon="fas fa-plus"/>
        </form>
    </x-adminlte-card>
@stop

@push('css')
@endpush

@push('js')
@endpush
