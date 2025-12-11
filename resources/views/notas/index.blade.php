@extends('layouts.app')

@section('title', 'Mis Notas')

@section('main_content_body')
<div class="card">
    <div class="card-header">
        <h1>Mis Notas</h1>
    </div>
    <div class="card-body">
        @if($materiasAsignadas->count() > 0)
            <div class="row">
                @foreach($materiasAsignadas as $estudianteMateria)
                    <div class="col-md-4">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">{{ $estudianteMateria->materia->nombre }}</h3>
                            </div>
                            <div class="card-body">
                                <p><strong>Docente:</strong> {{ $estudianteMateria->materia->docente->nombre ?? 'Sin asignar' }}</p>
                                <p><strong>Periodo:</strong> {{ $estudianteMateria->materia->periodo ?? '-' }}</p>
                                <hr>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <label>Promedio</label>
                                        <h4 class="{{ $estudianteMateria->promedio >= 10 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($estudianteMateria->promedio, 0) }}
                                        </h4>
                                    </div>
                                    <div class="col-6">
                                        <label>Estado</label>
                                        <h4>
                                            @if($estudianteMateria->promedio >= 10)
                                                <span class="badge badge-success">Aprobado</span>
                                            @elseif($estudianteMateria->promedio > 0)
                                                <span class="badge badge-danger">Reprobado</span>
                                            @else
                                                <span class="badge badge-secondary">Sin evaluar</span>
                                            @endif
                                        </h4>
                                    </div>
                                </div>
                                <hr>
                                <p class="text-sm"><strong>Promedios por Lapso:</strong></p>
                                <div class="d-flex justify-content-between">
                                    <span>L1: <strong>{{ $estudianteMateria->promediosPorLapso[1] ?? '-' }}</strong></span>
                                    <span>L2: <strong>{{ $estudianteMateria->promediosPorLapso[2] ?? '-' }}</strong></span>
                                    <span>L3: <strong>{{ $estudianteMateria->promediosPorLapso[3] ?? '-' }}</strong></span>
                                </div>
                                <a href="{{ route('notas.show', $estudianteMateria->materia->id_materia) }}" class="btn btn-primary btn-block mt-3">Ver Detalles</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center">No tienes materias asignadas aún</p>
        @endif
    </div>
</div>
@endsection

