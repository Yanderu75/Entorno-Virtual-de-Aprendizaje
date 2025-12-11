<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use App\Models\Materia;
use App\Models\Pregunta;
use App\Models\IntentoExamen;
use App\Models\RespuestaExamen;
use App\Models\Auditoria;
use App\Models\EstudianteMateria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamenController extends Controller
{

    public function indexDocente()
    {
        $userId = Auth::id();
        $materias = Materia::where('id_docente', $userId)->pluck('id_materia');
        
        $examenes = Examen::whereIn('id_materia', $materias)
            ->with(['materia', 'preguntas'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('examenes.index_docente', compact('examenes'));
    }

    public function create()
    {
        if (Auth::user()->rol !== 'docente' && Auth::user()->rol !== 'admin') {
            abort(403);
        }

        $materias = Materia::where('id_docente', Auth::id())->get();
        return view('examenes.create', compact('materias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_materia' => 'required|exists:materias,id_materia',
            'lapso' => 'required|in:1,2,3',
            'numero_evaluacion' => 'required|integer|min:1',
            'titulo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        $examen = Examen::create([
            'id_materia' => $request->id_materia,
            'lapso' => $request->lapso,
            'numero_evaluacion' => $request->numero_evaluacion,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'publicado' => false,
        ]);

        Auditoria::create([
            'id_usuario' => Auth::id(),
            'accion' => 'CreaciÃ³n de examen: ' . $examen->titulo,
            'ip' => request()->ip(),
        ]);

        return redirect()->route('examenes.gestion', $examen->id)->with('success', 'Examen creado correctamente.');
    }

    public function edit($id)
    {
        $examen = Examen::findOrFail($id);
        
        if ($examen->materia->id_docente !== Auth::id() && Auth::user()->rol !== 'admin') {
            abort(403);
        }

        $materias = Materia::where('id_docente', Auth::id())->get();
        return view('examenes.edit', compact('examen', 'materias'));
    }

    public function update(Request $request, $id)
    {
        $examen = Examen::findOrFail($id);
        
        if ($examen->materia->id_docente !== Auth::id() && Auth::user()->rol !== 'admin') {
            abort(403);
        }

        $request->validate([
            'id_materia' => 'required|exists:materias,id_materia',
            'lapso' => 'required|in:1,2,3',
            'numero_evaluacion' => 'required|integer|min:1',
            'titulo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        $examen->update([
            'id_materia' => $request->id_materia,
            'lapso' => $request->lapso,
            'numero_evaluacion' => $request->numero_evaluacion,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        return redirect()->route('examenes.gestion', $examen->id)
            ->with('success', 'Examen actualizado correctamente.');
    }

    public function gestion($id)
    {
        $examen = Examen::with(['preguntas', 'materia'])->findOrFail($id);
        
        if ($examen->materia->id_docente !== Auth::id() && Auth::user()->rol !== 'admin') {
            abort(403);
        }

        return view('examenes.gestion', compact('examen'));
    }

    public function publicar($id)
    {
        $examen = Examen::findOrFail($id);
        
        if ($examen->materia->id_docente !== Auth::id() && Auth::user()->rol !== 'admin') {
            abort(403);
        }

        $examen->update(['publicado' => !$examen->publicado]);
        
        $status = $examen->publicado ? 'publicado' : 'ocultado';
        return back()->with('success', "Examen {$status} correctamente.");
    }

    public function destroy($id)
    {
        $examen = Examen::findOrFail($id);
        
        if ($examen->materia->id_docente !== Auth::id() && Auth::user()->rol !== 'admin') {
            abort(403);
        }

        $examen->delete();
        return redirect()->route('examenes.index_docente')->with('success', 'Examen eliminado.');
    }


    public function storePregunta(Request $request, $examenId)
    {
        $request->validate([
            'enunciado' => 'required|string',
            'tipo' => 'required|in:opcion_simple,verdadero_falso,abierta',
            'puntaje' => 'required|numeric|min:0',
        ]);

        $opciones = null;
        $respuestaCorrecta = null;

        if ($request->tipo == 'opcion_simple') {
            $opciones = array_filter($request->opciones ?? []);
            $respuestaCorrecta = $request->respuesta_correcta;
        } elseif ($request->tipo == 'verdadero_falso') {
            $respuestaCorrecta = $request->respuesta_correcta;
        }

        Pregunta::create([
            'id_examen' => $examenId,
            'enunciado' => $request->enunciado,
            'tipo' => $request->tipo,
            'puntaje' => $request->puntaje,
            'opciones' => $opciones,
            'respuesta_correcta' => $respuestaCorrecta,
        ]);

        return back()->with('success', 'Pregunta agregada correctamente.');
    }

    public function destroyPregunta($id)
    {
        $pregunta = Pregunta::findOrFail($id);
        $pregunta->delete();
        return back()->with('success', 'Pregunta eliminada.');
    }


    public function indexEstudiante()
    {
        $user = Auth::user();
        
        $materiasIds = EstudianteMateria::where('id_estudiante', $user->id_usuario)
            ->pluck('id_materia');

        $examenes = Examen::whereIn('id_materia', $materiasIds)
            ->where('publicado', true)
            ->with('materia')
            ->orderBy('fecha_inicio', 'desc')
            ->get();
        
        foreach ($examenes as $examen) {
            $examen->intento = IntentoExamen::where('id_examen', $examen->id)
                ->where('id_estudiante', $user->id_usuario)
                ->first();
        }

        return view('examenes.index_estudiante', compact('examenes'));
    }

    public function presentar($id)
    {
        $examen = Examen::with('preguntas')->findOrFail($id);
        
        $isEnrolled = EstudianteMateria::where('id_estudiante', Auth::id())
            ->where('id_materia', $examen->id_materia)
            ->exists();

        if (!$isEnrolled) {
            abort(403, 'No estÃ¡s inscrito en esta materia.');
        }

        $intentoPrevio = IntentoExamen::where('id_examen', $id)
            ->where('id_estudiante', Auth::id())
            ->first();

        if ($intentoPrevio) {
            return redirect()->route('examenes.index_estudiante')
                ->with('error', 'Ya has presentado este examen.');
        }

        return view('examenes.presentar', compact('examen'));
    }

    public function guardarIntento(Request $request, $id)
    {
        $examen = Examen::with(['preguntas', 'materia'])->findOrFail($id);
        $user = Auth::user();
        
        DB::beginTransaction();
        try {
            $puntajeMaximo = $examen->preguntas->sum('puntaje');
            
            $intento = IntentoExamen::create([
                'id_examen' => $id,
                'id_estudiante' => $user->id_usuario,
                'fecha_entregado' => now(),
                'nota_final' => 0,
                'correccion_docente' => false
            ]);

            $puntajeObtenido = 0;
            $requiereRevision = false;

            foreach ($examen->preguntas as $pregunta) {
                $respuestaTexto = $request->input("respuesta_{$pregunta->id}");
                $puntos = 0;

                if ($pregunta->tipo == 'abierta') {
                    $requiereRevision = true;
                } else {
                    $respuestaAlumno = mb_strtolower(trim($respuestaTexto ?? ''));
                    $respuestaCorrecta = mb_strtolower(trim($pregunta->respuesta_correcta ?? ''));
                    
                    \Log::info('Comparando respuestas', [
                        'pregunta_id' => $pregunta->id,
                        'respuesta_alumno' => $respuestaAlumno,
                        'respuesta_correcta' => $respuestaCorrecta,
                        'coincide' => $respuestaAlumno === $respuestaCorrecta
                    ]);
                    
                    if ($respuestaAlumno === $respuestaCorrecta) {
                        $puntos = $pregunta->puntaje;
                    }
                }
                
                $puntajeObtenido += $puntos;

                RespuestaExamen::create([
                    'id_intento' => $intento->id,
                    'id_pregunta' => $pregunta->id,
                    'respuesta_texto' => $respuestaTexto,
                    'puntaje_obtenido' => $puntos
                ]);
            }

            $notaSobre20 = $puntajeMaximo > 0 ? ($puntajeObtenido / $puntajeMaximo) * 20 : 0;
            $notaSobre20 = round($notaSobre20, 2);
            
            \Log::info('CÃ¡lculo final', [
                'puntaje_maximo' => $puntajeMaximo,
                'puntaje_obtenido' => $puntajeObtenido,
                'nota_sobre_20' => $notaSobre20
            ]);

            $intento->update([
                'nota_final' => $notaSobre20,
                'correccion_docente' => !$requiereRevision
            ]);

            if (!$requiereRevision) {
                $this->guardarEnCalificaciones($examen, $user->id_usuario, $notaSobre20);
            }

            Auditoria::create([
                'id_usuario' => $user->id_usuario,
                'accion' => 'PresentaciÃ³n de examen: ' . $examen->titulo,
                'ip' => request()->ip(),
            ]);
            
            DB::commit();
            return redirect()->route('examenes.index_estudiante')
                ->with('success', 'Examen entregado correctamente. Tu nota es: ' . $notaSobre20 . '/20');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar el examen: ' . $e->getMessage());
        }
    }

    private function guardarEnCalificaciones($examen, $estudianteId, $nota)
    {
        $estudianteMateria = \App\Models\EstudianteMateria::where('id_estudiante', $estudianteId)
            ->where('id_materia', $examen->id_materia)
            ->first();

        if ($estudianteMateria) {
            \App\Models\Calificacion::create([
                'id_estudiante_materia' => $estudianteMateria->id_estudiante_materia,
                'lapso' => $examen->lapso,
                'tipo' => 'evaluacion',
                'nota' => $nota,
                'porcentaje' => 100, // Will be recalculated in aggregate
            ]);

            $this->actualizarPromedioLapso($estudianteMateria->id_estudiante_materia, $examen->lapso);
        }
    }

    private function actualizarPromedioLapso($estudianteMateriaId, $lapso)
    {
        $calificaciones = \App\Models\Calificacion::where('id_estudiante_materia', $estudianteMateriaId)
            ->where('lapso', $lapso)
            ->get();

        if ($calificaciones->isEmpty()) {
            return;
        }

        $suma = $calificaciones->sum('nota');
        $cantidad = $calificaciones->count();
        $promedio = $suma / $cantidad;

        $estudianteMateria = \App\Models\EstudianteMateria::find($estudianteMateriaId);
        if ($estudianteMateria) {
            $promedios = [];
            for ($l = 1; $l <= 3; $l++) {
                $cals = \App\Models\Calificacion::where('id_estudiante_materia', $estudianteMateriaId)
                    ->where('lapso', $l)
                    ->get();
                if ($cals->isNotEmpty()) {
                    $promedios[$l] = $cals->sum('nota') / $cals->count();
                }
            }
            
            $promediosExistentes = array_filter($promedios);
            if (!empty($promediosExistentes)) {
                $promedioGeneral = array_sum($promediosExistentes) / count($promediosExistentes);
                $estudianteMateria->update([
                    'promedio' => round($promedioGeneral, 0, PHP_ROUND_HALF_UP),
                    'avance' => (count($promediosExistentes) / 3) * 100,
                ]);
            }
        }
    }


    public function revisar($intentoId)
    {
        $intento = IntentoExamen::with(['examen.materia', 'estudiante', 'respuestas.pregunta'])->findOrFail($intentoId);
        
        if ($intento->examen->materia->id_docente !== Auth::id() && Auth::user()->rol !== 'admin') {
            abort(403);
        }

        return view('examenes.revisar', compact('intento'));
    }

    public function guardarCorreccion(Request $request, $intentoId)
    {
        $intento = IntentoExamen::with('respuestas')->findOrFail($intentoId);
        
        $notaTotal = 0;
        
        foreach ($intento->respuestas as $respuesta) {
            $puntaje = $request->input("puntaje_{$respuesta->id}", $respuesta->puntaje_obtenido);
            $respuesta->update(['puntaje_obtenido' => $puntaje]);
            $notaTotal += $puntaje;
        }

        $intento->update([
            'nota_final' => $notaTotal,
            'correccion_docente' => true
        ]);

        return back()->with('success', 'CorrecciÃ³n guardada. Nota final: ' . $notaTotal);
    }
}
