<?php

namespace App\Http\Controllers;

use App\Models\EstudianteMateria;
use App\Models\Materia;
use App\Models\User;
use App\Models\SolicitudInscripcion;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstudianteMateriaController extends Controller
{
    public function index($materiaId)
    {
        $materia = Materia::findOrFail($materiaId);
        
        if (Auth::user()->rol !== 'admin' && (Auth::user()->rol !== 'docente' || $materia->id_docente !== Auth::id())) {
            abort(403, 'No tienes permisos para ver esta materia');
        }

        $estudiantesAsignados = EstudianteMateria::where('id_materia', $materiaId)
            ->with('estudiante')
            ->get();
        $estudiantes = User::where('rol', 'estudiante')
            ->where('estado', 'activo')
            ->get();

        // Contar cupos disponibles
        $cuposDisponibles = null;
        if ($materia->cupo_maximo !== null) {
            $cuposDisponibles = $materia->cupo_maximo - $estudiantesAsignados->count();
        }

        return view('materias.asignar', compact('materia', 'estudiantesAsignados', 'estudiantes', 'cuposDisponibles'));
    }

    public function store(Request $request, $materiaId)
    {
        $materia = Materia::findOrFail($materiaId);
        
        // Si es admin, puede asignar directamente (sin solicitud)
        if (Auth::user()->rol === 'admin') {
            return $this->asignarDirectamente($request, $materiaId, $materia);
        }
        
        // Si es docente, debe crear una solicitud
        if (Auth::user()->rol !== 'docente' || $materia->id_docente !== Auth::id()) {
            abort(403, 'No tienes permisos para asignar estudiantes a esta materia');
        }

        // Redirigir al controlador de solicitudes
        $solicitudController = new \App\Http\Controllers\SolicitudInscripcionController();
        return $solicitudController->store($request, $materiaId);
    }

    // Método privado para asignación directa (solo admin)
    private function asignarDirectamente(Request $request, $materiaId, $materia)
    {
        $request->validate([
            'id_estudiante' => 'required|exists:usuarios,id_usuario',
        ], [
            'id_estudiante.required' => 'Debes seleccionar un estudiante',
            'id_estudiante.exists' => 'El estudiante seleccionado no existe',
        ]);

        $estudiante = User::findOrFail($request->id_estudiante);

        if ($estudiante->rol !== 'estudiante') {
            return back()->withErrors(['error' => 'Solo se pueden asignar estudiantes']);
        }

        // Validar cupos
        $estudiantesAsignados = EstudianteMateria::where('id_materia', $materiaId)->count();
        if ($materia->cupo_maximo !== null && $estudiantesAsignados >= $materia->cupo_maximo) {
            return back()->withErrors(['error' => 'No hay cupos disponibles en esta materia']);
        }

        $existe = EstudianteMateria::where('id_estudiante', $request->id_estudiante)
            ->where('id_materia', $materiaId)
            ->exists();

        if ($existe) {
            return back()->withErrors(['error' => 'El estudiante ya está asignado a esta materia']);
        }

        EstudianteMateria::create([
            'id_estudiante' => $request->id_estudiante,
            'id_materia' => $materiaId,
            'promedio' => 0.00,
            'avance' => 0.00,
        ]);

        Auditoria::create([
            'id_usuario' => Auth::id(),
            'accion' => 'Asignación directa de estudiante ' . $estudiante->nombre . ' a materia ' . $materia->nombre . ' (Admin)',
            'ip' => $request->ip(),
        ]);

        return back()->with('success', 'Estudiante asignado exitosamente');
    }

    public function destroy($materiaId, $id)
    {
        $materia = Materia::findOrFail($materiaId);
        
        if (Auth::user()->rol !== 'admin' && (Auth::user()->rol !== 'docente' || $materia->id_docente !== Auth::id())) {
            abort(403, 'No tienes permisos para realizar esta acción');
        }

        $estudianteMateria = EstudianteMateria::findOrFail($id);
        $estudiante = $estudianteMateria->estudiante;
        $materiaNombre = $materia->nombre;

        $estudianteMateria->delete();

        Auditoria::create([
            'id_usuario' => Auth::id(),
            'accion' => 'Desasignación de estudiante ' . $estudiante->nombre . ' de materia ' . $materiaNombre,
            'ip' => request()->ip(),
        ]);

        return back()->with('success', 'Estudiante desasignado exitosamente');
    }
}

