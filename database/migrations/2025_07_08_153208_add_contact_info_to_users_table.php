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
        Schema::table('users', function (Blueprint $table) {
            // Añadir columna para la fecha de nacimiento
            // Se coloca después de 'dni' para un orden lógico, ajusta si lo prefieres
            $table->date('date_of_birth')->nullable()->after('dni');

            // Añadir columna para el número de contacto
            // Se coloca después de 'date_of_birth'
            $table->string('phone_number')->nullable()->after('date_of_birth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar las columnas en caso de rollback
            $table->dropColumn('phone_number');
            $table->dropColumn('date_of_birth');
        });
    }
};
