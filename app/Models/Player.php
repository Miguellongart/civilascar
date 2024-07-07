<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'number',
        'birth_date',
        'nationality',
        'photo'
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'player_team_tournament')
                    ->withPivot('tournament_id')
                    ->withTimestamps();
    }

    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class, 'player_team_tournament')
                    ->withPivot('team_id')
                    ->withTimestamps();
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }
}
