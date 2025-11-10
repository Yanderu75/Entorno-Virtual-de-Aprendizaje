@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="card" style="max-width: 500px; margin: 0 auto;">
    <div class="card-header">
        <h1>Iniciar Sesión</h1>
        <p>Entorno Virtual de Aprendizaje</p>
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

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" value="{{ old('correo') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="contraseña">Contraseña</label>
                <input type="password" id="contraseña" name="contraseña" required>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="remember"> Recordarme
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </form>

        <div class="text-center mt-3">
            <p>¿No tienes cuenta? <a href="{{ route('register') }}" class="link">Regístrate aquí</a></p>
        </div>
    </div>
</div>
@endsection
