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
            <form action="{{ route('admin.fixture.index', $tournament->id) }}" method="GET" class="form-inline">
                <div class="form-group mb-2">
                    <label for="date" class="sr-only">Fecha</label>
                    <select class="form-control" id="date" name="date">
                        <option value="">Selecciona una fecha</option>
                        @foreach($dates as $date)
                            <option value="{{ $date }}" {{ request('date') == $date ? 'selected' : '' }}>{{ $date }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mb-2">Filtrar</button>
            </form>


            <a href="{{ route('admin.fixture.createFixture', $tournament->id) }}" class="btn-sm btn-success">
                <i class="fas fa-plus"></i> Crear
            </a>
            <x-adminlte-datatable id="table2" :heads="['ID', 'Home Team', 'Home Goal','Away Goal', 'Away Team', 'Match Date','Estado', 'Acciones']">
                @foreach($fixtures as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->homeTeam->name }}</td>
                        <td>{{ $item->home_team_score }}</td>
                        <td>{{ $item->away_team_score }}</td>
                        <td>{{ $item->awayTeam->name }}</td>
                        <td>{{ $item->match_date }}</td>
                        <td>{{ $item->status }}</td>
                        <td>
                            @canany('admin.fixture.show')
                                <a href="{{ route('admin.fixture.show', $item->id) }}" class="btn btn-xs btn-default text-teal mx-1 shadow" title="Ver Detalles">
                                    <i class="fa fa-lg fa-fw fa-eye"></i>
                                </a>
                            @endcanany
                            @canany('admin.fixture.edit')
                                <a href="{{ route('admin.fixture.edit', $item->id) }}" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </a>
                            @endcanany
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
            @foreach($fixtures as $item)
                <div class="col-md-4">
                    {{-- <x-adminlte-profile-widget name="{{ $item->name }}" desc="{{ $item->description }}" theme="teal" img="{{ asset('storage/' . $item->logo) }}">
                        <x-adminlte-profile-col-item title="Entrenador" text="{{ $item->coach }}" url="#"/>
                        <x-adminlte-profile-col-item title="Estadio" text="{{ $item->home_stadium }}" url="#"/>
                    </x-adminlte-profile-widget> --}}
                </div>
            @endforeach
        </x-adminlte-card>
    </x-adminlte-card>
@stop

@push('css')
@endpush

@push('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@endpush
