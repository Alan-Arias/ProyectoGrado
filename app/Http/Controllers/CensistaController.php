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
            $censista = Censista::where('codigo_estudiante', $request->codigo_estudiante)->first();

            if (!$censista) {
                return redirect()->back()->with('mensaje', 'El censista no está registrado. Por favor regístrate primero.');
            }

            if (!$censista->activo) {
                return redirect()->back()->with('mensaje', 'Tu cuenta aún no ha sido activada por un administrador.');
            }

            $existe = DB::table('intento_censo')
                        ->where('id_censista', $request->codigo_estudiante)
                        ->exists();

            if ($existe) {
                session(['codigo_estudiante' => $request->codigo_estudiante]);
                return redirect('/CensoAnimales/Propietarios')->with('mensaje', 'Bienvenido nuevamente, censista.');
            } else {
                return redirect()->back()->with('mensaje', 'Falta configurar tu acceso al censo.');
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
            return back()->with('mensaje', 'Registro exitoso. Por favor, espere a que su cuenta sea habilitada por el administrador.');
        } else {
            // Verificamos si está activo
            $censista = DB::table('censista')->where('codigo_estudiante', $request->codigo_estudiante)->first();

            if (!$censista || !$censista->activo) {
                return back()->with('error', 'Tu acceso aún no ha sido autorizado por el administrador.');
            }

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
