<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Auditoria;
use App\Models\Materia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function estudiante()
    {
        $userId = auth()->id();
        $materiasInscritas = \App\Models\EstudianteMateria::where('id_estudiante', $userId)->count();

        $promedioGeneral = \App\Models\EstudianteMateria::where('id_estudiante', $userId)->avg('promedio') ?? 0;
        
        return view('dashboard.estudiante', [
            'materiasInscritas' => $materiasInscritas,
            'promedioGeneral' => number_format($promedioGeneral, 2),
        ]);
    }

    public function docente()
    {
        $userId = auth()->id();
        $totalMaterias = \App\Models\Materia::where('id_docente', $userId)->count();

        $totalEstudiantes = \App\Models\EstudianteMateria::whereHas('materia', function($query) use ($userId){
            $query->where('id_docente', $userId);
        })->distinct('id_estudiante')->count('id_estudiante');
        
        $solicitudesPendientes = \App\Models\SolicitudInscripcion::where('id_docente', $userId)
                                ->where('estado', 'pendiente')
                                ->count();

        return view('dashboard.docente', [
            'totalMaterias' => $totalMaterias,
            'totalEstudiantes' => $totalEstudiantes,
            'solicitudesPendientes' => $solicitudesPendientes,
        ]);
    }

    public function admin()
    {
        $totalUsuarios = User::count();
        $totalMaterias = Materia::count();
        $ultimosEventos = Auditoria::orderBy('fecha', 'desc')->limit(10)->get();

        return view('dashboard.admin', [
            'totalUsuarios' => $totalUsuarios,
            'totalMaterias' => $totalMaterias,
            'ultimosEventos' => $ultimosEventos,
        ]);
    }
}
