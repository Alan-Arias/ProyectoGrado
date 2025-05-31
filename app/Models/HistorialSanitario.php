<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialSanitario extends Model
{
    protected $table = 'historial_sanitario';
    protected $fillable=[
    'nombre_vacuna',
    'fecha_aplicacion',
    'animal_id',
    'vacuna_id',
    'tratamiento_id',
    'personal_vacuna_id'
    ];
    public $timestamps=false;

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }
    public function PersonalVacuna()
    {
        return $this->belongsTo(PersonalVacuna::class, 'personal_vacuna_id');
    }
    public function Vacuna()
    {
        return $this->belongsTo(Vacuna::class, 'vacuna_id');
    }
    public function Tratamiento()
    {
        return $this->belongsTo(Tratamiento::class, 'tratamiento_id');
    }
    
}
