@extends('layouts.app')

@section('title', 'Dashboard - Docente')

@section('main_content_body')
<div class="card">
    <div class="card-header">
        <h1>Bienvenido, {{ Auth::user()->nombre }}</h1>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $totalMaterias }}</h3>
                        <p>Mis Materias</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $totalEstudiantes }}</h3>
                        <p>Estudiantes Total</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <!-- ./col -->
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
            <a href="{{ route('materias.index') }}" class="btn btn-primary" style="text-align: center; padding: 20px;">
                <h3 style="margin: 0;">Gestión de Materias</h3>
                <p style="margin: 5px 0 0 0;">Crear, editar y gestionar materias</p>
            </a>
            <!-- Removed Create Materia and Solicitudes buttons as per request -->
        </div>

        <h2 style="margin-top: 30px;">Perfil de Docente</h2>
        <p><strong>Correo:</strong> {{ Auth::user()->correo }}</p>
        <p><strong>Estado:</strong> {{ Auth::user()->estado }}</p>
        <p><strong>Fecha de registro:</strong> {{ Auth::user()->creado_en }}</p>
    </div>
</div>
@endsection
