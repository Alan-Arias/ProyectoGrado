<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->input('buscar');

        $intentos = DB::table('intento_censo')
            ->join('censista', 'intento_censo.id_censista', '=', 'censista.codigo_estudiante')
            ->select('intento_censo.*', 'censista.nombre', 'censista.apellido')
            ->when($busqueda, function ($query, $busqueda) {
                return $query->where('censista.codigo_estudiante', 'like', "%$busqueda%")
                            ->orWhere('censista.nombre', 'like', "%$busqueda%")
                            ->orWhere('censista.apellido', 'like', "%$busqueda%");
            })
            ->orderBy('intento_censo.updated_at', 'desc')
            ->paginate(5);

        return view('censo.censo_panel', compact('intentos', 'busqueda'));
    }

    public function reset(Request $request)
    {
        if ($request->has('reset_all')) {
            DB::table('intento_censo')->update(['intentos_realizados' => '0']);
            return back()->with('mensaje', 'Se han restablecido los intentos de todos los censistas.');
        }

        if ($request->has('reset_one')) {
            DB::table('intento_censo')
                ->where('id_censista', $request->input('codigo_estudiante'))
                ->update(['intentos_realizados' => '0']);
            return back()->with('mensaje', 'Intentos restablecidos para el censista seleccionado.');
        }

        return back()->with('mensaje', 'No se ha seleccionado ninguna acción.');
    }
    public function cambiarEstado(Request $request)
    {
        $censistaId = $request->input('censista_id');
        $nuevoEstado = $request->input('estado') == '1' ? 1 : 0;

        DB::table('censista')
            ->where('codigo_estudiante', $censistaId)
            ->update(['activo' => $nuevoEstado]);

        return back()->with('mensaje', 'El estado del censista ha sido actualizado.');
    }
}
