<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enfermedad extends Model
{
    protected $table = 'enfermedad';
    protected $fillable=[
    'nombre',
    'fecha_ini',
    'caracterdisticas',
    'vacuna_name',
    'mascota_id',
    'tipo_enf_id'
    ];
    public $timestamps=false;
    public function animal()
    {
        return $this->belongsTo(Animal::class, 'mascota_id');
    }
    public function TipoEnfemedad()
    {
        return $this->belongsTo(Animal::class, 'tipo_enf_id');
    }
}
