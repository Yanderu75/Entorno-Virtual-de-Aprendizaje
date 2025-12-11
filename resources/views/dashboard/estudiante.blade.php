@extends('layouts.app')

@section('title', 'Dashboard - Estudiante')

@section('main_content_body')
<div class="card">
    <div class="card-header">
        <h1>Bienvenido, {{ Auth::user()->nombre }}</h1>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $materiasInscritas }}</h3>
                        <p>Materias Inscritas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-6 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $promedioGeneral }}</h3>
                        <p>Promedio General</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
            <a href="{{ route('materias.index') }}" class="btn btn-primary" style="text-align: center; padding: 20px;">
                <h3 style="margin: 0;">Mis Materias</h3>
                <p style="margin: 5px 0 0 0;">Ver todas mis materias asignadas</p>
            </a>
            <a href="{{ route('notas.index') }}" class="btn btn-primary" style="text-align: center; padding: 20px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h3 style="margin: 0;">Mis Notas</h3>
                <p style="margin: 5px 0 0 0;">Consultar calificaciones</p>
            </a>
        </div>

        <h2 style="margin-top: 30px;">Perfil de Estudiante</h2>
        <p><strong>Correo:</strong> {{ Auth::user()->correo }}</p>
        <p><strong>Estado:</strong> {{ Auth::user()->estado }}</p>
        <p><strong>Fecha de registro:</strong> {{ Auth::user()->creado_en }}</p>
    </div>
</div>
@endsection
