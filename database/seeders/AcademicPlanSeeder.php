<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materia;
use App\Models\User;

class AcademicPlanSeeder extends Seeder
{
    public function run()
    {
        // Default subjects per year based on Venezuelan curriculum
        $planes = [
            '1er Año' => [
                'Castellano', 'Matemáticas', 'Inglés', 'GHC (Geografía, Historia)', 
                'Arte y Patrimonio', 'Educación Física', 'Ciencias Naturales', 'Grupo de Recreación'
            ],
            '2do Año' => [
                'Castellano', 'Matemáticas', 'Inglés', 'GHC (Geografía, Historia)', 
                'Arte y Patrimonio', 'Educación Física', 'Biología', 'Grupo de Recreación'
            ],
            '3er Año' => [
                'Castellano', 'Matemáticas', 'Inglés', 'GHC (Geografía, Historia)', 
                'Arte y Patrimonio', 'Educación Física', 'Biología', 'Física', 'Química', 'Grupo de Recreación'
            ],
            '4to Año' => [
                'Castellano', 'Matemáticas', 'Inglés', 'GHC (Geografía, Historia)', 
                'Soberanía', 'Educación Física', 'Biología', 'Física', 'Química', 'Grupo de Recreación'
            ],
            '5to Año' => [
                'Castellano', 'Matemáticas', 'Inglés', 'GHC (Geografía, Historia)', 
                'Soberanía', 'Educación Física', 'Biología', 'Física', 'Química', 'Grupo de Recreación'
            ],
        ];

        // Ensure a docent exists to assign (or create a generic one)
        $docente = User::where('rol', 'docente')->first();
        if (!$docente) {
            $docente = User::create([
                'nombre' => 'Docente General',
                'email' => 'docente@liceo.com',
                'password' => bcrypt('password'),
                'rol' => 'docente',
                'estado' => 'activo',
                'especialidad' => 'General'
            ]);
        }

        // Create subjects for Section A and B for testing
        foreach ($planes as $grado => $materias) {
            foreach (['A', 'B'] as $seccion) {
                foreach ($materias as $nombreMateria) {
                    Materia::firstOrCreate([
                        'nombre' => $nombreMateria,
                        'grado' => $grado,
                        'seccion' => $seccion,
                    ], [
                        'descripcion' => "Materia del plan de estudios de $grado",
                        'id_docente' => $docente->id_usuario,
                        'cupo_maximo' => 40,
                    ]);
                }
            }
        }
    }
}
