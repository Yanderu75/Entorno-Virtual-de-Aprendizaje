<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Entorno Virtual de Aprendizaje')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    @auth
    <div class="navbar">
        <div class="navbar-content">
            <div class="navbar-brand">EVA - Entorno Virtual de Aprendizaje</div>
            <div class="navbar-user">
                <div class="user-info">
                    <span>{{ Auth::user()->nombre }}</span>
                    <span class="badge badge-{{ Auth::user()->rol }}">{{ ucfirst(Auth::user()->rol) }}</span>
                </div>
                @if(Auth::check())
                    <a href="{{ route('notificaciones.index') }}" class="btn btn-primary btn-nav" style="position: relative;">
                        Notificaciones
                        @php
                            $notificacionesNoLeidas = Auth::user()->notificacionesNoLeidas()->count();
                        @endphp
                        @if($notificacionesNoLeidas > 0)
                            <span style="position: absolute; top: -5px; right: -5px; background: #dc3545; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold;">{{ $notificacionesNoLeidas }}</span>
                        @endif
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-nav">Salir</button>
                </form>
            </div>
        </div>
    </div>
    @endauth

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
