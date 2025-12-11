@extends('layouts.app')

@section('title', 'Reportes y Gestión')

@section('content_header')
    <h1>Reportes del Sistema</h1>
@stop

@section('main_content_body')
<div class="row">
    <!-- Reporte de Usuarios -->
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>Usuarios</h3>
                <p>Listado general de usuarios</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="small-box-footer">
                <a href="{{ route('reportes.usuarios', ['format' => 'pdf']) }}" target="_blank" class="text-white mr-2"><i class="fas fa-file-pdf"></i> PDF</a>
                <span class="text-white">|</span>
                <a href="{{ route('reportes.usuarios', ['format' => 'csv']) }}" target="_blank" class="text-white ml-2"><i class="fas fa-file-csv"></i> Excel/CSV</a>
            </div>
        </div>
    </div>

    <!-- Reporte de Rendimiento -->
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>Notas</h3>
                <p>Rendimiento Académico</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="small-box-footer">
                <a href="{{ route('reportes.rendimiento', ['format' => 'pdf']) }}" target="_blank" class="text-white mr-2"><i class="fas fa-file-pdf"></i> PDF</a>
                <span class="text-white">|</span>
                <a href="{{ route('reportes.rendimiento', ['format' => 'csv']) }}" target="_blank" class="text-white ml-2"><i class="fas fa-file-csv"></i> Excel/CSV</a>
            </div>
        </div>
    </div>
    
     <!-- Future Reports placeholders -->
    <!-- Reporte de Materias -->
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>Materias</h3>
                <p>Materias y Asignaciones</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="small-box-footer">
                <a href="{{ route('reportes.materias', ['format' => 'pdf']) }}" target="_blank" class="text-white mr-2"><i class="fas fa-file-pdf"></i> PDF</a>
                <span class="text-white">|</span>
                <a href="{{ route('reportes.materias', ['format' => 'csv']) }}" target="_blank" class="text-white ml-2"><i class="fas fa-file-csv"></i> Excel/CSV</a>
            </div>
        </div>
    </div>
</div>
@endsection
