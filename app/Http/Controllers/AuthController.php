<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $key = 'login.'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'correo' => "Demasiados intentos. Intenta de nuevo en {$seconds} segundos.",
            ]);
        }

        $request->validate([
            'correo' => 'required|email',
            'contraseña' => 'required|min:6',
        ], [
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'El correo debe ser válido',
            'contraseña.required' => 'La contraseña es obligatoria',
            'contraseña.min' => 'La contraseña debe tener al menos 6 caracteres',
        ]);

        $user = User::where('correo', $request->correo)->first();

        if (!$user || !Hash::check($request->contraseña, $user->contraseña)) {
            RateLimiter::hit($key);
            
            Auditoria::create([
                'accion' => 'Intento de login fallido: ' . $request->correo,
                'ip' => $request->ip(),
            ]);

            return back()->withErrors([
                'correo' => 'Correo o contraseña incorrectos.',
            ]);
        }

        if ($user->estado === 'bloqueado') {
            RateLimiter::hit($key);
            
            Auditoria::create([
                'id_usuario' => $user->id_usuario,
                'accion' => 'Intento de login con cuenta bloqueada',
                'ip' => $request->ip(),
            ]);

            return back()->withErrors([
                'correo' => 'Tu cuenta está bloqueada. Contacta al administrador.',
            ]);
        }

        Auth::login($user, $request->filled('remember'));
        
        RateLimiter::clear($key);

        Auditoria::create([
            'id_usuario' => $user->id_usuario,
            'accion' => 'Login exitoso',
            'ip' => $request->ip(),
        ]);

        if ($user->rol === 'admin') {
            return redirect()->route('dashboard.admin');
        } elseif ($user->rol === 'docente') {
            return redirect()->route('dashboard.docente');
        } else {
            return redirect()->route('dashboard.estudiante');
        }
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|min:3|max:100',
            'cedula' => 'required|string|max:20|unique:usuarios,cedula',
            'correo' => 'required|email|unique:usuarios,correo',
            'contraseña' => 'required|min:6|confirmed',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'El correo debe ser válido',
            'correo.unique' => 'Este correo ya está registrado',
            'contraseña.required' => 'La contraseña es obligatoria',
            'contraseña.min' => 'La contraseña debe tener al menos 6 caracteres',
            'contraseña.confirmed' => 'Las contraseñas no coinciden',
        ]);

        $user = User::create([
            'nombre' => $request->nombre,
            'cedula' => $request->cedula,
            'correo' => $request->correo,
            'contraseña' => Hash::make($request->contraseña),
            'rol' => 'estudiante',
            'estado' => 'activo',
        ]);

        Auditoria::create([
            'id_usuario' => $user->id_usuario,
            'accion' => 'Registro de nuevo usuario: ' . $request->rol,
            'ip' => $request->ip(),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard.estudiante')->with('success', 'Registro exitoso');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auditoria::create([
                'id_usuario' => Auth::id(),
                'accion' => 'Logout',
                'ip' => $request->ip(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
