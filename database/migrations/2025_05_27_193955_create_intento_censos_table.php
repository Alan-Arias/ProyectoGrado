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
        Schema::create('intento_censo', function (Blueprint $table) {
            $table->id();
            $table->string('id_censista');
            $table->foreign('id_censista')->references('codigo_estudiante')->on('censista')->onDelete('cascade');
            $table->unsignedBigInteger('id_censo');
            $table->foreign('id_censo')->references('id')->on('censo')->onDelete('cascade');
            $table->string('fecha');
            $table->string('intentos_totales');
            $table->string('intentos_realizados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intento_censo');
    }
};
