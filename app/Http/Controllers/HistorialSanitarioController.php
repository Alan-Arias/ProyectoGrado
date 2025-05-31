<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Propietario;
use App\Models\HistorialSanitario;
use App\Models\PersonalVacuna;
use App\Models\TipoVacuna;
use App\Models\especiemodel;
use App\Models\DirectorGeneral;
use App\Models\Veterinario;
use App\Models\Tratamiento;
use Illuminate\Support\Facades\Auth;
use App\Models\Vacuna;

class HistorialSanitarioController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Debes iniciar sesión primero.');
            return redirect('/Login');
        }
        $usuario = Auth::user();
        if (!in_array($usuario->tipo_user, ['Director General', 'Veterinario'])) {
            session()->flash('error', 'Debes iniciar sesión como Director General o Administrador para acceder a los registros.');
            return redirect()->back();
        }
        $Animales = Animal::all();
        $TipoVacuna = TipoVacuna::all();
        $Vacunas = Vacuna::with('TipoVacuna')->get()->map(function($vacuna){
            return [
                'id' => $vacuna->id,
                'nombre' => $vacuna->nombre,
                'TipoVacuna' => $vacuna->TipoVacuna ? ['id' => $vacuna->TipoVacuna->id, 'nombre' => $vacuna->TipoVacuna->nombre] : null,
                // otros campos si tienes
            ];
        });
        $Propietarios = Propietario::all();
        $perPage = $request->input('perPage', 3);
        $Historial = HistorialSanitario::paginate($perPage);
        $PersonalVacuna = PersonalVacuna::all();
        $Especies = especiemodel::all();
        $Tratamiento = Tratamiento::all();
        return view('historial_s.historial', compact('Animales', 'TipoVacuna', 'Vacunas', 'Propietarios', 'Historial', 'Especies', 'PersonalVacuna', 'Tratamiento'));
    }        
    public function store(Request $request)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Debes iniciar sesión primero.');
            return redirect('/Login');
        }
        $usuario = Auth::user();
        if (!in_array($usuario->tipo_user, ['Director General'])) {
            session()->flash('error', 'Debes iniciar sesión como Director General o Administrador para acceder a los registros.');
            return redirect()->back();
        }
         else {            

            $PersonalVacuna = new PersonalVacuna();
            $PersonalVacuna->nombre = $request->personal;            
            $PersonalVacuna->save();
            
            $Historial = new HistorialSanitario();
            $Historial->nombre_vacuna = $request->vname;
            $Historial->fecha_aplicacion = $request->date;
            $Historial->animal_id = $request->name_id;
            $Historial->vacuna_id = $request->vname_id;
            $Historial->tratamiento_id = $request->tname_id;
            $Historial->personal_vacuna_id=$PersonalVacuna->id;
            $Historial->save();
    
            return back()->with('agregar', 'Se ha registrado Correctamente');
        }
    }    
    public function show($id) {
        $animal = Animal::findOrFail($id);
        $Vacunas = Vacuna::all();
        $historial = HistorialSanitario::where('animal_id', $id)->get();
        return view('historial_s.show', compact('animal', 'historial', 'Vacunas'));
    }
    
}
