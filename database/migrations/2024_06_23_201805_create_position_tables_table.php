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
        Schema::create('position_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade'); // ID del torneo
            $table->foreignId('team_id')->constrained()->onDelete('cascade'); // ID del equipo
            $table->integer('played')->default(0); // Partidos jugados
            $table->integer('won')->default(0); // Partidos ganados
            $table->integer('drawn')->default(0); // Partidos empatados
            $table->integer('lost')->default(0); // Partidos perdidos
            $table->integer('goals_for')->default(0); // Goles a favor
            $table->integer('goals_against')->default(0); // Goles en contra
            $table->integer('goal_difference')->default(0); // Diferencia de goles
            $table->integer('points')->default(0); // Puntos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_tables');
    }
};
