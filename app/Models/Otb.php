<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otb extends Model
{
    protected $table = 'otb';
    protected $fillable=[
    'nombre_area'
    ];
    public $timestamps=false;
    public function CensoMascota()
    {
        return $this->hasOne(CensoMascota::class, 'otb_id');
    }
}
