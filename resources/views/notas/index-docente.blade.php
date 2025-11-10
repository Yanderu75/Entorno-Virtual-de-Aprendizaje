@extends('layouts.app')

@section('title', 'Gestionar Notas - ' . $materia->nombre)

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Gestionar Notas - {{ $materia->nombre }}</h1>
        <p style="margin-top: 10px; color: #666;">
            <strong>Docente:</strong> {{ $materia->docente->nombre ?? 'Sin asignar' }} | 
            <strong>Periodo:</strong> {{ $materia->periodo ?? '-' }}
        </p>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($estudiantes->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Lapso 1</th>
                        <th>Lapso 2</th>
                        <th>Lapso 3</th>
                        <th>Promedio General</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($estudiantes as $estudiante)
                    <tr>
                        <td>
                            <strong>{{ $estudiante->estudiante->nombre }}</strong><br>
                            <small>{{ $estudiante->estudiante->correo }}</small>
                        </td>
                        <td>
                            @if($estudiante->promediosPorLapso[1] !== null)
                                <span class="badge badge-info">{{ $estudiante->promediosPorLapso[1] }}</span>
                            @else
                                <span class="text-muted">Sin notas</span>
                            @endif
                        </td>
                        <td>
                            @if($estudiante->promediosPorLapso[2] !== null)
                                <span class="badge badge-info">{{ $estudiante->promediosPorLapso[2] }}</span>
                            @else
                                <span class="text-muted">Sin notas</span>
                            @endif
                        </td>
                        <td>
                            @if($estudiante->promediosPorLapso[3] !== null)
                                <span class="badge badge-info">{{ $estudiante->promediosPorLapso[3] }}</span>
                            @else
                                <span class="text-muted">Sin notas</span>
                            @endif
                        </td>
                        <td>
                            <strong style="font-size: 1.2em; color: {{ $estudiante->promedio >= 10 ? '#28a745' : '#dc3545' }};">
                                {{ number_format($estudiante->promedio, 0) }}
                            </strong>
                        </td>
                        <td>
                            @if($estudiante->promedio >= 10)
                                <span class="badge badge-success">Aprobado</span>
                            @elseif($estudiante->promedio > 0)
                                <span class="badge badge-danger">Reprobado</span>
                            @else
                                <span class="badge badge-secondary">Sin evaluar</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                <a href="{{ route('notas.docente.create-edit', [$materia->id_materia, $estudiante->id_estudiante_materia, 1]) }}" 
                                   class="btn btn-sm btn-primary" title="Gestionar Lapso 1">L1</a>
                                <a href="{{ route('notas.docente.create-edit', [$materia->id_materia, $estudiante->id_estudiante_materia, 2]) }}" 
                                   class="btn btn-sm btn-primary" title="Gestionar Lapso 2">L2</a>
                                <a href="{{ route('notas.docente.create-edit', [$materia->id_materia, $estudiante->id_estudiante_materia, 3]) }}" 
                                   class="btn btn-sm btn-primary" title="Gestionar Lapso 3">L3</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center">No hay estudiantes asignados a esta materia</p>
        @endif

        <div style="margin-top: 20px;">
            <a href="{{ route('materias.index') }}" class="btn btn-secondary">Volver a Materias</a>
        </div>
    </div>
</div>

<style>
.badge {
    padding: 5px 10px;
    border-radius: 4px;
    font-weight: bold;
}
.badge-success {
    background-color: #28a745;
    color: white;
}
.badge-danger {
    background-color: #dc3545;
    color: white;
}
.badge-info {
    background-color: #17a2b8;
    color: white;
}
.badge-secondary {
    background-color: #6c757d;
    color: white;
}
</style>
@endsection




