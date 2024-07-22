<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'home_team_id',
        'away_team_id',
        'match_date',
        'status',
        'home_team_score',
        'away_team_score',
        'sport',
        'periods',
        'period_times'
    ];

    protected $casts = [
        'period_times' => 'array',
    ];

    public function playerEvents()
    {
        return $this->hasMany(PlayerFixtureEvent::class);
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }
}
