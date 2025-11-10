<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return view('users.index', compact('usuarios'));
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
            'correo' => $request->correo,
            'rol' => $request->rol,
            'estado' => $request->estado,
        ]);

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

