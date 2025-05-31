<?php

namespace App\Http\Controllers;
use App\Models\Censista;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CensistaController extends Controller
{
    public function create()
    {
        return view('censo.censo_users');
    }
    public function store(Request $request)
    {
        $accion = $request->input('accion');

        if ($accion === 'login') {
            // Verificamos si el censista ya está en intento_censo
            $existe = DB::table('intento_censo')
                        ->where('id_censista', $request->codigo_estudiante)
                        ->exists();

            if ($existe) {
                session(['codigo_estudiante' => $request->codigo_estudiante]);
                return redirect('/CensoAnimales/Propietarios')->with('mensaje', 'Bienvenido nuevamente, censista.');
            } else {
                return redirect()->back()->with('mensaje', 'El censista no está registrado. Por favor regístrate primero.');
            }
        }

        // Acción: registro
        $request->validate([
            'codigo_estudiante' => 'required',
            'nombre' => 'required',
            'apellido' => 'required',
            'codigo_carrera' => 'required',
        ]);

        $censista = Censista::where('codigo_estudiante', $request->codigo_estudiante)->first();
        if (!$censista) {
            $censista = Censista::create($request->all());
            $ultimoCenso = DB::table('censo')->latest('id')->first();

            if ($ultimoCenso) {
                DB::table('intento_censo')->insert([
                    'id_censista' => $censista->codigo_estudiante,
                    'id_censo' => $ultimoCenso->id,
                    'fecha' => now()->toDateString(),
                    'intentos_totales' => '10',
                    'intentos_realizados' => '0',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            session(['codigo_estudiante' => $censista->codigo_estudiante]);
            return redirect('/CensoAnimales/Propietarios')->with('mensaje', 'Censista registrado correctamente.');
        } else {
            session(['codigo_estudiante' => $censista->codigo_estudiante]);
            return redirect('/CensoAnimales/Propietarios')->with('mensaje', 'Bienvenido nuevamente, censista.');
        }
    }
    public function logoutCensista(Request $request)
    {
        $request->session()->forget('codigo_estudiante');
        return redirect('/CensoAnimales');
    }
}
