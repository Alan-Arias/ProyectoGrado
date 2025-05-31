<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\usuario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $usuario = Usuario::where('email', $request->email)->first();
        if ($usuario) {
            $personal = $usuario->personal;
            if (!$personal) {
                return redirect()->back()->with('error', 'El usuario no está vinculado a un registro de personal.');
            }            
            if (strtolower($personal->estado) !== 'activo') {
                return redirect()->back()->with('error', 'Su cuenta está inhabilitada. Por favor contacte con soporte.');
            }
            if (Hash::check($request->password, $usuario->password)) {
                Auth::login($usuario);
                session([
                    'user_id' => $usuario->id,
                    'user_email' => $usuario->email,
                    'user_nombre' => $usuario->nombre,
                    'user_tipo' => $usuario->tipo_user,
                ]);
                switch ($usuario->tipo_user) {
                    case 'Director General':
                        return redirect('/Especies');
                    case 'Veterinario':
                        return redirect('/Animales');
                    case 'Administrador':
                        return redirect('/Usuarios');
                    default:
                        return redirect('/');
                }
            }
        }
        return redirect()->back()->with('error', 'Credenciales incorrectas.');
    }   
    public function logout(Request $request)
    {
        auth()->logout();
        return redirect('/Login');
    }

}
