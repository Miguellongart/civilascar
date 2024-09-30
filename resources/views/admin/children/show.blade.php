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
                    <div class="row">
                        <x-adminlte-input name="name" value="{{ $child->name }}" label="Nombre del Niño" placeholder="Nombre del Niño" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-users text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <x-adminlte-input name="age" value="{{ $child->age }}" label="Edad" placeholder="Edad" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-user text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="uniform_size" value="{{ $child->uniform_size }}" label="Talla Uniforme" placeholder="Talla Uniforme" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-building text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <x-adminlte-input name="document" value="{{ $child->document }}" label="Documento" placeholder="Documento" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-building text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="birthdate" value="{{ $child->birthdate }}" label="Fecha nacimiento del Niño" placeholder="Fecha nacimiento del Niño" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-users text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                </div>
            </div>
            <div class="row">
                <h3>Representantes del Alumno.</h3>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h5>Representante Principal</h5>
                    <div class="row">
                        <x-adminlte-input name="name" value="{{ $child->parent->name }}" label="Nombre PAdre" placeholder="Nombre PAdre" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-users text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <x-adminlte-input name="document" value="{{ $child->parent->document }}" label="Documento" placeholder="Documento" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-user text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="phone" value="{{ $child->parent->phone }}" label="Telefono" placeholder="Telefono" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-user text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        @if($child->parent && $child->parent->parent_document_path)
                            <p>Documento del Padre:</p>
                            <img src="{{ Storage::url($child->parent->parent_document_path) }}" alt="Documento del Padre" width="200">
                        @else
                            <p>No se ha subido el documento del padre.</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <h5>Representante Secundario</h5>
                    <div class="row">
                        <x-adminlte-input name="name" value="{{ $child->parent->guardians[0]->name }}" label="Nombre seguno Representate" placeholder="Nombre seguno Representate" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-users text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <x-adminlte-input name="document" value="{{ $child->parent->guardians[0]->document }}" label="Documento Segundo Representate" placeholder="Documento Segundo Representate" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-user text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="relationship" value="{{ $child->parent->guardians[0]->relationship }}" label="Relacion" placeholder="Relacion" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-building text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                </div>
            </div>
        </x-adminlte-card>
    </x-adminlte-card>
@stop

@push('css')
@endpush

@push('js')
@endpush
