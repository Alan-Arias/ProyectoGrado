<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Censo extends Model
{
    protected $table = 'censo';
    protected $fillable=[
    'gestion',
    'fecha_inicio',
    'fecha_fin',
    'coordinador'   
    ];
    public $timestamps=false;
    public function CensoMascota()
    {
        return $this->hasOne(CensoMascota::class, 'censo_id');
    }
}
