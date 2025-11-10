@extends('layouts.app')

@section('title', 'Mis Materias')

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Mis Materias</h1>
    </div>
    <div class="card-body">
        @if($materiasAsignadas->count() > 0)
            <div class="notas-grid">
                @foreach($materiasAsignadas as $estudianteMateria)
                    <div class="nota-card">
                        <h3>{{ $estudianteMateria->materia->nombre }}</h3>
                        <p><strong>Docente:</strong> {{ $estudianteMateria->materia->docente->nombre ?? 'Sin asignar' }}</p>
                        <p><strong>Periodo:</strong> {{ $estudianteMateria->materia->periodo ?? '-' }}</p>
                        <p><strong>Horario:</strong> {{ $estudianteMateria->materia->horario ?? '-' }}</p>
                        <div class="nota-stats">
                            <div class="nota-stat">
                                <span class="nota-label">Promedio</span>
                                <span class="nota-value">{{ number_format($estudianteMateria->promedio, 2) }}</span>
                            </div>
                            <div class="nota-stat">
                                <span class="nota-label">Avance</span>
                                <span class="nota-value">{{ number_format($estudianteMateria->avance, 2) }}%</span>
                            </div>
                        </div>
                        <a href="{{ route('notas.show', $estudianteMateria->materia->id_materia) }}" class="btn btn-primary" style="width: 100%; margin-top: 15px;">Ver Notas</a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center">No tienes materias asignadas aún</p>
        @endif
    </div>
</div>
@endsection

