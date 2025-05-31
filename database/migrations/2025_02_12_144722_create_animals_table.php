<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animal', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 40)->nullable();
            $table->string('edad')->nullable();
            $table->string('color', 15)->nullable();
            $table->string('fecha_nac',30)->nullable();
            $table->string('castrado',2)->nullable();
            $table->string('estado', 20)->nullable();
            $table->text('foto_animal')->nullable();            
            $table->string('fecha_deceso')->nullable()->default('sin_datos');
            $table->string('motivo_deceso')->nullable()->default('sin_datos');
            $table->string('alergico')->nullable();

            $table->string('sexo')->nullable();
            $table->string('n_chip')->nullable()->default('no_tiene');
            $table->string('carnet_vacuna')->nullable();
            $table->string('ultima_vacuna')->nullable();            
            $table->string('censo_data')->nullable()->default('no');            
            $table->string('tipo_mascota')->nullable();
            
            $table->string('codigo_propietario');
            $table->foreign('codigo_propietario')->references('codigo')->on('propietario')->onDelete('cascade');
            $table->unsignedBigInteger('raza_id')->nullable(); //FK
            $table->foreign('raza_id')->references('id')->on('raza')->onDelete('cascade'); // Clave foránea
            $table->unsignedBigInteger('tipo_animal_id')->nullable(); //FK
            $table->foreign('tipo_animal_id')->references('id')->on('tipo_animal')->onDelete('cascade'); // Clave foránea
            $table->unsignedBigInteger('incapacidad_id')->nullable(); //FK
            $table->foreign('incapacidad_id')->references('id')->on('incapacidad')->onDelete('cascade'); // Clave foránea
            $table->unsignedBigInteger('forma_adq_id')->nullable(); //FK
            $table->foreign('forma_adq_id')->references('id')->on('forma_adquisicion')->onDelete('cascade'); // Clave foránea
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('animal');
    }
};
