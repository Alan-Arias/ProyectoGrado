<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $table = 'animal';
    protected $fillable=[
    'nombre',
    'edad',
    'color',
    'fecha_nac',
    'castrado',
    'estado',
    'tipo_animal',
    'foto_animal',
    'fecha_deceso',
    'motivo_deceso',
    'alergico',
    'sexo',
    'n_chip',
    'carnet_vacuna',
    'ultima_vacuna',
    'censo_data',
    'tipo_mascota',
    'codigo_propietario',
    'raza_id',
    'tipo_animal_id',
    'incapacidad_id',
    'forma_adq_id'
    ];
    public $timestamps=false;

    public function propietario()
    {
        return $this->belongsTo(Propietario::class, 'codigo_propietario');
    }
    public function historial_saniario()
    {
        return $this->hasMany(HistorialSanitario::class, 'animal_id');
    }
    public function forma_adquisicion()
    {
        return $this->hasOne(Animal::class, 'animal_id');
    }
    public function raza()
    {
        return $this->belongsTo(Raza::class, 'raza_id');
    } 
    public function Enfermedad()
    {
        return $this->hasMany(Enfermedad::class, 'mascota_id');
    }
    public function TipoAnimal()
    {
        return $this->belongsTo(Tipo_Animal::class, 'tipo_animal_id');
    }
    public function Incapacidad()
    {
        return $this->belongsTo(Incapacidad::class, 'incapacidad_id');
    }
    public function FormaAdquisicion()
    {
        return $this->belongsTo(FormaAdquisicion::class, 'forma_adq_id');
    }
    public function CensoMascota()
    {
        return $this->hasOne(CensoMascota::class, 'animal_id');
    }
    public function RegistroCambio()
    {
        return $this->hasMany(RegistroCambio::class, 'id_animal');
    }
    public function ultimoCambio()
    {
        return $this->hasOne(RegistroCambio::class, 'id_animal')->latestOfMany();
    }
}
