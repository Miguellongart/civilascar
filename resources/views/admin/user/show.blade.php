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
                    <x-adminlte-profile-widget name="{{$user->name}}" desc="{{$user->email}}" theme="teal" img="https://picsum.photos/id/1/100">
                        <x-adminlte-profile-col-item title="DNI" text="{{$user->dni}}" url="#"/>
                        <x-adminlte-profile-col-item title="Following" text="243" url="#"/>
                        <x-adminlte-profile-col-item title="Posts" text="37" url="#"/>
                    </x-adminlte-profile-widget>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <x-adminlte-input name="name" value="{{$user->name}}" label="Nombre" placeholder="Nombre" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-user text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <x-adminlte-input name="dni" value="{{$user->dni}}" label="DNI" placeholder="DNI" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-user text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="email" value="{{$user->email}}" label="Correo" placeholder="Correo" fgroup-class="col-md-6" label-class="text-lightblue" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-user text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                </div>
            </div>

            <div class="row">

                @if (auth()->user()->can('admin.user.edit') ||
                    auth()->user()->can('admin.assign.roles') ||
                    auth()->user()->can('admin.assign.permissions')
                )
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                @can(['admin.user.edit'])
                                <li class="nav-item"><a class="nav-link active" href="#actualizar" data-toggle="tab">Actualizar</a></li>
                                @endcan
                                @can(['admin.assign.roles'])
                                <li class="nav-item"><a class="nav-link" href="#roles" data-toggle="tab">Roles</a></li>
                                @endcan
                                @can('admin.assign.permissions')
                                <li class="nav-item"><a class="nav-link" href="#permisos" data-toggle="tab">Permisos</a></li>
                                @endcan
                            </ul>
                        </div><!-- /.card-header -->

                        <div class="card-body">
                            <div class="tab-content">
                                @can(['admin.user.edit'])
                                <div class="active tab-pane" id="actualizar">
                                    <form action="{{route('admin.user.update', $user->id )}}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="row">
                                            <div class="form-group col-6 col-md-6 col-sm-12">
                                                <label for="name">Nombre</label>
                                                <input type="text" class="form-control" name="name" value="{{old('name',$user->name)}}" id="name">
                                            </div>
                                            <div class="form-group col-6 col-md-6 col-sm-12">
                                                <label for="dni">DNI</label>
                                                <input type="text" class="form-control" name="dni" value="{{old('dni',$user->dni)}}" id="dni"  ondrop="return false;" onpaste="return false;" onkeypress="return event.charCode>=48 && event.charCode<=57">
                                            </div>
                                            <div class="form-group col-6 col-md-6 col-sm-12">
                                                <label for="email">Email</label>
                                                <input type="text" class="form-control" name="email" value="{{old('email',$user->email)}}" id="email">
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-success" type="submit">Guardar</button>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                                @endcan
                                @can(['admin.assign.roles'])
                                <div class="tab-pane" id="roles">
                                    <form action="{{ route('user.role', $user->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            @foreach($roles as $role)
                                                <div class="form-check">
                                                    <input class="form-check-input"
                                                           name="roles[]"
                                                           @if($user->hasRole($role->id)) checked  @endif
                                                           value="{{old('roles',$role->id)}}"
                                                           type="checkbox">

                                                    <label class="form-check-label">{{$role->name}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary">Actualizar</button>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                                @endcan
                                @can('admin.assign.permissions')
                                <div class="tab-pane" id="permisos">
                                    <form action="{{ route('user.permission', $user->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            @foreach($permissions as $p)
                                                <div class="form-check">
                                                    <input class="form-check-input" name="permissions[]" 
                                                        @if($user->hasPermissionTo($p->id)) checked @endif 
                                                        value="{{old('permission',$p->id)}}" type="checkbox">
                                                    <label class="form-check-label">{{$p->description}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary">Actualizar</button>
                                    </form>
                                </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </x-adminlte-card>
    </x-adminlte-card>
@stop

{{-- Push extra CSS --}}
@push('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}
@push('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@endpush
