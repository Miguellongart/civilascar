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
        Schema::table('childrens', function (Blueprint $table) {
            $table->enum('status', ['pendiente', 'activo', 'inactivo'])->after('child_document_path'); // Agregar campo status como enum
            $table->unique('document'); // Hacer el campo document único
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('childrens', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropUnique(['document']); // Quitar la restricción de único en document
        });
    }
};
