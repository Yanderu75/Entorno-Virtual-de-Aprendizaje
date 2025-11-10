@extends('layouts.app')

@section('title', 'Dashboard - Administrador')

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Panel de Administración</h1>
    </div>
    <div class="card-body">
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>{{ $totalUsuarios }}</h3>
                <p>Total de Usuarios</p>
            </div>
            <div class="stat-card stat-card-pink">
                <h3>{{ $totalMaterias }}</h3>
                <p>Total de Materias</p>
            </div>
            @php
                $solicitudesPendientes = \App\Models\SolicitudInscripcion::where('estado', 'pendiente')->count();
            @endphp
            <div class="stat-card" style="background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%);">
                <h3>{{ $solicitudesPendientes }}</h3>
                <p>Solicitudes Pendientes</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
            <a href="{{ route('admin.solicitudes.pendientes') }}" class="btn btn-primary" style="text-align: center; padding: 20px; background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%);">
                <h3 style="margin: 0;">Solicitudes Pendientes</h3>
                <p style="margin: 5px 0 0 0;">Revisar y aprobar solicitudes de inscripción</p>
                @if($solicitudesPendientes > 0)
                    <span style="background: #dc3545; color: white; padding: 5px 10px; border-radius: 15px; font-size: 12px; margin-top: 10px; display: inline-block;">{{ $solicitudesPendientes }} pendientes</span>
                @endif
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-primary" style="text-align: center; padding: 20px;">
                <h3 style="margin: 0;">Gestión de Usuarios</h3>
                <p style="margin: 5px 0 0 0;">Administrar usuarios del sistema</p>
            </a>
            <a href="{{ route('materias.index') }}" class="btn btn-primary" style="text-align: center; padding: 20px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h3 style="margin: 0;">Gestión de Materias</h3>
                <p style="margin: 5px 0 0 0;">Ver todas las materias</p>
            </a>
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
