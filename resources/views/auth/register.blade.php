@extends('layouts.auth')

@section('title', 'Registro')

@section('content')
    <p class="login-box-msg">Registrar una nueva cuenta</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="input-group mb-3">
            <input type="text" name="nombre" class="form-control" placeholder="Nombre Completo" value="{{ old('nombre') }}" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="text" name="cedula" class="form-control" placeholder="Cédula de Identidad" value="{{ old('cedula') }}" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-id-card"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="email" name="correo" class="form-control" placeholder="Correo Electrónico" value="{{ old('correo') }}" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="password" name="contraseña" class="form-control" placeholder="Contraseña" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="password" name="contraseña_confirmation" class="form-control" placeholder="Confirmar Contraseña" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        
        <!-- Role selection removed: Default is Student -->

        <div class="row">
            <div class="col-8">
                <div class="icheck-primary">
                    <input type="checkbox" id="agreeTerms" name="terms" value="agree" required>
                    <label for="agreeTerms">
                     Acepto los <a href="#">términos</a>
                    </label>
                </div>
            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Registro</button>
            </div>
        </div>
    </form>

    <a href="{{ route('login') }}" class="text-center">Ya tengo una cuenta</a>
@endsection
