<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('propietario', function (Blueprint $table) {
            $table->string('codigo',10)->primary();
            $table->string('nombres',50);
            $table->string('carnet_nro')->nullable();
            $table->string('telefono_nro')->nullable();
            $table->string('fecha_nac',50);
            $table->string('direccion',50);
            $table->text('foto_fachada');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('propietario');
    }
};
