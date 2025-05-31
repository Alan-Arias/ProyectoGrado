<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    protected $table = 'backups';
    protected $fillable=[
    'nombre_archivo',
    'ruta_archivo',
    'tipo_respaldo',
    'tamaño',
    'fecha_creacion',
    'usuario_id'   
    ];
    public $timestamps=false;
    public function usuario()
    {
        return $this->belongsTo(usuario::class, 'usuario_id');
    }
}
