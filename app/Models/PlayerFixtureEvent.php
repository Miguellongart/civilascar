<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerFixtureEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'fixture_id',
        'player_id',
        'event_type',
        'minute',
        'comment',
        'quantity'  // Agregar este campo
    ];
    

    public function fixture()
    {
        return $this->belongsTo(Fixture::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
