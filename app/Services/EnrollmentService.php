<?php

namespace App\Services;

use App\Models\User;
use App\Models\Materia;
use App\Models\EstudianteMateria;
use App\Models\Auditoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    /**
     * Sincroniza las materias del estudiante basado en su Grado y Sección.
     * Elimina inscripciones que ya no corresponden y agrega las nuevas.
     */
    public function syncStudentSubjects(User $estudiante)
    {
        if ($estudiante->rol !== 'estudiante') {
            return;
        }

        if (!$estudiante->grado || !$estudiante->seccion) {
            // Si no tiene grado/sección asignado, no debería tener materias (o quizas es nuevo ingreso)
            // Por seguridad, podríamos no hacer nada o limpiar todo.
            // Decisión: Si no tiene grado/sección, no inscribimos nada, pero no borramos historial antiguo a menos que sea explícito.
            return;
        }

        // 1. Buscar todas las materias que corresponden al grado y sección del estudiante
        $materiasCorrespondientes = Materia::where('grado', $estudiante->grado)
            ->where('seccion', $estudiante->seccion)
            ->get();

        $idsMateriasCorrespondientes = $materiasCorrespondientes->pluck('id_materia')->toArray();

        // 2. Obtener las inscripciones actuales del estudiante
        $inscripcionesActuales = EstudianteMateria::where('id_estudiante', $estudiante->id_usuario)->get();
        $idsInscritas = $inscripcionesActuales->pluck('id_materia')->toArray();

        DB::beginTransaction();
        try {
            // 3. Identificar materias a eliminar (Las que tiene inscritas pero ya no corresponden a su grado/sección)
            // NOTA: Esto asume que el estudiante NO DEBE ver materias de otros años/secciones.
            // Si repite materia de otro año, este sistema lo borraría.
            // Para el "Modelo Liceo" estricto: Todo es por año/sección. Si repite, debería estar en una sección especial o la materia debería estar ofertada para su sección.
            $materiasEliminar = array_diff($idsInscritas, $idsMateriasCorrespondientes);
            
            if (!empty($materiasEliminar)) {
                EstudianteMateria::where('id_estudiante', $estudiante->id_usuario)
                    ->whereIn('id_materia', $materiasEliminar)
                    ->delete();
                
                // Log auditoria (opcional detallado)
            }

            // 4. Identificar materias a agregar (Las que corresponden pero no tiene)
            $materiasAgregar = array_diff($idsMateriasCorrespondientes, $idsInscritas);

            foreach ($materiasAgregar as $idMateria) {
                EstudianteMateria::create([
                    'id_estudiante' => $estudiante->id_usuario,
                    'id_materia' => $idMateria,
                    'promedio' => 0.00,
                    'avance' => 0.00,
                ]);
            }

            // Auditoría general del cambio
            if (!empty($materiasEliminar) || !empty($materiasAgregar)) {
                $creadorId = Auth::check() ? Auth::id() : 1; // 1 = Sistema/Admin si es automático
                Auditoria::create([
                    'id_usuario' => $creadorId,
                    'accion' => "Sincronización Académica para {$estudiante->nombre}: " . count($materiasAgregar) . " agregadas, " . count($materiasEliminar) . " eliminadas.",
                    'ip' => request()->ip() ?? '127.0.0.1',
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Log error
            \Illuminate\Support\Facades\Log::error("Error sincronizando materias para usuario {$estudiante->id_usuario}: " . $e->getMessage());
        }
    }

    /**
     * Sincroniza los estudiantes de una Materia nueva o actualizada.
     * Busca todos los estudiantes del grado/seccion de la materia e inscríbelos.
     */
    public function syncMateriaStudents(Materia $materia)
    {
        if (!$materia->grado || !$materia->seccion) {
            return;
        }

        // Buscar estudiantes que coinciden
        $estudiantes = User::where('rol', 'estudiante')
            ->where('grado', $materia->grado)
            ->where('seccion', $materia->seccion)
            ->where('estado', 'activo')
            ->get();

        foreach ($estudiantes as $estudiante) {
            EstudianteMateria::firstOrCreate([
                'id_estudiante' => $estudiante->id_usuario,
                'id_materia' => $materia->id_materia,
            ], [
                'promedio' => 0.00,
                'avance' => 0.00,
            ]);
        }
    }
}
