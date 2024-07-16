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
        'photo',
        'team_id',
        'user_id'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
