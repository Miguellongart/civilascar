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
                            <label for="home_team_score">Home Team Score</label>
                            <input type="number" class="form-control" id="home_team_score" name="home_team_score" value="{{ $fixture->home_team_score }}">
                        </div>
                        <div class="form-group">
                            <label for="away_team_score">Away Team Score</label>
                            <input type="number" class="form-control" id="away_team_score" name="away_team_score" value="{{ $fixture->away_team_score }}">
                        </div>
                        
                        <!-- Campos para actualizar eventos de los jugadores -->
                        <h2>Player Events</h2>
                        <div id="player-events-container">
                            @foreach($fixture->playerEvents as $event)
                                <div class="form-group" id="event_{{ $event->id }}">
                                    <label for="player_{{ $event->id }}">Player</label>
                                    <select class="form-control" id="player_{{ $event->id }}" name="player_events[{{ $event->id }}][player_id]">
                                        {{dd($players)}}
                                        @foreach($players as $player)
                                            <option value="{{ $player->id }}" {{ $event->player_id == $player->id ? 'selected' : '' }}>{{ $player->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="event_type_{{ $event->id }}">Event Type</label>
                                    <select class="form-control" id="event_type_{{ $event->id }}" name="player_events[{{ $event->id }}][event_type]">
                                        <option value="yellow_card" {{ $event->event_type == 'yellow_card' ? 'selected' : '' }}>Yellow Card</option>
                                        <option value="red_card" {{ $event->event_type == 'red_card' ? 'selected' : '' }}>Red Card</option>
                                        <option value="substitution_in" {{ $event->event_type == 'substitution_in' ? 'selected' : '' }}>Substitution In</option>
                                        <option value="substitution_out" {{ $event->event_type == 'substitution_out' ? 'selected' : '' }}>Substitution Out</option>
                                        <option value="goal" {{ $event->event_type == 'goal' ? 'selected' : '' }}>Goal</option>
                                        <option value="assist" {{ $event->event_type == 'assist' ? 'selected' : '' }}>Assist</option>
                                    </select>
                                    <label for="minute_{{ $event->id }}">Minute</label>
                                    <input type="number" class="form-control" id="minute_{{ $event->id }}" name="player_events[{{ $event->id }}][minute]" value="{{ $event->minute }}">
                                    <label for="comment_{{ $event->id }}">Comment</label>
                                    <input type="text" class="form-control" id="comment_{{ $event->id }}" name="player_events[{{ $event->id }}][comment]" value="{{ $event->comment }}">
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-secondary" id="add-event-button">Add Event</button>
                
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
<script>
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
</script>
@endpush
