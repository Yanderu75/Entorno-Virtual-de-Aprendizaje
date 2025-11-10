@extends('layouts.app')

@section('title', 'Dashboard - Docente')

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Bienvenido, {{ Auth::user()->nombre }}</h1>
    </div>
    <div class="card-body">
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>-</h3>
                <p>Mis Materias</p>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h3>-</h3>
                <p>Estudiantes Total</p>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <h3>-</h3>
                <p>Mensajes Sin Leer</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
            <a href="{{ route('materias.index') }}" class="btn btn-primary" style="text-align: center; padding: 20px;">
                <h3 style="margin: 0;">Gestión de Materias</h3>
                <p style="margin: 5px 0 0 0;">Crear, editar y gestionar materias</p>
            </a>
            <a href="{{ route('materias.create') }}" class="btn btn-primary" style="text-align: center; padding: 20px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h3 style="margin: 0;">Crear Materia</h3>
                <p style="margin: 5px 0 0 0;">Agregar nueva materia</p>
            </a>
            <a href="{{ route('solicitudes.index') }}" class="btn btn-primary" style="text-align: center; padding: 20px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <h3 style="margin: 0;">Mis Solicitudes</h3>
                <p style="margin: 5px 0 0 0;">Ver estado de mis solicitudes de inscripción</p>
            </a>
        </div>

        <h2 style="margin-top: 30px;">Perfil de Docente</h2>
        <p><strong>Correo:</strong> {{ Auth::user()->correo }}</p>
        <p><strong>Estado:</strong> {{ Auth::user()->estado }}</p>
        <p><strong>Fecha de registro:</strong> {{ Auth::user()->creado_en }}</p>
    </div>
</div>
@endsection
