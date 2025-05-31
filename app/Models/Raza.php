<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Raza extends Model
{
    protected $table = 'raza';
    protected $fillable=[
    'nombre',
    'especie_id'
    ];
    public $timestamps=false;

    public function especie()
    {
        return $this->belongsTo(Especie::class, 'especie_id');
    }
    public function animal()
    {
        return $this->hasMany(Animal::class, 'raza_id');
    }
}
