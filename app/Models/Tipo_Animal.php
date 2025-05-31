<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo_Animal extends Model
{
    protected $table = 'tipo_animal';
    protected $fillable=[
    'nombre'
    ];
    public $timestamps=false;
    public function Animal()
    {
        return $this->hasOne(Animal::class, 'tipo_animal_id');
    }
}
