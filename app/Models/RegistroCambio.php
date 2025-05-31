<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroCambio extends Model
{
    protected $table = 'registro_cambio';
    protected $fillable = [
        'id',
        'id_usuario',
        'id_animal',
        'codigo_propietario_anterior',
        'codigo_propietario_nuevo',
        'created_at',
    ];
    public function propietarioAnterior()
    {
        return $this->belongsTo(Propietario::class, 'codigo_propietario_anterior', 'codigo');
    }

    public function propietarioNuevo()
    {
        return $this->belongsTo(Propietario::class, 'codigo_propietario_nuevo', 'codigo');
    }
    public function Animal()
    {
        return $this->belongsTo(Animal::class, 'id_animal');
    }
}
