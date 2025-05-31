<?php

namespace App\Http\Controllers;
use App\Models\usuario;
use App\Models\Personal;
use App\Models\DirectorGeneral;
use App\Models\Veterinario;
use App\Models\Secretaria;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            session()->flash('error', 'Debes iniciar sesión primero.');
            return redirect('/Login'); // Redirigir sin abrir una nueva ventana
        }

        // Obtener al usuario autenticado
        $usuario = Auth::user();

        // Verificar si el usuario es Director General o Administrador
        if (!in_array($usuario->tipo_user, ['Director General', 'Administrador'])) {
            session()->flash('error', 'Debes iniciar sesión como Director General o Administrador para acceder a los registros.');
            return redirect()->back(); // Redirigir a la página anterior
        }
        $perPage = $request->input('perPage', 4);
        $Personal = Personal::orderBy('id', 'desc')->paginate($perPage)->appends($request->all());
        $Usuario = Usuario::paginate($perPage);
        return view('usuarios.usuario', compact('Usuario', 'Personal')); // Mostrar la vista
    }    
    public function indexRol(Request $request)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            session()->flash('error', 'Debes iniciar sesión primero.');
            return redirect('/Login'); // Redirigir sin abrir una nueva ventana
        }

        // Obtener al usuario autenticado
        $usuario = Auth::user();

        // Verificar si el usuario es Director General o Administrador
        if (!in_array($usuario->tipo_user, ['Director General', 'Administrador'])) {
            session()->flash('error', 'Debes iniciar sesión como Director General o Administrador para acceder a los registros.');
            return redirect()->back(); // Redirigir a la página anterior
        }
        $search = $request->input('search');

        $Personal = Personal::with('usuariov2')
            ->when($search, function ($query, $search) {
                return $query->where('nombre completo', 'like', '%' . $search . '%')
                            ->orWhereHas('usuariov2', function ($query) use ($search) {
                                $query->where('email', 'like', '%' . $search . '%');
                            });
            })
            ->orderBy('id', 'desc')
            ->paginate(5);

        $Usuario = Usuario::all();

        return view('usuarios.rol', compact('Usuario', 'Personal'));
    }
    public function store(Request $request)
    {
        // Validación de los campos
        $request->validate([
            'tipo_usuario.required' => 'Debe seleccionar un tipo de usuario.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            // Otros campos si es necesario
        ]);

        // Calculando la edad
        $fechaNacimiento = $request->fecha_nacimiento;
        $edad = \Carbon\Carbon::parse($fechaNacimiento)->age;

        // Validación para verificar que sea mayor de edad
        if ($edad < 18) {
            return back()->with('error', 'La persona debe ser mayor de edad para guardar los datos.');
        }

        // Creación del objeto Personal y asignación de valores
        $Personal = new Personal();
        $Personal->{'nombre completo'} = $request->input('nombre');
        $Personal->fecha_nacimiento = $fechaNacimiento;        
        $Personal->sexo = $request->sexo;
        $Personal->direccion = $request->direccion;
        $Personal->telefono = $request->telefono;
        $Personal->estado = $request->estado;
        $Personal->tipo_usuario = $request->tipo_usuario;
        switch ($request->tipo_usuario) {
            case 'Veterinario':
                $Personal->especialidad = $request->input('especialidad');
                break;
            case 'Secretaria':
                $Personal->horario_trabajo = $request->input('horario_trabajo');
                break;
            case 'Director General':
                $Personal->anios_cargo = $request->input('anios_cargo');
                break;
        }
        $Personal->save();

        return back()->with('agregar', 'Se ha registrado correctamente');
    }

    public function agregarRol($id)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            session()->flash('error', 'Debes iniciar sesión primero.');
            return redirect('/Login'); // Redirigir sin abrir una nueva ventana
        }

        // Obtener al usuario autenticado
        $usuario = Auth::user();

        // Verificar si el usuario es Director General o Administrador
        if (!in_array($usuario->tipo_user, ['Director General', 'Administrador'])) {
            session()->flash('error', 'Debes iniciar sesión como Director General o Administrador para acceder a los registros.');
            return redirect()->back(); // Redirigir a la página anterior
        }    
        $personal =  Personal::find($id);
        $usuario = Usuario::find($id);
        return view('usuarios.agregar_rol', compact('usuario', 'personal'));
    }
    public function guardarRol(Request $request, $id)
    {
        $usuario = new Usuario();
        $usuario->nombre = $request->nombre_completo;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->contraseña);
        $usuario->personal_id = $id;
        
        $usuario->tipo_user = $request->rol;
        if (strtolower($request->rol) === 'Administrador') {
            $usuario->nivel_acceso = 'Alto';
        }
        $usuario->save();
        return redirect('/Usuarios')->with('agregar', 'Rol añadido correctamente.');
    }      
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:activo,inhabilitado'
        ]);

        $personal = Personal::findOrFail($id);
        $personal->estado = $request->estado;
        $personal->save();

        return redirect()->back()->with('agregar', 'Estado actualizado correctamente.');
    } 
}
