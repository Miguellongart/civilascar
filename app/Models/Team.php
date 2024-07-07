<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'coach',
        'founded',
        'logo',
        'description',
        'home_stadium'
    ];

    public function players()
    {
        return $this->belongsToMany(Player::class, 'player_team_tournament')
                    ->withPivot('tournament_id')
                    ->withTimestamps();
    }

    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class, 'player_team_tournament')
                    ->withPivot('player_id')
                    ->withTimestamps();
    }
}
