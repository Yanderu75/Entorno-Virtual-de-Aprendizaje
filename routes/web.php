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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
    
    // Rutas para notificaciones
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::post('/notificaciones/{id}/leida', [NotificacionController::class, 'marcarLeida'])->name('notificaciones.leida');
    Route::post('/notificaciones/marcar-todas', [NotificacionController::class, 'marcarTodasLeidas'])->name('notificaciones.marcar-todas');
    Route::delete('/notificaciones/{id}', [NotificacionController::class, 'destroy'])->name('notificaciones.destroy');
    Route::get('/api/notificaciones/contar', [NotificacionController::class, 'contarNoLeidas'])->name('api.notificaciones.contar');
});
