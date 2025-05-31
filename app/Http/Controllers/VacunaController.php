<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vacuna;
use App\Models\DirectorGeneral;
use App\Models\Veterinario;
use App\Models\TipoVacuna;

class VacunaController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Debes iniciar sesión primero.');
            return redirect('/Login');
        }
        $usuario = Auth::user();
        if (!in_array($usuario->tipo_user, ['Director General', 'Veterinario'])) {
            session()->flash('error', 'Debes iniciar sesión como Director General o Veterinario para acceder a los registros.');
            return redirect()->back();
        }     
        $TipoVacuna = TipoVacuna::all();

        // Vacunas ordenadas por tipo_vacuna_id
        $Vacunas = Vacuna::orderBy('id', 'DESC')
            ->paginate(4)
            ->appends($request->all());

        return view('vacuna.vacuna', compact('Vacunas', 'TipoVacuna'));
    }

    public function indexTipo(Request $request)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Debes iniciar sesión primero.');
            return redirect('/Login');
        }
        $usuario = Auth::user();
        if (!in_array($usuario->tipo_user, ['Director General', 'Veterinario'])) {
            session()->flash('error', 'Debes iniciar sesión como Director General o Veterinario para acceder a los registros.');
            return redirect()->back();
        }
        $perPage = $request->input('perPage', 4);
        $TipoVacuna=TipoVacuna::paginate($perPage)->appends($request->all());        
        return view('vacuna.tipo_vacuna', compact('TipoVacuna'));
    }  
    public function storeTipo(Request $request)
    {
        $usuario = Auth::user();
        if ($usuario->tipo_user !== 'Director General') {
            session()->flash('error', 'Debes iniciar sesión como Director General para acceder a los registros.');
            return redirect()->back();
        }
        $TipoVacuna = new TipoVacuna();
        $TipoVacuna->nombre = $request->nombre;
        $TipoVacuna->fabrica = $request->fabrica;
        $TipoVacuna->nro_lote = $request->lote;        
        $TipoVacuna->save();
        return back()->with('agregar', 'Se ha registrado Correctamente');
    }
    public function store(Request $request)
    {
       $usuario = Auth::user();
        if ($usuario->tipo_user !== 'Director General') {
            session()->flash('error', 'Debes iniciar sesión como Director General para acceder a los registros.');
            return redirect()->back();
        }
        $Vacuna = new Vacuna();
        $Vacuna->nombre = $request->nombre;
        $Vacuna->tipo_vacuna_id = $request->tipo_vacuna_id;
        $Vacuna->save();
        
        return back()->with('agregar', 'Se ha registrado Correctamente');
    } 
    public function create(Request $request)
    {
        $tipoVacunaId = $request->input('tipo_vacuna_id');
        $tipoVacuna = TipoVacuna::findOrFail($tipoVacunaId);
        return view('vacuna.create_vacuna', compact('tipoVacuna'));
    }
    public function storeVacuna(Request $request)
    {
        Vacuna::create($request->only('nombre', 'tipo_vacuna_id'));
        return redirect('/Vacunas')->with('success', 'Vacuna añadida correctamente.');
    }    
   
}
