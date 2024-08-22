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
            $table->string('document')->nullable(); // Documento del padre o madre
            $table->string('neighborhood')->nullable(); // Barrio del padre o madre
            $table->string('parent_document_path')->nullable(); // Documento del padre o madre
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['document', 'neighborhood', 'parent_document_path']);
        });
    }
};
