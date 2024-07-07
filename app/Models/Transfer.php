<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'from_team_id',
        'to_team_id',
        'tournament_id',
        'transfer_date',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function fromTeam()
    {
        return $this->belongsTo(Team::class, 'from_team_id');
    }

    public function toTeam()
    {
        return $this->belongsTo(Team::class, 'to_team_id');
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
