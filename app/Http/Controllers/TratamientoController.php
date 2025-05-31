<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tratamiento;
use App\Models\DirectorGeneral;
use App\Models\Veterinario;
use Illuminate\Support\Facades\Auth;
class TratamientoController extends Controller
{
    public function index()
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            session()->flash('error', 'Debes iniciar sesión primero.');
            return redirect('/Login'); // Redirigir sin abrir una nueva ventana
        }
    
        $usuario = Auth::user();
        if (!in_array($usuario->tipo_user, ['Director General', 'Veterinario'])) {
            session()->flash('error', 'Debes iniciar sesión como Director General o Veterinario para acceder a los registros.');
            return redirect()->back();
        }     
        $Tratamiento = Tratamiento::all();
        return view('tratamiento.tratamiento', compact('Tratamiento')); // Mostrar la vista
    }
    public function store(Request $request)
    {
        $usuario = Auth::user();
        if (!in_array($usuario->tipo_user, ['Director General'])) {
            session()->flash('error', 'Debes iniciar sesión como Director General o Veterinario para acceder a los registros.');
            return redirect()->back();
        }     
        $Tratamiento=new Tratamiento();
        $Tratamiento->producto=$request->name;
        $Tratamiento->Tiempo=$request->tiempo;
        $Tratamiento->Fecha=$request->datetime;
        $Tratamiento->Descripción=$request->descripcion;
        $Tratamiento->save();
        return back()->with('agregar', 'Se ha registrado Correctamente');
    }
}
