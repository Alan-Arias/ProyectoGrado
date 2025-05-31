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
        Schema::create('censo_mascota', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('censo_id');
            $table->foreign('censo_id')->references('id')->on('censo')->onDelete('cascade');            

            $table->unsignedBigInteger('animal_id');
            $table->foreign('animal_id')->references('id')->on('animal')->onDelete('cascade');            

            $table->string('propietario_id');
            $table->foreign('propietario_id')->references('codigo')->on('propietario')->onDelete('cascade');            

            $table->unsignedBigInteger('otb_id');
            $table->foreign('otb_id')->references('id')->on('otb')->onDelete('cascade'); 
                       
            $table->string('mascota_edad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('censo_mascota');
    }
};
