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
        Schema::table('players', function (Blueprint $table) {
            // Añadir columna para la ruta de la foto del jugador
            // Se coloca después de la columna 'photo' existente, o donde prefieras
            $table->string('player_photo_path')->nullable()->after('photo');

            // Añadir columna para la ruta de la foto del documento
            $table->string('document_photo_path')->nullable()->after('player_photo_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            // Eliminar las columnas en caso de rollback
            $table->dropColumn('document_photo_path');
            $table->dropColumn('player_photo_path');
            //
        });
    }
};
