<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'max_teams',
        'min_teams',
        'description',
        'start_date',
        'end_date',
        'location',
        'status',
        'rules',
        'prizes',
        'logo'
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'player_team_tournament')
                    ->withPivot('player_id')
                    ->withTimestamps();
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'player_team_tournament')
                    ->withPivot('team_id')
                    ->withTimestamps();
    }
}
