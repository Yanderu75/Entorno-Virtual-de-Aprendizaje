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
        return view('dashboard.estudiante');
    }

    public function docente()
    {
        return view('dashboard.docente');
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
