<?php

namespace App\Http\Controllers;

use App\Models\SolicitudInscripcion;
use App\Models\Materia;
use App\Models\User;
use App\Models\EstudianteMateria;
use App\Models\Notificacion;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SolicitudInscripcionController extends Controller
{
    // Vista para docentes: ver sus solicitudes
    public function index()
    {
        if (Auth::user()->rol !== 'docente') {
            abort(403, 'Solo los docentes pueden ver sus solicitudes');
        }

        $solicitudes = SolicitudInscripcion::where('id_docente', Auth::id())
            ->with(['estudiante', 'materia', 'adminResolutor'])
            ->orderBy('fecha_solicitud', 'desc')
            ->get();

        return view('solicitudes.index', compact('solicitudes'));
    }

    // Crear solicitud de inscripción (Docente)
    public function store(Request $request, $materiaId)
    {
        $materia = Materia::findOrFail($materiaId);
        
        // Verificar permisos
        if (Auth::user()->rol !== 'docente' || $materia->id_docente !== Auth::id()) {
            abort(403, 'No tienes permisos para solicitar inscripciones en esta materia');
        }

        $request->validate([
            'id_estudiante' => 'required|exists:usuarios,id_usuario',
        ], [
            'id_estudiante.required' => 'Debes seleccionar un estudiante',
            'id_estudiante.exists' => 'El estudiante seleccionado no existe',
        ]);

        $estudiante = User::findOrFail($request->id_estudiante);

        // Validar que sea estudiante
        if ($estudiante->rol !== 'estudiante') {
            return back()->withErrors(['error' => 'Solo se pueden inscribir estudiantes']);
        }

        // Validar que el estudiante esté activo
        if ($estudiante->estado !== 'activo') {
            return back()->withErrors(['error' => 'El estudiante debe estar activo']);
        }

        // Validar que no esté ya asignado
        $yaAsignado = EstudianteMateria::where('id_estudiante', $request->id_estudiante)
            ->where('id_materia', $materiaId)
            ->exists();

        if ($yaAsignado) {
            return back()->withErrors(['error' => 'El estudiante ya está asignado a esta materia']);
        }

        // Validar que no haya una solicitud pendiente
        $solicitudPendiente = SolicitudInscripcion::where('id_estudiante', $request->id_estudiante)
            ->where('id_materia', $materiaId)
            ->where('estado', 'pendiente')
            ->exists();

        if ($solicitudPendiente) {
            return back()->withErrors(['error' => 'Ya existe una solicitud pendiente para este estudiante en esta materia']);
        }

        // Validar cupos disponibles
        $estudiantesAsignados = EstudianteMateria::where('id_materia', $materiaId)->count();
        if ($materia->cupo_maximo !== null && $estudiantesAsignados >= $materia->cupo_maximo) {
            return back()->withErrors(['error' => 'No hay cupos disponibles en esta materia']);
        }

        DB::beginTransaction();
        try {
            // Crear solicitud
            $solicitud = SolicitudInscripcion::create([
                'id_docente' => Auth::id(),
                'id_estudiante' => $request->id_estudiante,
                'id_materia' => $materiaId,
                'estado' => 'pendiente',
            ]);

            // Notificar a administradores
            $admins = User::where('rol', 'admin')->where('estado', 'activo')->get();
            foreach ($admins as $admin) {
                Notificacion::crearNotificacion(
                    $admin->id_usuario,
                    'otro',
                    'Nueva solicitud de inscripción',
                    "El docente {$materia->docente->nombre} ha solicitado inscribir al estudiante {$estudiante->nombre} en la materia {$materia->nombre}."
                );
            }

            // Registrar en auditoría
            Auditoria::create([
                'id_usuario' => Auth::id(),
                'accion' => "Solicitud de inscripción - Estudiante: {$estudiante->nombre} - Materia: {$materia->nombre}",
                'ip' => $request->ip(),
            ]);

            DB::commit();

            return back()->with('success', 'Solicitud de inscripción enviada exitosamente. Esperando aprobación del administrador.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al crear la solicitud: ' . $e->getMessage()]);
        }
    }

    // Vista para administradores: ver solicitudes pendientes
    public function pendientes()
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden ver solicitudes pendientes');
        }

        $solicitudes = SolicitudInscripcion::where('estado', 'pendiente')
            ->with(['docente', 'estudiante', 'materia'])
            ->orderBy('fecha_solicitud', 'asc')
            ->get();

        return view('solicitudes.pendientes', compact('solicitudes'));
    }

    // Aprobar solicitud (Administrador)
    public function aprobar($id)
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden aprobar solicitudes');
        }

        $solicitud = SolicitudInscripcion::findOrFail($id);

        if ($solicitud->estado !== 'pendiente') {
            return back()->withErrors(['error' => 'Esta solicitud ya fue procesada']);
        }

        // Validar cupos nuevamente (por si cambió desde que se creó)
        $materia = $solicitud->materia;
        $estudiantesAsignados = EstudianteMateria::where('id_materia', $solicitud->id_materia)->count();
        if ($materia->cupo_maximo !== null && $estudiantesAsignados >= $materia->cupo_maximo) {
            return back()->withErrors(['error' => 'No hay cupos disponibles en esta materia']);
        }

        // Validar que no esté ya asignado
        $yaAsignado = EstudianteMateria::where('id_estudiante', $solicitud->id_estudiante)
            ->where('id_materia', $solicitud->id_materia)
            ->exists();

        if ($yaAsignado) {
            return back()->withErrors(['error' => 'El estudiante ya está asignado a esta materia']);
        }

        DB::beginTransaction();
        try {
            // Crear asignación definitiva
            EstudianteMateria::create([
                'id_estudiante' => $solicitud->id_estudiante,
                'id_materia' => $solicitud->id_materia,
                'promedio' => 0.00,
                'avance' => 0.00,
            ]);

            // Actualizar solicitud
            $solicitud->update([
                'estado' => 'aprobada',
                'fecha_resolucion' => now(),
                'id_admin_resolutor' => Auth::id(),
            ]);

            // Notificar al docente
            Notificacion::crearNotificacion(
                $solicitud->id_docente,
                'inscripcion_aprobada',
                'Solicitud de inscripción aprobada',
                "Su solicitud para inscribir al estudiante {$solicitud->estudiante->nombre} en la materia {$solicitud->materia->nombre} ha sido aprobada."
            );

            // Notificar al estudiante
            Notificacion::crearNotificacion(
                $solicitud->id_estudiante,
                'inscripcion_aprobada',
                'Inscripción aprobada',
                "Has sido inscrito en la materia {$solicitud->materia->nombre} por el docente {$solicitud->docente->nombre}."
            );

            // Registrar en auditoría
            Auditoria::create([
                'id_usuario' => Auth::id(),
                'accion' => "Aprobación de solicitud de inscripción - Estudiante: {$solicitud->estudiante->nombre} - Materia: {$solicitud->materia->nombre}",
                'ip' => request()->ip(),
            ]);

            DB::commit();

            return back()->with('success', 'Solicitud aprobada exitosamente. El estudiante ha sido inscrito en la materia.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al aprobar la solicitud: ' . $e->getMessage()]);
        }
    }

    // Rechazar solicitud (Administrador)
    public function rechazar(Request $request, $id)
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden rechazar solicitudes');
        }

        $request->validate([
            'motivo_rechazo' => 'required|string|min:10|max:500',
        ], [
            'motivo_rechazo.required' => 'Debes proporcionar un motivo de rechazo',
            'motivo_rechazo.min' => 'El motivo debe tener al menos 10 caracteres',
            'motivo_rechazo.max' => 'El motivo no puede exceder 500 caracteres',
        ]);

        $solicitud = SolicitudInscripcion::findOrFail($id);

        if ($solicitud->estado !== 'pendiente') {
            return back()->withErrors(['error' => 'Esta solicitud ya fue procesada']);
        }

        DB::beginTransaction();
        try {
            // Actualizar solicitud
            $solicitud->update([
                'estado' => 'rechazada',
                'motivo_rechazo' => $request->motivo_rechazo,
                'fecha_resolucion' => now(),
                'id_admin_resolutor' => Auth::id(),
            ]);

            // Notificar al docente
            Notificacion::crearNotificacion(
                $solicitud->id_docente,
                'inscripcion_rechazada',
                'Solicitud de inscripción rechazada',
                "Su solicitud para inscribir al estudiante {$solicitud->estudiante->nombre} en la materia {$solicitud->materia->nombre} ha sido rechazada. Motivo: {$request->motivo_rechazo}"
            );

            // Registrar en auditoría
            Auditoria::create([
                'id_usuario' => Auth::id(),
                'accion' => "Rechazo de solicitud de inscripción - Estudiante: {$solicitud->estudiante->nombre} - Materia: {$solicitud->materia->nombre}",
                'ip' => request()->ip(),
            ]);

            DB::commit();

            return back()->with('success', 'Solicitud rechazada exitosamente. El docente ha sido notificado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al rechazar la solicitud: ' . $e->getMessage()]);
        }
    }
}

