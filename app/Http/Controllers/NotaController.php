<?php

namespace App\Http\Controllers;

use App\Models\EstudianteMateria;
use App\Models\Calificacion;
use App\Models\Materia;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotaController extends Controller
{
    public function index()
    {
        $estudianteId = Auth::id();
        $materiasAsignadas = EstudianteMateria::where('id_estudiante', $estudianteId)
            ->with(['materia', 'materia.docente', 'calificaciones'])
            ->get();

        foreach ($materiasAsignadas as $estudianteMateria) {
            $estudianteMateria->promediosPorLapso = $this->calcularPromediosPorLapso($estudianteMateria->id_estudiante_materia);
        }

        return view('notas.index', compact('materiasAsignadas'));
    }

    public function show($materiaId)
    {
        $estudianteId = Auth::id();
        $estudianteMateria = EstudianteMateria::where('id_estudiante', $estudianteId)
            ->where('id_materia', $materiaId)
            ->with(['materia', 'materia.docente', 'calificaciones'])
            ->firstOrFail();

        $calificacionesPorLapso = $estudianteMateria->calificaciones->groupBy('lapso');
        
        $promediosPorLapso = $this->calcularPromediosPorLapso($estudianteMateria->id_estudiante_materia);

        return view('notas.show', compact('estudianteMateria', 'calificacionesPorLapso', 'promediosPorLapso'));
    }

    public function indexDocente($materiaId)
    {
        $materia = Materia::with(['recursos', 'docente'])->findOrFail($materiaId);
        
        if (Auth::user()->rol !== 'admin' && (Auth::user()->rol !== 'docente' || $materia->id_docente !== Auth::id())) {
            abort(403, 'No tienes permisos para ver esta materia');
        }

        $query = EstudianteMateria::where('id_materia', $materiaId)
            ->with(['estudiante', 'calificaciones'])
            ->whereHas('estudiante', function($q) {
                $q->where('estado', 'activo');
            });

        $filterGrado = request('grado');
        $filterSeccion = request('seccion');

        if ($filterGrado) {
            $query->whereHas('estudiante', function($q) use ($filterGrado) {
                $q->where('grado', $filterGrado);
            });
        }

        if ($filterSeccion) {
            $query->whereHas('estudiante', function($q) use ($filterSeccion) {
                $q->where('seccion', $filterSeccion);
            });
        }

        $estudiantes = $query->get();

        foreach ($estudiantes as $estudiante) {
            $estudiante->promediosPorLapso = $this->calcularPromediosPorLapso($estudiante->id_estudiante_materia);
        }

        return view('notas.index-docente', compact('materia', 'estudiantes', 'filterGrado', 'filterSeccion'));
    }

    public function createEdit($materiaId, $estudianteMateriaId, $lapso)
    {
        $materia = Materia::findOrFail($materiaId);
        
        if (Auth::user()->rol !== 'admin' && (Auth::user()->rol !== 'docente' || $materia->id_docente !== Auth::id())) {
            abort(403, 'No tienes permisos para gestionar notas de esta materia');
        }

        $estudianteMateria = EstudianteMateria::findOrFail($estudianteMateriaId);
        
        if ($estudianteMateria->id_materia != $materiaId) {
            abort(404);
        }

        if (!in_array($lapso, [1, 2, 3])) {
            abort(404, 'Lapso invÃ¡lido');
        }

        $calificaciones = Calificacion::where('id_estudiante_materia', $estudianteMateriaId)
            ->where('lapso', $lapso)
            ->orderBy('created_at', 'asc')
            ->get();

        $promedioLapso = $this->calcularPromedioLapso($estudianteMateriaId, $lapso);

        return view('notas.create-edit', compact('materia', 'estudianteMateria', 'lapso', 'calificaciones', 'promedioLapso'));
    }

    public function store(Request $request, $materiaId, $estudianteMateriaId, $lapso)
    {
        $materia = Materia::findOrFail($materiaId);
        
        if (Auth::user()->rol !== 'admin' && (Auth::user()->rol !== 'docente' || $materia->id_docente !== Auth::id())) {
            abort(403, 'No tienes permisos para gestionar notas de esta materia');
        }

        $estudianteMateria = EstudianteMateria::findOrFail($estudianteMateriaId);
        
        if ($estudianteMateria->id_materia != $materiaId) {
            abort(404);
        }

        if (!in_array($lapso, [1, 2, 3])) {
            abort(404, 'Lapso invÃ¡lido');
        }

        $request->validate([
            'notas' => 'required|array|min:1',
            'notas.*' => 'required|numeric|min:0|max:20',
        ], [
            'notas.required' => 'Debes ingresar al menos una nota',
            'notas.*.required' => 'Todas las notas son obligatorias',
            'notas.*.numeric' => 'Las notas deben ser nÃºmeros',
            'notas.*.min' => 'Las notas no pueden ser menores a 0',
            'notas.*.max' => 'Las notas no pueden ser mayores a 20',
        ]);

        DB::beginTransaction();
        try {
            Calificacion::where('id_estudiante_materia', $estudianteMateriaId)
                ->where('lapso', $lapso)
                ->delete();

            foreach ($request->notas as $index => $nota) {
                Calificacion::create([
                    'id_estudiante_materia' => $estudianteMateriaId,
                    'lapso' => $lapso,
                    'tipo' => 'evaluacion',
                    'nota' => $nota,
                    'porcentaje' => 100 / count($request->notas), // Porcentaje igual para todas
                ]);
            }

            $this->actualizarPromedioGeneral($estudianteMateriaId);

            Auditoria::create([
                'id_usuario' => Auth::id(),
                'accion' => "Registro de notas - Lapso {$lapso} - Estudiante: {$estudianteMateria->estudiante->nombre} - Materia: {$materia->nombre}",
                'ip' => $request->ip(),
            ]);

            DB::commit();

            return redirect()->route('notas.docente.index', $materiaId)
                ->with('success', 'Notas guardadas exitosamente. El promedio del lapso se ha calculado automÃ¡ticamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al guardar las notas: ' . $e->getMessage()]);
        }
    }

    private function calcularPromedioLapso($estudianteMateriaId, $lapso)
    {
        $calificaciones = Calificacion::where('id_estudiante_materia', $estudianteMateriaId)
            ->where('lapso', $lapso)
            ->get();

        if ($calificaciones->isEmpty()) {
            return null;
        }

        $suma = $calificaciones->sum('nota');
        $cantidad = $calificaciones->count();
        $promedio = $suma / $cantidad;

        return round($promedio, 0, PHP_ROUND_HALF_UP);
    }

    private function calcularPromediosPorLapso($estudianteMateriaId)
    {
        $promedios = [];
        for ($lapso = 1; $lapso <= 3; $lapso++) {
            $promedios[$lapso] = $this->calcularPromedioLapso($estudianteMateriaId, $lapso);
        }
        return $promedios;
    }

    private function actualizarPromedioGeneral($estudianteMateriaId)
    {
        $promedios = $this->calcularPromediosPorLapso($estudianteMateriaId);
        
        $promediosExistentes = array_filter($promedios, function($promedio) {
            return $promedio !== null;
        });

        if (empty($promediosExistentes)) {
            $promedioGeneral = 0;
        } else {
            $promedioGeneral = array_sum($promediosExistentes) / count($promediosExistentes);
            $promedioGeneral = round($promedioGeneral, 0, PHP_ROUND_HALF_UP);
        }

        $estado = $promedioGeneral >= 10 ? 'aprobado' : 'reprobado';

        EstudianteMateria::where('id_estudiante_materia', $estudianteMateriaId)
            ->update([
                'promedio' => $promedioGeneral,
                'avance' => (count($promediosExistentes) / 3) * 100,
            ]);
    }
}

