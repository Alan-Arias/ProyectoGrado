<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CensoMascota extends Model
{
    protected $table = 'censo_mascota';
    protected $fillable=[
    'censo_id',
    'animal_id',
    'propietario_id',
    'otb_id',
    'mascota_edad',
    'fecha'
    ];
    public $timestamps=false;

    public function Censo()
    {
        return $this->hasOne(CensoMascota::class, 'censo_id');
    }
    public function Animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }
    public function Propietario()
    {
        return $this->belongsTo(Animal::class, 'propietario_id');
    }
    public function Otb()
    {
        return $this->belongsTo(Otb::class, 'otb_id');
    }
}
