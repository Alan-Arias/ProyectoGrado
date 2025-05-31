<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\usuario;
use App\Models\Personal;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $personal = Personal::create([
            'nombre completo' => 'nombre prueba',
            'fecha_nacimiento' => '17-06-2000',
            'edad' => '24',
            'sexo' => 'masculino'
            'direccion' => 'direccion prueba'
            'telefono' => '77777777'
            'estado' => 'activo'
            'tipo_usuario' => 'Administrador'
        ]);
        $personal2 = Personal::create([
            'nombre completo' => 'nombre prueba 2',
            'fecha_nacimiento' => '17-06-2000',
            'edad' => '24',
            'sexo' => 'masculino',
            'direccion' => 'direccion prueba',
            'telefono' => '78888888',
            'estado' => 'activo',
            'tipo_usuario' => 'Veterinario',
        ]);
        $personal3 = Personal::create([
            'nombre completo' => 'nombre prueba 3 ',
            'fecha_nacimiento' => '17-06-2000',
            'edad' => '24',
            'sexo' => 'masculino',
            'direccion' => 'direccion prueba',
            'telefono' => '79999999',
            'estado' => 'activo',
            'tipo_usuario' => 'Secretaria',
        ]);
        $personal4 = Personal::create([
            'nombre completo' => 'nombre prueba 4',
            'fecha_nacimiento' => '17-06-2000',
            'edad' => '24',
            'sexo' => 'masculino',
            'direccion' => 'direccion prueba',
            'telefono' => '74444444',
            'estado' => 'activo',
            'tipo_usuario' => 'Director General',
        ]);
        // Crear usuarios
        $usuarioAdmin = usuario::create([
            'nombre' => 'Alan Joel Arias Moron',
            'email' => 'alanjoelarias660@gmail.com',
            'password' => bcrypt('12345'),
            'nivel_acceso' => 'Alto',
            'tipo_user' => 'Administrador',
            'personal_id' => $personal->id,
        ]);

        $usuarioVeterinario = usuario::create([
            'nombre' => 'Usuario Veterinario',
            'email' => 'usuario@gmail.com',
            'password' => bcrypt('12345'),
            'especialidad' => 'Zoonosis',
            'tipo_user' => 'Veterinario',
            'personal_id' => $personal2->id,
        ]);

        $usuarioSecretaria = usuario::create([
            'nombre' => 'Secretaria Ejemplo',
            'email' => 'secretaria@gmail.com',
            'password' => bcrypt('12345'),
            'horario_trabajo' => '09:00 - 17:00',
            'tipo_user' => 'Secretaria',
            'personal_id' => $personal3->id,
        ]);

        $usuarioDirectorGeneral = usuario::create([
            'nombre' => 'Director General',
            'email' => 'director@gmail.com',
            'password' => bcrypt('12345'),
            'anios_cargo' => '10',
            'tipo_user' => 'Director General',
            'personal_id' => $personal4->id,
        ]);

    }
    
}
