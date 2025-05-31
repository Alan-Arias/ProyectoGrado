<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incapacidad extends Model
{
    protected $table = 'incapacidad';
    protected $fillable=[
    'descripcion'  
    ];
    public $timestamps=false;
    
    public function Animal()
    {
        return $this->hasOne(Animal::class, 'incapacidad_id');
    }
        
}
