<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_sanitario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_vacuna');
            $table->string('fecha_aplicacion', 10);
            $table->unsignedBigInteger('animal_id');
            $table->foreign('animal_id')->references('id')->on('animal')->onDelete('cascade');
            $table->unsignedBigInteger('vacuna_id');
            $table->foreign('vacuna_id')->references('id')->on('vacuna')->onDelete('cascade');
            $table->unsignedBigInteger('tratamiento_id');
            $table->foreign('tratamiento_id')->references('id')->on('tratamiento')->onDelete('cascade');
            $table->unsignedBigInteger('personal_vacuna_id');
            $table->foreign('personal_vacuna_id')->references('id')->on('personal_vacuna')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_sanitario');
    }
};
