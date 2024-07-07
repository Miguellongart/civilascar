<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('player_team_tournament', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade'); // Relación con el jugador
            $table->foreignId('team_id')->constrained()->onDelete('cascade'); // Relación con el equipo
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade'); // Relación con el torneo            
            // Garantiza que un jugador no pueda jugar para diferentes equipos en el mismo torneo
            $table->unique(['player_id', 'tournament_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_team_tournament');
    }
};
