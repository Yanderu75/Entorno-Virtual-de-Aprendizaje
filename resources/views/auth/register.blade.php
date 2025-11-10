@extends('layouts.app')

@section('title', 'Registro')

@section('content')
<div class="card" style="max-width: 500px; margin: 0 auto;">
    <div class="card-header">
        <h1>Crear Cuenta</h1>
        <p>Regístrate en el Entorno Virtual de Aprendizaje</p>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" value="{{ old('correo') }}" required>
            </div>

            <div class="form-group">
                <label for="contraseña">Contraseña</label>
                <input type="password" id="contraseña" name="contraseña" required>
            </div>

            <div class="form-group">
                <label for="contraseña_confirmation">Confirmar Contraseña</label>
                <input type="password" id="contraseña_confirmation" name="contraseña_confirmation" required>
            </div>

            <div class="form-group">
                <label for="rol">Rol</label>
                <select id="rol" name="rol" required>
                    <option value="estudiante">Estudiante</option>
                    <option value="docente">Docente</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Registrarse</button>
        </form>

        <div class="text-center mt-3">
            <p>¿Ya tienes cuenta? <a href="{{ route('login') }}" class="link">Inicia sesión aquí</a></p>
        </div>
    </div>
</div>
@endsection
