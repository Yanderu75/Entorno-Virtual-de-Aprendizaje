<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\EstudianteMateriaController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\SolicitudInscripcionController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\ReporteController;

Route::get('/', function () {
    if (Illuminate\Support\Facades\Auth::check()) {
        $role = Illuminate\Support\Facades\Auth::user()->rol;
        switch ($role) {
            case 'admin': return redirect()->route('dashboard.admin');
            case 'docente': return redirect()->route('dashboard.docente');
            case 'estudiante': return redirect()->route('dashboard.estudiante');
            // default fallback
            default: return redirect()->route('dashboard.estudiante'); 
        }
    }
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout']); // Allow GET for sidebar link

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/estudiante', [DashboardController::class, 'estudiante'])->name('dashboard.estudiante');
    Route::get('/dashboard/docente', [DashboardController::class, 'docente'])->name('dashboard.docente');
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');

    Route::resource('users', UserController::class);
    Route::resource('materias', MateriaController::class);
    
    Route::get('/materias/{materia}/asignar', [EstudianteMateriaController::class, 'index'])->name('materias.asignar');
    Route::post('/materias/{materia}/asignar', [EstudianteMateriaController::class, 'store'])->name('materias.asignar.store');
    Route::delete('/materias/{materia}/asignar/{id}', [EstudianteMateriaController::class, 'destroy'])->name('materias.asignar.destroy');
    
    // Rutas para estudiantes: ver sus notas
    Route::get('/notas', [NotaController::class, 'index'])->name('notas.index');
    Route::get('/notas/{materia}', [NotaController::class, 'show'])->name('notas.show');
    
    // Rutas para docentes: gestionar notas por lapso
    Route::get('/materias/{materia}/notas', [NotaController::class, 'indexDocente'])->name('notas.docente.index');
    Route::get('/materias/{materia}/notas/{estudianteMateria}/{lapso}', [NotaController::class, 'createEdit'])->name('notas.docente.create-edit');
    Route::post('/materias/{materia}/notas/{estudianteMateria}/{lapso}', [NotaController::class, 'store'])->name('notas.docente.store');
    
    // Rutas para solicitudes de inscripción (Docentes)
    Route::get('/solicitudes', [SolicitudInscripcionController::class, 'index'])->name('solicitudes.index');
    
    // Rutas para solicitudes de inscripción (Administradores)
    Route::get('/admin/solicitudes/pendientes', [SolicitudInscripcionController::class, 'pendientes'])->name('admin.solicitudes.pendientes');
    Route::post('/admin/solicitudes/{id}/aprobar', [SolicitudInscripcionController::class, 'aprobar'])->name('admin.solicitudes.aprobar');
    Route::post('/admin/solicitudes/{id}/rechazar', [SolicitudInscripcionController::class, 'rechazar'])->name('admin.solicitudes.rechazar');
    
    // Rutas para Notificaciones
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::post('/notificaciones/{id}/leida', [NotificacionController::class, 'marcarLeida'])->name('notificaciones.leida');
    Route::post('/notificaciones/marcar-todas', [NotificacionController::class, 'marcarTodasLeidas'])->name('notificaciones.marcar-todas');
    Route::delete('/notificaciones/{id}', [NotificacionController::class, 'destroy'])->name('notificaciones.destroy');
    Route::get('/api/notificaciones/contar', [NotificacionController::class, 'contarNoLeidas'])->name('api.notificaciones.contar');

    // Rutas para Mensajería (Chat)
    Route::get('/mensajes', [MensajeController::class, 'index'])->name('mensajes.index');
    Route::get('/mensajes/enviados', [MensajeController::class, 'sent'])->name('mensajes.sent');
    Route::get('/mensajes/crear', [MensajeController::class, 'create'])->name('mensajes.create');
    Route::post('/mensajes', [MensajeController::class, 'store'])->name('mensajes.store');
    Route::get('/mensajes/{id}', [MensajeController::class, 'show'])->name('mensajes.show');

    // Rutas para Calendario / Eventos
    Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
    Route::delete('/eventos/{id}', [EventoController::class, 'destroy'])->name('eventos.destroy');

    // Rutas para Recursos Educativos
    Route::get('/recursos', [RecursoController::class, 'index'])->name('recursos.index');
    Route::get('/recursos/crear', [RecursoController::class, 'create'])->name('recursos.create');
    Route::post('/recursos', [RecursoController::class, 'store'])->name('recursos.store');
    Route::delete('/recursos/{id}', [RecursoController::class, 'destroy'])->name('recursos.destroy');
    Route::get('/recursos/{id}/download', [RecursoController::class, 'download'])->name('recursos.download');

    // Rutas para Reportes
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/usuarios', [ReporteController::class, 'usuarios'])->name('reportes.usuarios');
    Route::get('/reportes/rendimiento', [ReporteController::class, 'rendimiento'])->name('reportes.rendimiento');
    Route::get('/reportes/materias', [ReporteController::class, 'materias'])->name('reportes.materias'); // Nueva para materias

    // Rutas para Auditoría
    Route::get('/auditoria', [App\Http\Controllers\AuditoriaController::class, 'index'])->name('auditoria.index');
    Route::get('/auditoria/reporte', [App\Http\Controllers\AuditoriaController::class, 'reporte'])->name('auditoria.reporte');

    // Rutas para Respaldos
    Route::get('/respaldos', [App\Http\Controllers\BackupController::class, 'index'])->name('respaldos.index');
    Route::post('/respaldos', [App\Http\Controllers\BackupController::class, 'create'])->name('respaldos.create');

    // Rutas para Exámenes
    Route::get('/examenes/docente', [App\Http\Controllers\ExamenController::class, 'indexDocente'])->name('examenes.index_docente');
    Route::get('/examenes/crear', [App\Http\Controllers\ExamenController::class, 'create'])->name('examenes.create');
    Route::post('/examenes', [App\Http\Controllers\ExamenController::class, 'store'])->name('examenes.store');
    Route::get('/examenes/{id}/editar', [App\Http\Controllers\ExamenController::class, 'edit'])->name('examenes.edit');
    Route::put('/examenes/{id}', [App\Http\Controllers\ExamenController::class, 'update'])->name('examenes.update');
    Route::get('/examenes/{id}/gestion', [App\Http\Controllers\ExamenController::class, 'gestion'])->name('examenes.gestion');
    Route::post('/examenes/{id}/publicar', [App\Http\Controllers\ExamenController::class, 'publicar'])->name('examenes.publicar');
    Route::delete('/examenes/{id}', [App\Http\Controllers\ExamenController::class, 'destroy'])->name('examenes.destroy');
    
    // Preguntas
    Route::post('/examenes/{id}/preguntas', [App\Http\Controllers\ExamenController::class, 'storePregunta'])->name('examenes.preguntas.store');
    Route::delete('/preguntas/{id}', [App\Http\Controllers\ExamenController::class, 'destroyPregunta'])->name('preguntas.destroy');
    
    // Estudiante
    Route::get('/examenes', [App\Http\Controllers\ExamenController::class, 'indexEstudiante'])->name('examenes.index_estudiante');
    Route::get('/examenes/{id}/presentar', [App\Http\Controllers\ExamenController::class, 'presentar'])->name('examenes.presentar');
    Route::post('/examenes/{id}/guardar', [App\Http\Controllers\ExamenController::class, 'guardarIntento'])->name('examenes.guardar');
    
    // Corrección
    Route::get('/examenes/revisar/{intento}', [App\Http\Controllers\ExamenController::class, 'revisar'])->name('examenes.revisar');
    Route::post('/examenes/revisar/{intento}', [App\Http\Controllers\ExamenController::class, 'guardarCorreccion'])->name('examenes.correccion.guardar');
});
