<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by Role
        $filterRol = $request->get('rol');
        if ($filterRol) {
            $query->where('rol', $filterRol);
        }

        // Filter by Grado (Only relevant for Students usually, but generic is fine)
        $filterGrado = $request->get('grado');
        if($filterGrado && $filterRol === 'estudiante') {
             $query->where('grado', $filterGrado);
        }

        // Filter by Seccion
        $filterSeccion = $request->get('seccion');
        if($filterSeccion && $filterRol === 'estudiante') {
             $query->where('seccion', $filterSeccion);
        }

        // Search by Cedula or Name
        $busqueda = $request->get('busqueda');
        if ($busqueda) {
            $query->where(function($q) use ($busqueda) {
                $q->where('cedula', 'like', "%{$busqueda}%")
                  ->orWhere('nombre', 'like', "%{$busqueda}%")
                  ->orWhere('correo', 'like', "%{$busqueda}%");
            });
        }

        $usuarios = $query->get();

        return view('users.index', compact('usuarios', 'filterRol', 'filterGrado', 'filterSeccion', 'busqueda'));
    }

    public function show($id)
    {
        $usuario = User::findOrFail($id);
        return view('users.show', compact('usuario'));
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('users.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'nombre' => 'required|min:3|max:100',
            'cedula' => 'required|string|max:20|unique:usuarios,cedula,' . $id . ',id_usuario',
            'correo' => 'required|email|unique:usuarios,correo,' . $id . ',id_usuario',
            'rol' => 'required|in:estudiante,docente,admin',
            'estado' => 'required|in:activo,bloqueado',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'El correo debe ser válido',
            'correo.unique' => 'Este correo ya está registrado',
            'rol.required' => 'Debes seleccionar un rol',
            'estado.required' => 'Debes seleccionar un estado',
        ]);

        $usuario->update([
            'nombre' => $request->nombre,
            'cedula' => $request->cedula,
            'correo' => $request->correo,
            'rol' => $request->rol,
            'especialidad' => $request->rol == 'docente' ? $request->especialidad : null,
            'grado' => $request->rol == 'estudiante' ? $request->grado : null,
            'seccion' => $request->rol == 'estudiante' ? $request->seccion : null,
            'estado' => $request->estado,
        ]);

        // Automated Academic Layout Logic: Sync Subjects if Student
        if ($usuario->rol === 'estudiante') {
            $enrollmentService = new \App\Services\EnrollmentService();
            $enrollmentService->syncStudentSubjects($usuario);
        }

        if ($request->filled('contraseña')) {
            $request->validate([
                'contraseña' => 'min:6',
            ], [
                'contraseña.min' => 'La contraseña debe tener al menos 6 caracteres',
            ]);

            $usuario->update([
                'contraseña' => Hash::make($request->contraseña),
            ]);
        }

        Auditoria::create([
            'id_usuario' => Auth::id(),
            'accion' => 'Actualización de usuario: ' . $usuario->nombre,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $nombreUsuario = $usuario->nombre;

        if ($usuario->id_usuario === Auth::id()) {
            return back()->withErrors(['error' => 'No puedes eliminar tu propia cuenta']);
        }

        $usuario->delete();

        Auditoria::create([
            'id_usuario' => Auth::id(),
            'accion' => 'Eliminación de usuario: ' . $nombreUsuario,
            'ip' => request()->ip(),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente');
    }
}

