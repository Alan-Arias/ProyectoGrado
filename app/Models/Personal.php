<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $table = 'personal';
    protected $fillable=[
    'nombre completo',
    'fecha_nacimiento',
    'edad'
    ];
    public $timestamps=false;
    public function usuario()
    {
        return $this->hasMany(usuario::class, 'personal_id');
    }
    public function usuariov2()
{
    return $this->hasOne(Usuario::class, 'personal_id');
}

}
