<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recurso;
use App\Models\Materia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class RecursoController extends Controller
{
    public function index(Request $request)
    {
        $query = Recurso::with(['materia.docente']);

        if ($request->has('grado') && $request->grado != '') {
            $query->whereHas('materia', function($q) use ($request) {
                $q->where('grado', $request->grado);
            });
        }

        if ($request->has('docente') && $request->docente != '') {
            $query->whereHas('materia.docente', function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->docente . '%');
            });
        }

        $recursos = $query->latest()->get();


        return view('recursos.index', compact('recursos'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->rol == 'admin') {
            $materias = Materia::all();
        } else {
            $materias = Materia::where('id_docente', $user->id_usuario)->get();
        }

        return view('recursos.create', compact('materias'));
    }

    public function store(Request $request, $idMateria = null)
    {
        $materiaId = $idMateria ?? $request->input('id_materia');

        if (!$materiaId) {
             return redirect()->back()->with('error', 'Materia no especificada.');
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'archivo' => 'required|file|max:20480', // Max 20MB
        ]);

        $materia = Materia::findOrFail($materiaId);
        
        if (Auth::user()->rol != 'admin' && Auth::id() != $materia->id_docente) {
             abort(403, 'No tienes permiso para agregar recursos a esta materia.');
        }

        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('recursos', $filename, 'public');

            Recurso::create([
                'id_materia' => $materia->id_materia,
                'titulo' => $request->titulo,
                'tipo' => $file->getClientOriginalExtension(), // pdf, doc, jpg, etc.
                'ruta' => $path,
            ]);

            return redirect()->route('recursos.index')->with('success', 'Recurso subido correctamente.');
        }

        return redirect()->back()->with('error', 'Error al subir el archivo.');
    }

    public function destroy($id)
    {
        $recurso = Recurso::findOrFail($id);
        
        $materia = $recurso->materia;
        if (Auth::user()->rol != 'admin' && Auth::id() != $materia->id_docente) {
            abort(403);
        }

        if (Storage::disk('public')->exists($recurso->ruta)) {
            Storage::disk('public')->delete($recurso->ruta);
        }

        $recurso->delete();

        return redirect()->back()->with('success', 'Recurso eliminado correctamente.');
    }

    public function download($id)
    {
        $recurso = Recurso::findOrFail($id);
        
        if (Storage::disk('public')->exists($recurso->ruta)) {
            return Storage::disk('public')->download($recurso->ruta, $recurso->titulo . '.' . $recurso->tipo);
        }
        
        return redirect()->back()->with('error', 'El archivo no existe.');
    }
}
