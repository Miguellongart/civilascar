@extends('layouts.app')

@section('subtitle', $subtitle)
@section('content_header_title', $content_header_title)
@section('content_header_subtitle', $content_header_subtitle)

@section('content')
    <x-adminlte-card theme="lime" theme-mode="outline">
        @if (session('success'))
            <x-adminlte-callout theme="success" title="Éxito">
                {{ session('success') }}
            </x-adminlte-callout>
        @endif
        @if (session('error'))
            <x-adminlte-callout theme="danger" title="Error">
                {{ session('error') }}
            </x-adminlte-callout>
        @endif
        <x-adminlte-card>
            <div class="mb-3">
                <a href="{{ route('admin.team.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Crear Nuevo Equipo
                </a>
                <a href="{{ route('admin.tournament.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-trophy"></i> Gestionar Torneos
                </a>
            </div>
            <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" striped hoverable bordered compressed beautify>
                @foreach($config['data'] as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td>{!! $cell !!}</td>
                        @endforeach
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
