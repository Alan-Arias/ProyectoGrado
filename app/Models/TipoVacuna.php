<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoVacuna extends Model
{
    protected $table = 'tipo_vacuna';
    protected $fillable=[
    'nombre',
    'fabrica',
    'lote' 
    ];
    public $timestamps=false;
  
    public function Vacuna()
    {
        return $this->hasMany(Vacuna::class, 'tipo_vacuna_id');
    } 
}
