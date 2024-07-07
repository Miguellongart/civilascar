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
        Schema::create('player_fixture_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fixture_id')->constrained()->onDelete('cascade'); // Relación con el fixture
            $table->foreignId('player_id')->constrained()->onDelete('cascade'); // Relación con el jugador
            $table->enum('event_type', ['yellow_card', 'red_card', 'substitution_in', 'substitution_out', 'goal', 'assist'])->default('yellow_card'); // Tipo de evento
            $table->integer('minute'); // Minuto del evento
            $table->string('comment')->nullable(); // Comentario adicional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_fixture_events');
    }
};
