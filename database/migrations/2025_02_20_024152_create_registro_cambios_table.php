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
        Schema::create('registro_cambio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuario')->onDelete('cascade');
            $table->foreignId('id_animal')->constrained('animal')->onDelete('cascade');
            $table->string('codigo_propietario_anterior');
            $table->string('codigo_propietario_nuevo');
            $table->foreign('codigo_propietario_anterior')->references('codigo')->on('propietario')->onDelete('set null');
            $table->foreign('codigo_propietario_nuevo')->references('codigo')->on('propietario')->onDelete('set null');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_cambio');
    }
};
