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
        Schema::create('censista', function (Blueprint $table) {        
            $table->string('codigo_estudiante')->primary();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('codigo_carrera');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('censista');
    }
};
