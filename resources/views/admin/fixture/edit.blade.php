@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', $subtitle)
@section('content_header_title', $content_header_title)
@section('content_header_subtitle', $content_header_subtitle)

@section('content_body')
    <x-adminlte-card theme="lime" theme-mode="outline">
        <x-adminlte-card>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <h1>Edit Fixture</h1>
                    <form action="{{ route('admin.fixture.update', $fixture->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Campos para editar el fixture -->
                        <div class="form-group">
                            <label for="match_date">Match Date</label>
                            <input type="datetime-local" class="form-control" id="match_date" name="match_date" value="{{ $fixture->match_date }}">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="scheduled" {{ $fixture->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="completed" {{ $fixture->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="canceled" {{ $fixture->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="home_team_score">Home Team {{ $fixture->homeTeam->name }}</label>
                            <input type="number" class="form-control" id="home_team_score" name="home_team_score" value="{{ $fixture->home_team_score }}">
                        </div>
                        <div class="form-group">
                            <label for="away_team_score">Away Team {{ $fixture->awayTeam->name }}</label>
                            <input type="number" class="form-control" id="away_team_score" name="away_team_score" value="{{ $fixture->away_team_score }}">
                        </div>

                        <!-- Nuevo campo para marcar si el partido fue ganado por mesa -->
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="won_by_forfeit" name="won_by_forfeit" value="1" {{ $fixture->won_by_forfeit ? 'checked' : '' }}>
                            <label class="form-check-label" for="won_by_forfeit">Partido ganado por mesa</label>
                        </div>

                        <div class="row">
                            <!-- Equipo Local -->
                            <div class="col-md-6">
                                <h3>Home Team {{ $fixture->homeTeam->name }}</h3>
                            
                                @foreach($homeTeamPlayers as $player)
                                    <div class="form-group">
                                        <label>{{ $player->number }} - {{ $player->user->name }}</label>
                                        <input type="hidden" name="player_events[{{ $player->id }}][player_id]" value="{{ $player->id }}">
                                        
                                        <div class="row">
                                            <!-- Goles -->
                                            <div class="col-md-4">
                                                <label for="goals_{{ $player->id }}">Goals</label>
                                                <input type="number" class="form-control" id="goals_{{ $player->id }}" name="player_events[{{ $player->id }}][goals]"
                                                    value="{{ $playerEvents[$player->id]['goal']->quantity ?? 0 }}">
                                            </div>
                                            
                                            <!-- Tarjetas Amarillas -->
                                            <div class="col-md-4">
                                                <label for="yellow_cards_{{ $player->id }}">Yellow Cards</label>
                                                <input type="number" class="form-control" id="yellow_cards_{{ $player->id }}" name="player_events[{{ $player->id }}][yellow_cards]"
                                                    value="{{ $playerEvents[$player->id]['yellow_card']->quantity ?? 0 }}">
                                            </div>
                                            
                                            <!-- Tarjetas Rojas -->
                                            <div class="col-md-4">
                                                <label for="red_cards_{{ $player->id }}">Red Cards</label>
                                                <input type="number" class="form-control" id="red_cards_{{ $player->id }}" name="player_events[{{ $player->id }}][red_cards]"
                                                    value="{{ $playerEvents[$player->id]['red_card']->quantity ?? 0 }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        
                            <!-- Equipo Visitante -->
                            <div class="col-md-6">
                                <h3>Away Team {{ $fixture->awayTeam->name }}</h3>
                            
                                @foreach($awayTeamPlayers as $player)
                                    <div class="form-group">
                                        <label>{{ $player->number }} - {{ $player->user->name }}</label>
                                        <input type="hidden" name="player_events[{{ $player->id }}][player_id]" value="{{ $player->id }}">
                                        
                                        <div class="row">
                                            <!-- Goles -->
                                            <div class="col-md-4">
                                                <label for="goals_{{ $player->id }}">Goals</label>
                                                <input type="number" class="form-control" id="goals_{{ $player->id }}" name="player_events[{{ $player->id }}][goals]"
                                                    value="{{ $playerEvents[$player->id]['goal']->quantity ?? 0 }}">
                                            </div>
                                            
                                            <!-- Tarjetas Amarillas -->
                                            <div class="col-md-4">
                                                <label for="yellow_cards_{{ $player->id }}">Yellow Cards</label>
                                                <input type="number" class="form-control" id="yellow_cards_{{ $player->id }}" name="player_events[{{ $player->id }}][yellow_cards]"
                                                    value="{{ $playerEvents[$player->id]['yellow_card']->quantity ?? 0 }}">
                                            </div>
                                            
                                            <!-- Tarjetas Rojas -->
                                            <div class="col-md-4">
                                                <label for="red_cards_{{ $player->id }}">Red Cards</label>
                                                <input type="number" class="form-control" id="red_cards_{{ $player->id }}" name="player_events[{{ $player->id }}][red_cards]"
                                                    value="{{ $playerEvents[$player->id]['red_card']->quantity ?? 0 }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </x-adminlte-card>
        
    </x-adminlte-card>
@stop

@push('css')
@endpush

@push('js')
{{-- <script>
    $(document).ready(function() {
        $('#add-event-button').click(function() {
            var eventId = new Date().getTime(); // Use timestamp as a unique ID
            var eventHtml = `
                <div class="form-group" id="event_${eventId}">
                    <label for="player_${eventId}">Player</label>
                    <select class="form-control" id="player_${eventId}" name="player_events[new_${eventId}][player_id]">
                        @foreach($players as $player)
                            <option value="{{ $player->id }}">{{ $player->name }}</option>
                        @endforeach
                    </select>
                    <label for="event_type_${eventId}">Event Type</label>
                    <select class="form-control" id="event_type_${eventId}" name="player_events[new_${eventId}][event_type]">
                        <option value="yellow_card">Yellow Card</option>
                        <option value="red_card">Red Card</option>
                        <option value="substitution_in">Substitution In</option>
                        <option value="substitution_out">Substitution Out</option>
                        <option value="goal">Goal</option>
                        <option value="assist">Assist</option>
                    </select>
                    <label for="minute_${eventId}">Minute</label>
                    <input type="number" class="form-control" id="minute_${eventId}" name="player_events[new_${eventId}][minute]" value="">
                    <label for="comment_${eventId}">Comment</label>
                    <input type="text" class="form-control" id="comment_${eventId}" name="player_events[new_${eventId}][comment]" value="">
                    <button type="button" class="btn btn-danger mt-2 remove-event-button" data-event-id="${eventId}">Remove</button>
                </div>
            `;
            $('#player-events-container').append(eventHtml);
        });

        $(document).on('click', '.remove-event-button', function() {
            var eventId = $(this).data('event-id');
            $('#event_' + eventId).remove();
        });
    });
</script> --}}
@endpush
