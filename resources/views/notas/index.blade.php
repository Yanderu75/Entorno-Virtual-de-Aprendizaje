@extends('layouts.app')

@section('title', 'Mis Notas')

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Mis Notas</h1>
    </div>
    <div class="card-body">
        @if($materiasAsignadas->count() > 0)
            <div class="notas-grid">
                @foreach($materiasAsignadas as $estudianteMateria)
                    <div class="nota-card">
                        <h3>{{ $estudianteMateria->materia->nombre }}</h3>
                        <p><strong>Docente:</strong> {{ $estudianteMateria->materia->docente->nombre ?? 'Sin asignar' }}</p>
                        <p><strong>Periodo:</strong> {{ $estudianteMateria->materia->periodo ?? '-' }}</p>
                        <div class="nota-stats">
                            <div class="nota-stat">
                                <span class="nota-label">Promedio General</span>
                                <span class="nota-value" style="color: {{ $estudianteMateria->promedio >= 10 ? '#28a745' : '#dc3545' }};">
                                    {{ number_format($estudianteMateria->promedio, 0) }}
                                </span>
                            </div>
                            <div class="nota-stat">
                                <span class="nota-label">Estado</span>
                                <span class="nota-value">
                                    @if($estudianteMateria->promedio >= 10)
                                        <span style="color: #28a745; font-weight: bold;">Aprobado</span>
                                    @elseif($estudianteMateria->promedio > 0)
                                        <span style="color: #dc3545; font-weight: bold;">Reprobado</span>
                                    @else
                                        <span style="color: #6c757d;">Sin evaluar</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                            <strong>Promedios por Lapso:</strong>
                            <div style="display: flex; gap: 10px; margin-top: 8px;">
                                <span><strong>L1:</strong> 
                                    @if($estudianteMateria->promediosPorLapso[1] !== null)
                                        {{ $estudianteMateria->promediosPorLapso[1] }}
                                    @else
                                        <span style="color: #999;">-</span>
                                    @endif
                                </span>
                                <span><strong>L2:</strong> 
                                    @if($estudianteMateria->promediosPorLapso[2] !== null)
                                        {{ $estudianteMateria->promediosPorLapso[2] }}
                                    @else
                                        <span style="color: #999;">-</span>
                                    @endif
                                </span>
                                <span><strong>L3:</strong> 
                                    @if($estudianteMateria->promediosPorLapso[3] !== null)
                                        {{ $estudianteMateria->promediosPorLapso[3] }}
                                    @else
                                        <span style="color: #999;">-</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('notas.show', $estudianteMateria->materia->id_materia) }}" class="btn btn-primary" style="width: 100%; margin-top: 15px;">Ver Detalles</a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center">No tienes materias asignadas aún</p>
        @endif
    </div>
</div>
@endsection

