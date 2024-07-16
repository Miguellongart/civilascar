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
        'logo',
        'limit_teams'
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_tournament')
                    ->withTimestamps();
    }

    public function fixtures()
    {
        return $this->hasMany(Fixture::class);
    }

    public function positionTables()
    {
        return $this->hasMany(PositionTable::class);
    }
}
