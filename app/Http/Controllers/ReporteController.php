<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EstudianteMateria;
use App\Models\Calificacion;
use App\Models\Materia;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function usuarios(Request $request)
    {
        $usuarios = User::orderBy('rol')->get();

        if ($request->format == 'csv') {
            $filename = "usuarios_" . date('Y-m-d') . ".csv";
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=$filename",
            ];
            
            $callback = function() use ($usuarios) {
                $file = fopen('php://output', 'w');
                // Add Byte Order Mark (BOM) for Excel UTF-8 compatibility
                fputs($file, "\xEF\xBB\xBF"); 
                fputcsv($file, ['ID', 'Nombre', 'Email', 'Rol', 'Fecha Registro']);
                
                foreach ($usuarios as $user) {
                    fputcsv($file, [
                        $user->id_usuario, 
                        $user->nombre, 
                        $user->correo, 
                        $user->rol, 
                        $user->created_at
                    ]);
                }
                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        }

        // PDF Default
        $pdf = Pdf::loadView('reportes.pdf_usuarios', compact('usuarios'));
        return $pdf->stream('usuarios.pdf');
    }

    public function rendimiento(Request $request)
    {
        // Use EstudianteMateria as it links Student + Materia (+ Average)
        $registros = EstudianteMateria::with(['estudiante', 'materia', 'calificaciones'])->get();
        
        // Calculate lapsos separately as they are aggregate of individual qualifications
        // Or simply iterate and calculate on the fly for the report
        
        if ($request->format == 'csv') {
            $filename = "rendimiento_academico_" . date('Y-m-d') . ".csv";
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=$filename",
            ];

            $callback = function() use ($registros) {
                $file = fopen('php://output', 'w');
                // Add Byte Order Mark (BOM)
                fputs($file, "\xEF\xBB\xBF");
                fputcsv($file, ['Estudiante', 'Materia', 'Lapso 1', 'Lapso 2', 'Lapso 3', 'Promedio General']);
                
                foreach ($registros as $registro) {
                    // Logic to calc average per lapso manually if not stored
                    $l1 = $this->calcPromedioLapso($registro, 1);
                    $l2 = $this->calcPromedioLapso($registro, 2);
                    $l3 = $this->calcPromedioLapso($registro, 3);
                    
                    fputcsv($file, [
                        $registro->estudiante->nombre ?? 'N/A',
                        $registro->materia->nombre ?? 'N/A',
                        $l1,
                        $l2,
                        $l3,
                        $registro->promedio // Stored average from NotaController logic
                    ]);
                }
                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        }

        // Prepare data for PDF view
        foreach ($registros as $registro) {
            $registro->l1 = $this->calcPromedioLapso($registro, 1);
            $registro->l2 = $this->calcPromedioLapso($registro, 2);
            $registro->l3 = $this->calcPromedioLapso($registro, 3);
        }

        $pdf = Pdf::loadView('reportes.pdf_rendimiento', compact('registros'));
        return $pdf->stream('rendimiento.pdf');
    }

    private function calcPromedioLapso($estudianteMateria, $lapso)
    {
        $notas = $estudianteMateria->calificaciones->where('lapso', $lapso);
        if ($notas->count() > 0) {
            return round($notas->avg('nota'), 2);
        }
        return '-';
    }

    public function materias(Request $request)
    {
        $materias = Materia::with(['docente', 'estudiantes'])->orderBy('grado')->orderBy('seccion')->get();

        if ($request->format == 'csv') {
            $filename = "materias_asignaciones_" . date('Y-m-d') . ".csv";
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=$filename",
            ];

            $callback = function() use ($materias) {
                $file = fopen('php://output', 'w');
                fputs($file, "\xEF\xBB\xBF"); // BOM
                fputcsv($file, ['Materia', 'Grado', 'Sección', 'Docente', 'Estudiantes Inscritos', 'Horario']);
                
                foreach ($materias as $materia) {
                    fputcsv($file, [
                        $materia->nombre,
                        $materia->grado,
                        $materia->seccion,
                        $materia->docente->nombre ?? 'SIN ASIGNAR',
                        $materia->estudiantes->count(),
                        $materia->horario ?? 'No definido'
                    ]);
                }
                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        }
        
        $pdf = Pdf::loadView('reportes.pdf_materias', compact('materias'));
        return $pdf->stream('reporte_materias.pdf');
    }
}

