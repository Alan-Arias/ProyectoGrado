<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalVacuna extends Model
{
    protected $table = 'personal_vacuna';
    protected $fillable=[
    'nombre'
    ];
    public $timestamps=false;
    public function HistorialSanitario()
    {
        return $this->hasOne(HistorialSanitario::class, 'personal_vacuna_id');
    }
}
