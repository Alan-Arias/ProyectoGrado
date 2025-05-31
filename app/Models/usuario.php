<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class usuario extends Authenticatable
{
    use Notifiable;
    protected $table = 'usuario';
    protected $fillable=[
    'nombre',
    'email',
    'password',
    'personal_id',
    'nivel_acceso',
    'especialidad',
    'horario_trabajo',
    'anios_cargo',
    'tipo_user'
    ];
    
    public function setRememberToken($value) {
        
    }

    public function getRememberToken() {
        return null;
    }

    public function getRememberTokenName() {
        return null;
    }
    public function backup()
    {
        return $this->hasMany(Backup::class, 'usuario_id');
    }
    public function Personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }
}
