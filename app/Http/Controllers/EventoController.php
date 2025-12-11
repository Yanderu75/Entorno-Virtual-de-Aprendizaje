<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Materia;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $query = Evento::query();

            if ($user->rol == 'estudiante') {
                 $materiasIds = \App\Models\EstudianteMateria::where('id_estudiante', $user->id_usuario)->pluck('id_materia');
                 $query->whereIn('id_materia', $materiasIds)
                       ->orWhereNull('id_materia');
            } elseif ($user->rol == 'docente') {
                $query->where('id_usuario', $user->id_usuario)
                      ->orWhereIn('id_materia', Materia::where('id_docente', $user->id_usuario)->pluck('id_materia'));
            }
            
            $eventos = $query->get(['id_evento as id', 'titulo as title', 'fecha_inicio as start', 'fecha_fin as end', 'tipo']);
            
            $formattedEvents = $eventos->map(function($ev) {
                $color = '#3788d8'; // default blue
                if($ev->tipo == 'examen') $color = '#dc3545'; // red
                if($ev->tipo == 'entrega') $color = '#ffc107'; // yellow
                
                return [
                    'id' => $ev->id,
                    'title' => $ev->title,
                    'start' => $ev->start,
                    'end' => $ev->end,
                    'backgroundColor' => $color,
                    'borderColor' => $color
                ];
            });

            return response()->json($formattedEvents);
        }

        $materias = [];
        if (Auth::user()->rol == 'docente') {
            $materias = Materia::where('id_docente', Auth::id())->get();
        } elseif (Auth::user()->rol == 'admin') {
            $materias = Materia::all();
        }

        return view('eventos.index', compact('materias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'tipo' => 'required|in:general,clase,examen,entrega',
            'id_materia' => 'nullable|exists:materias,id_materia',
        ]);

        Evento::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'tipo' => $request->tipo,
            'id_materia' => $request->id_materia,
            'id_usuario' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Evento creado correctamente.');
    }

    public function destroy($id)
    {
        $evento = Evento::findOrFail($id);
        
        if (Auth::user()->rol != 'admin' && Auth::id() != $evento->id_usuario) {
            abort(403);
        }

        $evento->delete();
        return response()->json(['success' => true]);
    }
}
