@extends('layouts.app')

@section('title', 'Dashboard - Administrador')

@section('main_content_body')
<div class="card">
    <div class="card-header">
        <h1>Panel de Administración</h1>
    </div>
    <div class="card-body">
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalUsuarios }}</h3>
                    <p>Total de Usuarios</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('users.index') }}" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalMaterias }}</h3>
                    <p>Total de Materias</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <a href="{{ route('materias.index') }}" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        @php
            $solicitudesPendientes = \App\Models\SolicitudInscripcion::where('estado', 'pendiente')->count();
        @endphp
        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $solicitudesPendientes }}</h3>
                    <p>Solicitudes Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <a href="{{ route('admin.solicitudes.pendientes') }}" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

        <h2>Últimos Eventos en el Sistema</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Fecha</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ultimosEventos as $evento)
                <tr>
                    <td>{{ $evento->usuario->nombre ?? 'Sistema' }}</td>
                    <td>{{ $evento->accion }}</td>
                    <td>{{ $evento->fecha }}</td>
                    <td>{{ $evento->ip }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No hay eventos registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
