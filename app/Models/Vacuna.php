<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacuna extends Model
{
    protected $table = 'vacuna';
    protected $fillable=[
    'nombre',
    'tipo_vacuna_id'  
    ];
    public $timestamps=false;
    
    public function TipoVacuna()
    {
        return $this->belongsTo(TipoVacuna::class, 'tipo_vacuna_id');
    }
    public function HistorialSanitario()
    {
        return $this->hasMany(HistorialSanitario::class, 'vacuna_id');
    }
    
}
