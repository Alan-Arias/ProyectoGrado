<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Censista extends Model
{
    protected $table = 'censista';
    protected $primaryKey = 'codigo_estudiante';
    protected $fillable=[
    'codigo_estudiante',
    'nombre',
    'apellido',
    'codigo_carrera'
    ];
}
