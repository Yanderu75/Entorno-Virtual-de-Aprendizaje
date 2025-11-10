<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuarios de prueba
        User::create([
            'nombre' => 'Administrador',
            'correo' => 'admin@eva.com',
            'contraseña' => Hash::make('123456'),
            'rol' => 'admin',
            'estado' => 'activo',
        ]);

        User::create([
            'nombre' => 'Profesor Juan',
            'correo' => 'juan@eva.com',
            'contraseña' => Hash::make('123456'),
            'rol' => 'docente',
            'estado' => 'activo',
        ]);

        User::create([
            'nombre' => 'Estudiante Ana',
            'correo' => 'ana@eva.com',
            'contraseña' => Hash::make('123456'),
            'rol' => 'estudiante',
            'estado' => 'activo',
        ]);
    }
}
