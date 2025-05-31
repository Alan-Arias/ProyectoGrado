<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaAdquisicion extends Model
{
    protected $table = 'forma_adquisicion';
    protected $fillable=[
    'descripcion'
    ];
    public $timestamps=false;
    
    public function Animal()
    {
        return $this->hasOne(Animal::class, 'forma_adq_id');
    }   
}
