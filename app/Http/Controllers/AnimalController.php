<?php

namespace App\Http\Controllers;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Raza;
use App\Models\Enfermedad;
use App\Models\TipoEnfermedad;
use App\Models\Censo;
use App\Models\Propietario;
use App\Models\especiemodel;
use App\Models\HistorialSanitario;
use App\Models\FormaAdquisicion;
use App\Models\Incapacidad;
use App\Models\DirectorGeneral;
use App\Models\Veterinario;
use App\Models\Tipo_Animal;
use App\Models\Otb;
use App\Models\RegistroCambio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Exports\AnimalesCensadosExport;
use Maatwebsite\Excel\Facades\Excel;

class AnimalController extends Controller
{
    public function boot()
    {
        Paginator::useBootstrap();
    }
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
        
        $perPage = $request->input('perPage', 5);
        $Animales = Animal::orderBy('id', 'desc')->paginate($perPage);
        $Propietarios = Propietario::all();
        $Especies = especiemodel::with('razas')->get();
        $Historial = HistorialSanitario::all();
        $FormaAdquisicion = FormaAdquisicion::all();
        $Incapacidad = Incapacidad::all();
        $tipos = Tipo_Animal::all();
        $grados = Incapacidad::all();
        return view('animal.animal', compact('Animales', 'Propietarios', 'Especies', 'Historial', 'FormaAdquisicion', 'Incapacidad', 'tipos', 'grados')); // Mostrar la vista
    }  
    public function store(Request $request)
    {
            // Procesar la foto si está presente
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $extension = $file->getClientOriginalExtension();
                $filename = substr(md5(time()), 0, 30) . '.' . $extension;
                $path = $file->storeAs('public/animals', $filename);
                $fotoUrl = str_replace('public/', 'storage/', $path);
            } else {
                $fotoUrl = 'storage/animals/unknown.jpg';
            }

            // Crear una nueva instancia de Animal
            $Animal = new Animal();
            $Animal->nombre = $request->name;
            $Animal->color = $request->color;
            $Animal->fecha_nac = $request->date;
            $Animal->sexo = $request->sexo;
            $Animal->carnet_vacuna = $request->carnetvacuna;
            $Animal->n_chip = $request->input('n_chip', 'no_tiene');
            $Animal->ultima_vacuna = $request->datevacuna;
            $Animal->censo_data = $request->input('censo_data', 'no');
            $Animal->castrado = $request->castrado;
            $Animal->tipo_animal_id = $request->tipo_animal_id;
            $Animal->alergico=$request->alergico;
            $Animal->estado = $request->estado;
    
            // Procesar la foto del animal
            if ($request->hasFile('pc_animal')) {
                $file = $request->file('pc_animal');
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = 'animals/' . $filename;
                $file->move(public_path('animals'), $filename);
                $Animal->foto_animal = $filePath;
            }
    
            // Asignar las relaciones            
            $Animal->codigo_propietario = $request->propietario_id;
            $Animal->raza_id = $request->raza_id;
            $Animal->fecha_deceso = $request->fecha_deceso;
            $Animal->motivo_deceso = $request->motivo_deceso;
            $Animal->incapacidad_id= $request->grado_incapacidad_id;
            $Animal->forma_adq_id=$request->forma_adquisicion_id;
            $Animal->save();
    
            return back()->with('agregar', 'Se ha registrado Correctamente');
    }    
    public function buscar(Request $request) 
    {
        $query = $request->input('query');

        if (empty($query)) {
            
        }

        $Animales = Animal::where('nombre', 'LIKE', "%$query%")
                    ->orWhere('color', 'LIKE', "%$query%")
                    ->orWhereHas('propietario', function($q) use ($query) {
                        $q->where('nombres', 'LIKE', "%$query%")
                        ->orWhere('codigo', 'LIKE', "%$query%");;
                    })
                    ->get();

        return view('layout.resultados', compact('Animales'));
    }  
    public function CensoAnimales(Request $request)
    {
        $codigo = session('codigo_estudiante');
        if (!$codigo) {
            return redirect('/CensoAnimales')->with('mensaje', 'Debes iniciar sesión como censista para acceder.');
        }

        $intento = DB::table('intento_censo')
                    ->where('id_censista', $codigo)
                    ->latest('id')
                    ->first();

        if ($intento) {
            $realizados = (int)$intento->intentos_realizados;
            $totales = (int)$intento->intentos_totales;

            if ($realizados >= $totales) {
                session()->flush(); // Cierra la sesión
                return redirect('/CensoAnimales')->with('sin_intentos', 'Ya no tienes intentos disponibles. Tu sesión ha sido cerrada.');
            }
        }
        $Animales = Animal::where('censo_data', 'si')->get();     
        $Propietarios = Propietario::all();
        $Especies = especiemodel::with('razas')->get();
        $Historial = HistorialSanitario::all();
        $FormaAdquisicion = FormaAdquisicion::all();
        $Incapacidad = Incapacidad::all();
        $tipos = Tipo_Animal::all();
        $grados = Incapacidad::all();
        $Otb = Otb::all();
        $Censo = Censo::all();
        return view('animal.censo_animal', compact('Animales', 'Especies', 'Historial', 'FormaAdquisicion', 'Incapacidad', 'tipos', 'grados', 'Otb', 'Censo')); // Mostrar la vista
    }
    public function getPropietariosAjax(Request $request)
    {
        $busqueda = $request->input('busqueda');
        $limite = $request->input('limite', 2);

        $query = Propietario::query();

        if (!empty($busqueda)) {
            $query->where('nombres', 'like', "%$busqueda%");
        }

        $propietarios = $query->orderBy('nombres')->paginate($limite);

        return response()->json($propietarios);
    }
    public function CensoIndex(Request $request)
    {
        $codigo = session('codigo_estudiante');
        if (!$codigo) {
            return redirect('/CensoAnimales')->with('mensaje', 'Debes iniciar sesión como censista para acceder.');
        }

        $intento = DB::table('intento_censo')
                    ->where('id_censista', $codigo)
                    ->latest('id')
                    ->first();

        if ($intento) {
            $realizados = (int)$intento->intentos_realizados;
            $totales = (int)$intento->intentos_totales;

            if ($realizados >= $totales) {
                session()->flush(); // Cierra la sesión
                return redirect('/CensoAnimales')->with('sin_intentos', 'Ya no tienes intentos disponibles. Tu sesión ha sido cerrada.');
            }
        }            
        $Censo = Censo::all();
        return view('propietario.censo', compact('Censo'));
    }
    public function CensoStore(Request $request)
    {
        // Guardar el censo
        $Censo = new Censo();
        $Censo->gestion = $request->gestion;
        $Censo->fecha_inicio = $request->fecha_inicio;
        $Censo->fecha_fin = $request->fecha_fin;
        $Censo->coordinador = $request->coordinador;
        $Censo->save();
        return back()->with('agregar', 'Se ha registrado correctamente en Censo-Animal.');
    }
    public function CensoAnimalesStore(Request $request)
    {
        $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tipo_animal_id' => 'required',
        ], [
            'tipo_animal_id.required' => 'Debe seleccionar un tipo de animal.',
        ]);
    
        // Procesar la foto si está presente
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $filename = substr(md5(time()), 0, 30) . '.' . $extension;
            $path = $file->storeAs('public/animals', $filename);
            $fotoUrl = str_replace('public/', 'storage/', $path);
        } else {
            $fotoUrl = 'storage/animals/unknown.jpg';
        }
    
        // Buscar si el animal ya existe
        $Animal = Animal::where('nombre', $request->name)
            ->where('codigo_propietario', $request->propietario_id)
            ->first();
    
        if (!$Animal) {
            // Si el animal no existe, crearlo
            $Animal = new Animal();
            $Animal->nombre = $request->name;
            $Animal->color = $request->color;
            $Animal->fecha_nac = $request->date;
            $Animal->sexo = $request->sexo;
            $Animal->carnet_vacuna = $request->carnetvacuna;
            $Animal->n_chip = $request->input('n_chip', 'no_tiene');
            $Animal->ultima_vacuna = $request->datevacuna;
            $Animal->censo_data = $request->input('censo_data', 'si');
            $Animal->castrado = $request->castrado;
            $Animal->tipo_animal_id = $request->tipo_animal_id;
            $Animal->alergico=$request->alergico;
            $Animal->estado = $request->estado;
    
            // Procesar la foto del animal
            if ($request->hasFile('pc_animal')) {
                $file = $request->file('pc_animal');
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = 'animals/' . $filename;
                $file->move(public_path('animals'), $filename);
                $Animal->foto_animal = $filePath;
            }
    
            $Animal->codigo_propietario = $request->propietario_id;
            $Animal->raza_id = $request->raza_id;
            $Animal->fecha_deceso = $request->fecha_deceso;
            $Animal->motivo_deceso = $request->motivo_deceso;
            $Animal->incapacidad_id= $request->grado_incapacidad_id;
            $Animal->forma_adq_id=$request->forma_adquisicion_id;
            $Animal->save();
        }        
        DB::table('censo_mascota')->insert([
            'censo_id' => $request->gestion_id,
            'animal_id' => $Animal->id,
            'propietario_id' => $Animal->codigo_propietario,
            'mascota_edad' => $request->edad,  
            'otb_id' => $request->otb_id,    
            'id_censista' => session('codigo_estudiante'),     
        ]);
        $registro = DB::table('intento_censo')
            ->where('id_censista', session('codigo_estudiante'))
            ->where('id_censo', $request->gestion_id)
            ->first();

        if ($registro) {
            $actual = (int)$registro->intentos_realizados;
            $nuevo = $actual + 1;

            DB::table('intento_censo')
                ->where('id_censista', session('codigo_estudiante'))
                ->where('id_censo', $request->gestion_id)
                ->update(['intentos_realizados' => (string)$nuevo]);
        }
        return back()->with('agregar', 'Se ha registrado correctamente en Censo-Animal.');
    }   
    public function CensoAnimalesPropietariosIndex(Request $request)
    {
                $codigo = session('codigo_estudiante');

        if (!$codigo) {
            return redirect('/CensoAnimales')->with('mensaje', 'Debes iniciar sesión como censista para acceder.');
        }

        $intento = DB::table('intento_censo')
                    ->where('id_censista', $codigo)
                    ->latest('id')
                    ->first();

        if ($intento) {
            $realizados = (int)$intento->intentos_realizados;
            $totales = (int)$intento->intentos_totales;

            if ($realizados >= $totales) {
                session()->flush(); // Cierra la sesión
                return redirect('/CensoAnimales')->with('sin_intentos', 'Ya no tienes intentos disponibles. Tu sesión ha sido cerrada.');
            }
        }
        $totalPropietarios = Propietario::count();
        return view('propietario.censo_propietarios', compact('totalPropietarios'));
    }
    public function CensoAnimalesPropietariosStore(Request $request)
    {
        $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Guardar la imagen si existe
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $filename = substr(md5(time()), 0, 30) . '.' . $extension;
            $path = $file->storeAs('public/propietarios', $filename);  
            $fotoUrl = str_replace('public/', 'storage/', $path);
        } else {
            $fotoUrl = 'storage/propietarios/unknown.jpg';
        }        
    
        // Verificar si el propietario ya existe
        $Propietario = Propietario::where('carnet_nro', $request->carnet)->first();
    
        if (!$Propietario) {
            // Crear nuevo propietario
            $Propietario = new Propietario();
            $Propietario->nombres = $request->name;
            $Propietario->fecha_nac = $request->date;
            $Propietario->direccion = $request->direccion;
            $Propietario->carnet_nro = $request->carnet;
            $Propietario->telefono_nro = $request->telefono;
            
            if ($request->hasFile('pc_prop')) {
                $file = $request->file('pc_prop');
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = 'propietarios/' . $filename;

                $file->move(public_path('propietarios'), $filename);

                $Propietario->foto_fachada = $filePath;
            }
            $Propietario->save();
        }
            
        return back()->with('agregar', 'Se ha registrado correctamente');
    }
       
    public function CensoAnimalesMostrarEspecie(Request $request)
    {
        $codigo = session('codigo_estudiante');
        if (!$codigo) {
            return redirect('/CensoAnimales')->with('mensaje', 'Debes iniciar sesión como censista para acceder.');
        }

        $intento = DB::table('intento_censo')
                    ->where('id_censista', $codigo)
                    ->latest('id')
                    ->first();

        if ($intento) {
            $realizados = (int)$intento->intentos_realizados;
            $totales = (int)$intento->intentos_totales;

            if ($realizados >= $totales) {
                session()->flush(); // Cierra la sesión
                return redirect('/CensoAnimales')->with('sin_intentos', 'Ya no tienes intentos disponibles. Tu sesión ha sido cerrada.');
            }
        }        
        $perPage = $request->input('perPage', 1);
        // Si es Director General, continuar con la lógica
        $Especies = especiemodel::paginate($perPage);
        $Raza = Raza::all();
        return view('animal.censo_especie', compact('Especies', 'Raza'));
    }
    public function CensoAnimalesEnfermedadesIndex(Request $request)
    {
        $codigo = session('codigo_estudiante');
        if (!$codigo) {
            return redirect('/CensoAnimales')->with('mensaje', 'Debes iniciar sesión como censista para acceder.');
        }

        $intento = DB::table('intento_censo')
                    ->where('id_censista', $codigo)
                    ->latest('id')
                    ->first();

        if ($intento) {
            $realizados = (int)$intento->intentos_realizados;
            $totales = (int)$intento->intentos_totales;

            if ($realizados >= $totales) {
                session()->flush(); // Cierra la sesión
                return redirect('/CensoAnimales')->with('sin_intentos', 'Ya no tienes intentos disponibles. Tu sesión ha sido cerrada.');
            }
        }       
        $Animales2 = Animal::with(['raza', 'propietario'])->orderBy('id', 'desc')->get();
        $Enfermedad = Enfermedad::all();
        $ListaEnfermedad = TipoEnfermedad::orderBy('nombre')->get();
        $censo_data = 'si';

        // Conteo de animales sanos y enfermos
        $totalSanos = Enfermedad::where('nombre', 'Sin enfermedad')->count();
        $totalEnfermos = Enfermedad::where('nombre', '!=', 'Sin enfermedad')->count();

        return view('animal.censo_animal_enfermedad', compact(
            'Animales2',
            'Enfermedad',
            'ListaEnfermedad',
            'censo_data',
            'totalSanos',
            'totalEnfermos'
        ));
    }
    
    public function buscarAnimales(Request $request)
    {
        $query = Animal::with(['raza', 'propietario']);

        if ($request->has('censo_data') && $request->censo_data === 'si') {
            $query->where('censo_data', 'si');
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                ->orWhereHas('propietario', function ($q2) use ($search) {
                    $q2->where('nombres', 'like', "%$search%");
                });
            });
        }

        $animales = $query->orderBy('id', 'desc')->paginate(4); // 4 por página

        return response()->json($animales);
    }
    public function CensoAnimalesEnfermedadesStore(Request $request)
    {
        $Enfermedad = new Enfermedad();
        $Enfermedad->nombre = $request->nombre_enfermedad;
        $Enfermedad->fecha_ini = $request->fecha;
        $Enfermedad->caracteristicas = $request->caracteristicas;
        $Enfermedad->vacuna_name = $request->vacuna;
        $Enfermedad->mascota_id = $request->animal_id;
        $Enfermedad->tipo_enf_id = $request->filled('enfermedadId') ? $request->enfermedadId : null;
        $Enfermedad->save();
        return back()->with('agregar', 'Se ha registrado Correctamente');
    }
    public function CensoAnimalesListaEnfermedadesIndex(Request $request)
    {
        $codigo = session('codigo_estudiante');
        if (!$codigo) {
            return redirect('/CensoAnimales')->with('mensaje', 'Debes iniciar sesión como censista para acceder.');
        }

        $intento = DB::table('intento_censo')
                    ->where('id_censista', $codigo)
                    ->latest('id')
                    ->first();

        if ($intento) {
            $realizados = (int)$intento->intentos_realizados;
            $totales = (int)$intento->intentos_totales;

            if ($realizados >= $totales) {
                session()->flush(); // Cierra la sesión
                return redirect('/CensoAnimales')->with('sin_intentos', 'Ya no tienes intentos disponibles. Tu sesión ha sido cerrada.');
            }
        }
        $ListaEnfermedad = TipoEnfermedad::all();        
        return view('animal.censo_animal_lista_enfermedad', compact('ListaEnfermedad'));
    }
    public function CensoAnimalesListaEnfermedadesStore(Request $request)
    {
        $ListaEnfermedad = new TipoEnfermedad();
        $ListaEnfermedad->nombre = $request->nombre_enfermedad;
        $ListaEnfermedad->especie_enf = $request->especie_enf;
        $ListaEnfermedad->save();
        return back()->with('agregar', 'Se ha registrado Correctamente');
    }
    public function CensoAnimalesOtb(Request $request)
    {
        $codigo = session('codigo_estudiante');
        if (!$codigo) {
            return redirect('/CensoAnimales')->with('mensaje', 'Debes iniciar sesión como censista para acceder.');
        }

        $intento = DB::table('intento_censo')
                    ->where('id_censista', $codigo)
                    ->latest('id')
                    ->first();

        if ($intento) {
            $realizados = (int)$intento->intentos_realizados;
            $totales = (int)$intento->intentos_totales;

            if ($realizados >= $totales) {
                session()->flush(); // Cierra la sesión
                return redirect('/CensoAnimales')->with('sin_intentos', 'Ya no tienes intentos disponibles. Tu sesión ha sido cerrada.');
            }
        }
        $Otb = Otb::all();
        return view('otb.otb', compact('Otb'));
    }
    public function CensoAnimalesOtbStore(Request $request)
    {
        $Otb = new Otb();
        $Otb->nombre_area = $request->otb_name;
        $Otb->save();
        return back()->with('agregar', 'Se ha registrado Correctamente');
    }
    
    public function cambiarPropietario($id)
    {
        $animal = Animal::findOrFail($id);
        $propietarios = Propietario::all(); // Obtener lista de propietarios
    
        return view('animal.cambiar_propietario', compact('animal', 'propietarios'));
    }
    
    public function actualizarPropietario(Request $request, $id)
    {
        $animal = Animal::findOrFail($id);
        $nuevoPropietario = $request->codigo_propietario_nuevo;
    
        // Registrar el cambio en la tabla `registro_cambio`
        \DB::table('registro_cambio')->insert([
            'id_usuario' => Auth::id(),
            'id_animal' => $animal->id,
            'codigo_propietario_anterior' => $animal->codigo_propietario,
            'codigo_propietario_nuevo' => $nuevoPropietario,
            'created_at' => now(),
        ]);
    
        // Actualizar el propietario en la tabla `animal`
        $animal->update(['codigo_propietario' => $nuevoPropietario]);
    
        return redirect('/Animales')->with('success', 'Propietario cambiado correctamente.');
    } 
    public function indexTipoAnimal(Request $request)
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
        $TipoAnimal = Tipo_Animal::all();
        return view('animal.tipo_animal', compact('TipoAnimal')); // Mostrar la vista
    }    
    public function storeTipoAnimal(Request $request)
    {
        $TipoAnimal = new Tipo_Animal();
        $TipoAnimal->nombre = $request->name;
        $TipoAnimal->save();
        return back()->with('agregar', 'Se ha registrado Correctamente');
    }
    public function indexFormaAdquisicion(Request $request)
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
        $FormaAdquisicion = FormaAdquisicion::all();
        return view('animal.forma_adquisicion_animal', compact('FormaAdquisicion')); // Mostrar la vista
    }
    public function storeFormaAdquisicion(Request $request)
    {
        $FormaAdquisicion = new FormaAdquisicion();
        $FormaAdquisicion->nombre = $request->name;
        $FormaAdquisicion->save();
        return back()->with('agregar', 'Se ha registrado Correctamente');
    }
    public function indexIncapacidad(Request $request)
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
        $Incapacidad = Incapacidad::all();
        return view('animal.grado_incapacidad_animal', compact('Incapacidad')); // Mostrar la vista
    }
    public function storeIncapacidad(Request $request)
    {
        $Incapacidad = new Incapacidad();
        $Incapacidad->descripcion = $request->name;
        $Incapacidad->save();
        return back()->with('agregar', 'Se ha registrado Correctamente');
    }
    public function indexHistorialPropietarios(Request $request)
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
        $Animales = Animal::orderBy('id', 'desc')->get();
        $RegistroCambio = RegistroCambio::orderBy('id', 'desc')->get();
        return view('animal.historial_prop_animal',compact('Animales', 'RegistroCambio'));
    }
    public function exportExcel()
    {
        return Excel::download(new AnimalesCensadosExport, 'animales_censados.xlsx');
    }
}
