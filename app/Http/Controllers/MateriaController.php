<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\EstudianteMateria;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MateriaController extends Controller
{
    public function index()
    {
        if (Auth::user()->rol === 'estudiante') {
            $materiasAsignadas = EstudianteMateria::where('id_estudiante', Auth::id())
                ->with('materia.docente')
                ->get();
            return view('materias.index-estudiante', compact('materiasAsignadas'));
        }

        $materias = Materia::with('docente')->get();
        return view('materias.index', compact('materias'));
    }

    public function create()
    {
        $docentes = \App\Models\User::where('rol', 'docente')->get();
        return view('materias.create', compact('docentes'));
    }

    public function store(Request $request)
    {
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
        ]);

        Auditoria::create([
            'id_usuario' => Auth::id(),
            'accion' => 'Creación de materia: ' . $materia->nombre,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('materias.index')->with('success', 'Materia creada exitosamente');
    }

    public function show($id)
    {
        $materia = Materia::with('docente')->findOrFail($id);
        return view('materias.show', compact('materia'));
    }

    public function edit($id)
    {
        $materia = Materia::findOrFail($id);
        $docentes = \App\Models\User::where('rol', 'docente')->get();
        return view('materias.edit', compact('materia', 'docentes'));
    }

    public function update(Request $request, $id)
    {
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
        ]);

        Auditoria::create([
            'id_usuario' => Auth::id(),
            'accion' => 'Actualización de materia: ' . $materia->nombre,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('materias.index')->with('success', 'Materia actualizada exitosamente');
    }

    public function destroy($id)
    {
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

