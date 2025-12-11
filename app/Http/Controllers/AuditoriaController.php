<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auditoria;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        // Security: Only Admin
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Acceso denegado');
        }

        $query = Auditoria::with('usuario')->orderBy('created_at', 'desc');

        // Filters
        if ($request->filled('id_usuario')) {
            $query->where('id_usuario', $request->id_usuario);
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        $logs = $query->paginate(20);
        $usuarios = User::all();

        return view('auditoria.index', compact('logs', 'usuarios'));
    }

    public function reporte(Request $request)
    {
        // Security: Only Admin
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Acceso denegado');
        }

        $query = Auditoria::with('usuario')->orderBy('created_at', 'desc');

        // Apply same filters for the report
        if ($request->filled('id_usuario')) {
            $query->where('id_usuario', $request->id_usuario);
        }
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        $logs = $query->limit(500)->get(); // Limit to avoid memory issues in PDF

        $pdf = Pdf::loadView('reportes.pdf_auditoria', compact('logs'));
        return $pdf->stream('reporte_auditoria.pdf');
    }
}
