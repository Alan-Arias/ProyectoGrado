<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Propietario;
use Illuminate\Support\Facades\Auth;

class PropietarioController extends Controller
{
    public function index(Request $request)
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
        $perPage = $request->input('perPage', 2);        
        $Propietarios = Propietario::paginate($perPage);
        return view('propietario.propietario', compact('Propietarios')); // Mostrar la vista
    }    
    public function store(Request $request)
    {
        //validacion de la imagen
        $request->validate([            
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);
        //para la imagen
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $filename = substr(md5(time()), 0, 30) . '.' . $extension;
            $path = $file->storeAs('public/propietarios', $filename);  
            $fotoUrl = str_replace('public/', 'storage/', $path);
        } else {
            $fotoUrl = 'storage/propietarios/unknown.jpg';
        }

        $Propietario=new Propietario();
        $Propietario->nombres=$request->name;
        $Propietario->fecha_nac=$request->date;
        $Propietario->direccion=$request->direccion;  

        if ($request->hasFile('pc_prop')) {
            $file = $request->file('pc_prop');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = 'propietarios/' . $filename;

            $file->move(public_path('propietarios'), $filename);

            $Propietario->foto_fachada = $filePath;
        }     
         
        $Propietario->save();
        return back()->with('agregar', 'Se ha registrado Correctamente');
    }
}
