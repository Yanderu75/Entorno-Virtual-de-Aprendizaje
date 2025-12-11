<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Materia;
use Illuminate\Support\Str;

class TeachersSeeder extends Seeder
{
    public function run()
    {
        // Define specialist teachers
        $teachers = [
            'Matemáticas' => ['name' => 'Jose Altuve', 'email' => 'jose@gmail.com'],
            'Castellano' => ['name' => 'Maria Rodriguez', 'email' => 'maria@gmail.com'],
            'Inglés' => ['name' => 'John Smith', 'email' => 'john@gmail.com'],
            'GHC (Geografía, Historia)' => ['name' => 'Simon Bolivar', 'email' => 'simon@gmail.com'], // GHC teacher
            'Arte y Patrimonio' => ['name' => 'Frida Kahlo', 'email' => 'frida@gmail.com'],
            'Educación Física' => ['name' => 'Yulimar Rojas', 'email' => 'yulimar@gmail.com'],
            'Ciencias Naturales' => ['name' => 'Albert Einstein', 'email' => 'albert@gmail.com'],
            'Biología' => ['name' => 'Charles Darwin', 'email' => 'charles@gmail.com'],
            'Física' => ['name' => 'Isaac Newton', 'email' => 'isaac@gmail.com'],
            'Química' => ['name' => 'Marie Curie', 'email' => 'marie@gmail.com'],
            'Soberanía' => ['name' => 'Andres Bello', 'email' => 'andres@gmail.com'],
            'Grupo de Recreación' => ['name' => 'Tio Simon', 'email' => 'tiosimon@gmail.com'],
        ];

        foreach ($teachers as $specialty => $data) {
            // Create or Find Teacher
            $teacher = User::firstOrCreate(
                ['correo' => $data['email']],
                [
                    'nombre' => $data['name'],
                    'contraseña' => bcrypt('password'), // Simple password for testing
                    'rol' => 'docente',
                    'estado' => 'activo',
                    'especialidad' => $specialty
                ]
            );

            // Assign this teacher to all matching subjects
            // We match broadly, e.g., "Matemáticas" matches "Matemáticas 1A", "Matemáticas 5B"
            // Note: The Seeder created simple names "Matemáticas", but in case future logic appends things, we use LIKE or strict match.
            // Our previous seeder created names exactly like 'Matemáticas', 'Inglés', etc.
            
            // Handle specific GHC matching because name in DB is 'GHC (Geografía, Historia)'
            // Handle 'Grupo'
            
            $searchName = $specialty;
            
            // Update subjects
            // We use 'like' to catch variations if any, or strict match.
            // Using LIKE %Name% to be safe.
            $term = explode(' ', $specialty)[0]; // First word match usually works (Matemáticas, Castellano)
            if ($specialty == 'GHC (Geografía, Historia)') $term = 'GHC';
            
            Materia::where('nombre', 'LIKE', "%{$term}%")
                ->update(['id_docente' => $teacher->id_usuario]);
        }
    }
}
