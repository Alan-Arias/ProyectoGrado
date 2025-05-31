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
        Schema::create('enfermedad', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('fecha_ini');
            $table->string('caracteristicas');
            $table->string('vacuna_name')->nullable()->default('sin_datos');  
            $table->unsignedBigInteger('mascota_id');
            $table->foreign('mascota_id')->references('id')->on('animal')->onDelete('cascade');            
            $table->unsignedBigInteger('tipo_enf_id')->nullable();            
            $table->foreign('tipo_enf_id')->references('id')->on('tipo_enfermedad')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enfermedad');
    }
};
