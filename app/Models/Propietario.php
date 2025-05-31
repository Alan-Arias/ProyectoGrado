<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Propietario extends Model
{
    protected $table = 'propietario';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['codigo', 'nombres', 'fecha_nac', 'direccion'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->codigo = self::generarCodigoUnico();
        });
    }

    public static function generarCodigoUnico()
    {
        do {
            $codigo = Str::upper(Str::random(10));
        } while (self::where('codigo', $codigo)->exists());

        return $codigo;
    }

    public function animales()
    {
        return $this->hasMany(Animal::class, 'codigo_propietario'); // Asegúrate de que 'propietario_codigo' sea el nombre correcto de la columna en la tabla 'animal'
    }
    public function CensoMascota()
    {
        return $this->hasOne(CensoMascota::class, 'propietario_id');
    }
    public function RegistroCambio1()
    {
        return $this->hasMany(RegistroCambio::class, 'codigo_propietario_anterior');
    }
    public function RegistroCambio2()
    {
        return $this->hasMany(RegistroCambio::class, 'codigo_propietario_nuevo');
    }
}
