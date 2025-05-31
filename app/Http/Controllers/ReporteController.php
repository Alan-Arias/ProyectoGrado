<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReporteController extends Controller
{
    public function ReporteIndex()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Debes iniciar sesión primero.');
            return redirect('/Login');
        }

        $usuario = Auth::user();
        if (!in_array($usuario->tipo_user, ['Director General', 'Veterinario'])) {
            session()->flash('error', 'Acceso denegado. Solo Director General o Veterinario.');
            return redirect()->back();
        }

        $animal = Animal::all();
        $totalAnimales = $animal->count();
        $totalCastrados = $animal->where('castrado', 'si')->count();
        $totalMachos = $animal->where('sexo', 'macho')->count();
        $totalHembras = $animal->where('sexo', 'hembra')->count();

        $animales = collect(); // lista vacía al inicio

        return view('reportes.rp_buscar_animal', compact(
            'animales', 'totalAnimales', 'totalCastrados', 'totalMachos', 'totalHembras'
        ));
    }

    private function extraerEdadEnAnios($edadTexto)
    {
        if (preg_match('/(\d+)\s*años?/', $edadTexto, $match)) {
            return (int) $match[1];
        }
        return 0;
    }

    public function reporteAnimal(Request $request)
    {
        $query = $request->input('query');
        $edadMinAnios = $request->input('edad_min');
        $edadMaxAnios = $request->input('edad_max');

        $animal = Animal::all();
        $totalAnimales = $animal->count();
        $totalCastrados = $animal->where('castrado', 'si')->count();
        $totalMachos = $animal->where('sexo', 'macho')->count();
        $totalHembras = $animal->where('sexo', 'hembra')->count();

        // Si no hay ningún filtro, mostrar vista sin resultados
        if (empty($query) && is_null($edadMinAnios) && is_null($edadMaxAnios)) {
            $animales = collect();
            return view('reportes.rp_buscar_animal', compact(
                'animales', 'totalAnimales', 'totalCastrados', 'totalMachos', 'totalHembras'
            ));
        }

        $animalesQuery = Animal::query();

        if ($query) {
            $animalesQuery->where('nombre', 'LIKE', "%$query%")
                ->orWhere('color', 'LIKE', "%$query%")
                ->orWhereHas('propietario', function ($q) use ($query) {
                    $q->where('nombres', 'LIKE', "%$query%")
                        ->orWhere('codigo', 'LIKE', "%$query%");
                });
        }

        $animales = $animalesQuery->get();

        if ($edadMinAnios !== null || $edadMaxAnios !== null) {
            $animales = $animales->filter(function ($animal) use ($edadMinAnios, $edadMaxAnios) {
                $edadAnios = $this->extraerEdadEnAnios($animal->edad);
                return ($edadMinAnios === null || $edadAnios >= $edadMinAnios) &&
                       ($edadMaxAnios === null || $edadAnios <= $edadMaxAnios);
            });
        }

        return view('reportes.rp_buscar_animal', compact(
            'animales', 'totalAnimales', 'totalCastrados', 'totalMachos', 'totalHembras'
        ));
    }
}
