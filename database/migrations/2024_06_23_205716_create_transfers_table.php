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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade'); // Relación con el jugador
            $table->foreignId('from_team_id')->nullable()->constrained('teams')->onDelete('cascade'); // Equipo de origen
            $table->foreignId('to_team_id')->constrained('teams')->onDelete('cascade'); // Equipo de destino
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade'); // Relación con el torneo
            $table->date('transfer_date'); // Fecha de la transferencia
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
