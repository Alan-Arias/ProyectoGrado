<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class especiemodel extends Model
{
    protected $table = 'especie';
    protected $fillable=[
    'nombre'   
    ];
    public $timestamps=false;

    public function razas()
    {
        return $this->hasMany(Raza::class, 'especie_id');
    }
}   
