@extends('layouts.app')

@section('subtitle', $subtitle)
@section('content_header_title', $content_header_title)
@section('content_header_subtitle', $content_header_subtitle)

@section('content')
    <x-adminlte-card theme="lime" theme-mode="outline">
        @if (session('success'))
            <x-adminlte-callout theme="success" title="Success">
                {{ session('success') }}
            </x-adminlte-callout>
        @endif
        <x-adminlte-card>
            <x-adminlte-datatable id="table2" :heads="['ID', 'Nombre del Niño', 'Edad','Talle de Uniforme', 'Documento','Documento del Niño', 'Acciones']">
                @foreach($children as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->age }}</td>
                        <td>{{ $item->uniform_size }}</td>
                        <td>{{ $item->document }}</td>
                        {{-- <td>{{ $item->parent->name }}</td> --}}
                        <td>
                            @if($item->child_document_path)
                                <a href="{{ asset('storage/' . $item->child_document_path) }}" target="_blank">Ver Documento</a>
                            @else
                                No Disponible
                            @endif
                        </td>
                        <td>
                            @canany('admin.littleSchool.show')
                                <a href="{{ route('admin.littleSchool.show', $item->id) }}" class="btn btn-xs btn-default text-teal mx-1 shadow" title="Ver Detalles">
                                    <i class="fa fa-lg fa-fw fa-eye"></i>
                                </a>
                            @endcanany
                            @canany('admin.littleSchool.edit')
                                <a href="{{ route('admin.littleSchool.edit', $item->id) }}" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </a>
                            @endcanany
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </x-adminlte-card>
    </x-adminlte-card>
@stop

@push('css')
@endpush

@push('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@endpush
