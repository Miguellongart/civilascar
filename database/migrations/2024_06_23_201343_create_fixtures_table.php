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
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade'); // Relación con el torneo
            $table->foreignId('home_team_id')->constrained('teams')->onDelete('cascade'); // Equipo local
            $table->foreignId('away_team_id')->constrained('teams')->onDelete('cascade'); // Equipo visitante
            $table->dateTime('match_date'); // Fecha y hora del partido
            $table->enum('status', ['scheduled', 'completed', 'canceled'])->default('scheduled'); // Estado del partido
            $table->integer('home_team_score')->nullable(); // Marcador del equipo local
            $table->integer('away_team_score')->nullable(); // Marcador del equipo visitante
            $table->enum('sport', ['football', 'basketball', 'futsal', 'padel'])->default('football'); // Deporte
            $table->enum('periods', ['halves', 'quarters', 'periods'])->default('halves'); // Tiempos de juego
            $table->json('period_times')->nullable(); // Tiempos de cada período
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};
