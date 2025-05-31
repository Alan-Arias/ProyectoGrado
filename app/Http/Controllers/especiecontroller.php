<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\especiemodel;
use App\Models\Raza;
use App\Models\DirectorGeneral;

use Illuminate\Support\Facades\Auth;

class especiecontroller extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Debes iniciar sesión primero.');
            return redirect('/Login');
        }
        $usuario = Auth::user();
        if ($usuario->tipo_user !== 'Director General') {
            session()->flash('error', 'Debes iniciar sesión como Director General para acceder a los registros.');
            return redirect()->back();
        }
        $perPage = $request->input('perPage', 1);
        $Especies = especiemodel::paginate($perPage)->appends(['perPage' => $perPage]);
        $Raza = Raza::all();
        return view('especie.especie', compact('Especies', 'Raza'));
    }    

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'razas' => 'required|array|min:1',
            'razas.*' => 'required|string|max:255',
        ]);
        $especie = especiemodel::create(['nombre' => $request->name]);
        foreach ($request->razas as $razaNombre) {
            $especie->razas()->create(['nombre' => $razaNombre]);
        }
        return back()->with('agregar', 'Se ha guardado correctamente.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        // Actualizar la especie
        $especie = especiemodel::findOrFail($id);
        $especie->nombre = $request->nombre;
        $especie->save();

        return back()->with('actualizar', 'Especie actualizada correctamente.');
    }

    public function updateRaza(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        // Actualizar la raza
        $raza = Raza::findOrFail($id);
        $raza->nombre = $request->nombre;
        $raza->save();

        return back()->with('actualizar', 'Raza actualizada correctamente.');
    }
    public function registrarRaza(Request $request)
    {
        $raza = new Raza();
        $raza->nombre = $request->nombre;
        $raza->especie_id = $request->especie_id;
        $raza->save();

        return redirect()->back()->with('agregar', 'Raza agregada con éxito.');
    }
    public function mostrarAgregarRaza($id)
    {
        $especie = especiemodel::with('razas')->findOrFail($id);
        return view('especie.AgregarRaza', compact('especie'));
    }
    public function CensomostrarAgregarRaza($id)
    {
        $especie = especiemodel::with('razas')->findOrFail($id);
        return view('especie.censo_AgregarRaza', compact('especie'));
    }
}
