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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nombre del equipo
            $table->string('slug')->unique();
            $table->string('coach')->nullable(); // Nombre del entrenador
            $table->string('logo')->nullable(); // Ruta al logotipo del equipo
            $table->text('description')->nullable(); // Descripción del equipo
            $table->string('home_stadium')->nullable(); // Estadio local del equipo
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
