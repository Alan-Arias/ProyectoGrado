<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    protected $table = 'tratamiento';
    protected $fillable=[
    'producto',
    'Tiempo',
    'Fecha',
    'Descripcion'
    ];
    public $timestamps=false;
    
    public function HistorialSanitario()
    {
        return $this->hasMany(HistorialSanitario::class, 'tratamiento_id');
    }
}
