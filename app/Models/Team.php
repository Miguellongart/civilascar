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
        'logo',
        'description',
        'home_stadium',
        'user_id',
        'slug'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class, 'team_tournament')
                    ->withTimestamps();
    }

    public function tournamentPlayers($tournamentId)
    {
        return $this->belongsToMany(
                    Player::class,            // Modelo relacionado
                    'player_team_tournament', // Nombre de la tabla pivote
                    'team_id',                // Columna que relaciona el equipo en la tabla pivote
                    'player_id'               // Columna que relaciona al jugador en la tabla pivote
                )
                ->withPivot('tournament_id') // Para tener acceso al id del torneo en el pivot (opcional)
                ->wherePivot('tournament_id', $tournamentId); // Filtra por el torneo seleccionado
    }
}
