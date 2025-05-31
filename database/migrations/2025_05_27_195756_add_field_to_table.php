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
        Schema::table('censo_mascota', function (Blueprint $table) {
            $table->string('id_censista');
            $table->foreign('id_censista')->references('codigo_estudiante')->on('censista')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('censo_mascota', function (Blueprint $table) {
            //
        });
    }
};
