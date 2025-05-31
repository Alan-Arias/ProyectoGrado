<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEnfermedad extends Model
{
    protected $table = 'tipo_enfermedad';
    protected $fillable=[
    'nombre',
    'especie_enf'
    ];
    public $timestamps=false;
    public function Enfermedad()
    {
        return $this->hasMany(Enfermedad::class, 'tipo_enf_id');
    }
}
