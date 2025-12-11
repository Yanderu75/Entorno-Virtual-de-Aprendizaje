<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\EstudianteMateria;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MateriaController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->rol === 'estudiante') {
            $materiasAsignadas = EstudianteMateria::where('id_estudiante', Auth::id())
                ->with('materia.docente')
                ->get();
            return view('materias.index-estudiante', compact('materiasAsignadas'));
        }

        if (Auth::user()->rol === 'docente') {
            // Teacher sees only their assigned subjects
            $materias = Materia::where('id_docente', Auth::id())->get();
            return view('materias.index', compact('materias'));
        }

        // Admin sees all, with filters
        $query = Materia::with('docente');

        // Filters
        $filterGrado = $request->get('grado');
        if ($filterGrado) {
            $query->where('grado', $filterGrado);
        }

        $filterSeccion = $request->get('seccion');
        if ($filterSeccion) {
            $query->where('seccion', $filterSeccion);
        }

        $filterNombre = $request->get('nombre');
        if ($filterNombre) {
            $query->where('nombre', $filterNombre);
        }

        $materias = $query->get();
        return view('materias.index', compact('materias', 'filterGrado', 'filterSeccion', 'filterNombre'));
    }

    public function create()
    {
        if (Auth::user()->rol !== 'admin') abort(403);
        $docentes = \App\Models\User::where('rol', 'docente')->get();
        return view('materias.create', compact('docentes'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->rol !== 'admin') abort(403);
        $request->validate([
            'nombre' => 'required|min:3|max:100',
            'descripcion' => 'nullable|max:500',
            'id_docente' => 'required|exists:usuarios,id_usuario',
            'periodo' => 'nullable|max:20',
            'horario' => 'nullable|max:50',
            'cupo_maximo' => 'nullable|integer|min:1',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
            'id_docente.required' => 'Debes seleccionar un docente',
            'id_docente.exists' => 'El docente seleccionado no existe',
            'cupo_maximo.integer' => 'El cupo máximo debe ser un número',
            'cupo_maximo.min' => 'El cupo máximo debe ser al menos 1',
        ]);

        $materia = Materia::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'id_docente' => $request->id_docente,
            'periodo' => $request->periodo,
            'horario' => $request->horario,
            'cupo_maximo' => $request->cupo_maximo,
            'grado' => $request->grado,
            'seccion' => $request->seccion,
        ]);

        // Sync students immediately
        $enrollmentService = new \App\Services\EnrollmentService();
        $enrollmentService->syncMateriaStudents($materia);

        Auditoria::create([
            'id_usuario' => Auth::id(),
            'accion' => 'Creación de materia: ' . $materia->nombre,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('materias.index')->with('success', 'Materia creada exitosamente');
    }

    public function show($id)
    {
        $materia = Materia::with(['docente'])->findOrFail($id);
        
        // Security check: Teacher can only view their own subject? 
        // Or maybe they can view others but not manage? 
        // Requirement says "he is him and can only touch the subjects of his profession"
        if (Auth::user()->rol === 'docente' && $materia->id_docente !== Auth::id()) {
            abort(403, 'No tienes permiso para ver esta materia.');
        }

        $recursos = \App\Models\Recurso::where('id_materia', $id)->get();
        return view('materias.show', compact('materia', 'recursos'));
    }

    public function edit($id)
    {
        if (Auth::user()->rol !== 'admin') abort(403);
        $materia = Materia::findOrFail($id);
        $docentes = \App\Models\User::where('rol', 'docente')->get();
        return view('materias.edit', compact('materia', 'docentes'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->rol !== 'admin') abort(403);
        $materia = Materia::findOrFail($id);

        $request->validate([
            'nombre' => 'required|min:3|max:100',
            'descripcion' => 'nullable|max:500',
            'id_docente' => 'required|exists:usuarios,id_usuario',
            'periodo' => 'nullable|max:20',
            'horario' => 'nullable|max:50',
            'cupo_maximo' => 'nullable|integer|min:1',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
            'id_docente.required' => 'Debes seleccionar un docente',
            'id_docente.exists' => 'El docente seleccionado no existe',
            'cupo_maximo.integer' => 'El cupo máximo debe ser un número',
            'cupo_maximo.min' => 'El cupo máximo debe ser al menos 1',
        ]);

        $materia->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'id_docente' => $request->id_docente,
            'periodo' => $request->periodo,
            'horario' => $request->horario,
            'cupo_maximo' => $request->cupo_maximo,
            'grado' => $request->grado,
            'seccion' => $request->seccion,
        ]);
        
        // Sync students immediately (in case grado/seccion changed)
        $enrollmentService = new \App\Services\EnrollmentService();
        $enrollmentService->syncMateriaStudents($materia);

        Auditoria::create([
            'id_usuario' => Auth::id(),
            'accion' => 'Actualización de materia: ' . $materia->nombre,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('materias.index')->with('success', 'Materia actualizada exitosamente');
    }

    public function destroy($id)
    {
        if (Auth::user()->rol !== 'admin') abort(403);
        $materia = Materia::findOrFail($id);
        $nombreMateria = $materia->nombre;

        $materia->delete();

        Auditoria::create([
            'id_usuario' => Auth::id(),
            'accion' => 'Eliminación de materia: ' . $nombreMateria,
            'ip' => request()->ip(),
        ]);

        return redirect()->route('materias.index')->with('success', 'Materia eliminada exitosamente');
    }
}

