<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroCambio;
use App\Models\Animal;

class RegistroCambioController extends Controller
{
    public function verHistorial($id)
    {
        $animal = Animal::findOrFail($id);
        $historial = RegistroCambio::where('id_animal', $id)
            ->with(['propietarioAnterior', 'propietarioNuevo']) // Cargar las relaciones
            ->orderBy('created_at', 'desc')
            ->get();

        return view('animal.historial_propietario', compact('animal', 'historial'));
    }
}
