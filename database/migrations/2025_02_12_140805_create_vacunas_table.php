<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('vacuna', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('tipo_vacuna_id')->constrained('tipo_vacuna')->onDelete('cascade'); // Clave foránea            
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('vacuna');
    }
};
