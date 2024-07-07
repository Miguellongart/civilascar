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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade'); // Relación con el equipo
            $table->string('name'); // Nombre del jugador
            $table->string('position'); // Posición del jugador
            $table->integer('number'); // Número de camiseta
            $table->date('birth_date')->nullable(); // Fecha de nacimiento
            $table->string('nationality')->nullable(); // Nacionalidad
            $table->string('photo')->nullable(); // Ruta de la foto del jugador
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
