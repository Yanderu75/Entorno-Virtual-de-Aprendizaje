@extends('layouts.app')

@section('title', 'Detalle de Notas')

@section('content')
<div class="card">
    <div class="card-header">
        <h1>{{ $estudianteMateria->materia->nombre }}</h1>
    </div>
    <div class="card-body">
        <div style="margin-bottom: 30px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h3>{{ number_format($estudianteMateria->promedio, 0) }}</h3>
                    <p>Promedio General</p>
                    @if($estudianteMateria->promedio >= 10)
                        <span style="color: #28a745; font-weight: bold;">✓ Aprobado</span>
                    @else
                        <span style="color: #dc3545; font-weight: bold;">✗ Reprobado</span>
                    @endif
                </div>
                <div class="stat-card stat-card-pink">
                    <h3>{{ number_format($estudianteMateria->avance, 2) }}%</h3>
                    <p>Avance</p>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
            <h3>Promedios por Lapso</h3>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-top: 15px;">
                @for($lapso = 1; $lapso <= 3; $lapso++)
                <div style="padding: 15px; background: white; border-radius: 5px; border: 2px solid {{ $promediosPorLapso[$lapso] !== null && $promediosPorLapso[$lapso] >= 10 ? '#28a745' : ($promediosPorLapso[$lapso] !== null ? '#dc3545' : '#ddd') }};">
                    <strong>Lapso {{ $lapso }}</strong>
                    @if($promediosPorLapso[$lapso] !== null)
                        <div style="font-size: 1.5em; font-weight: bold; margin-top: 5px; color: {{ $promediosPorLapso[$lapso] >= 10 ? '#28a745' : '#dc3545' }};">
                            {{ $promediosPorLapso[$lapso] }}
                        </div>
                        @if($promediosPorLapso[$lapso] >= 10)
                            <span style="color: #28a745; font-size: 0.9em;">Aprobado</span>
                        @else
                            <span style="color: #dc3545; font-size: 0.9em;">Reprobado</span>
                        @endif
                    @else
                        <div style="color: #999; margin-top: 5px;">Sin notas</div>
                    @endif
                </div>
                @endfor
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <strong>Docente:</strong> {{ $estudianteMateria->materia->docente->nombre ?? 'Sin asignar' }}<br>
            <strong>Periodo:</strong> {{ $estudianteMateria->materia->periodo ?? '-' }}<br>
            <strong>Horario:</strong> {{ $estudianteMateria->materia->horario ?? '-' }}
        </div>

        <h2>Calificaciones por Lapso</h2>
        
        @for($lapso = 1; $lapso <= 3; $lapso++)
            @if(isset($calificacionesPorLapso[$lapso]) && $calificacionesPorLapso[$lapso]->count() > 0)
                <div style="margin-bottom: 30px;">
                    <h3 style="color: #667eea; margin-bottom: 15px;">
                        Lapso {{ $lapso }}
                        @if($promediosPorLapso[$lapso] !== null)
                            <span style="font-size: 0.8em; color: {{ $promediosPorLapso[$lapso] >= 10 ? '#28a745' : '#dc3545' }};">
                                (Promedio: {{ $promediosPorLapso[$lapso] }})
                            </span>
                        @endif
                    </h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Evaluación</th>
                                <th>Nota</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($calificacionesPorLapso[$lapso] as $index => $calificacion)
                            <tr>
                                <td>Evaluación {{ $index + 1 }}</td>
                                <td><strong>{{ number_format($calificacion->nota, 2) }}</strong></td>
                                <td>{{ $calificacion->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                            @if($promediosPorLapso[$lapso] !== null)
                            <tr style="background-color: #f8f9fa; font-weight: bold;">
                                <td>Promedio del Lapso</td>
                                <td style="color: {{ $promediosPorLapso[$lapso] >= 10 ? '#28a745' : '#dc3545' }};">
                                    {{ $promediosPorLapso[$lapso] }}
                                </td>
                                <td>
                                    @if($promediosPorLapso[$lapso] >= 10)
                                        <span style="color: #28a745;">Aprobado</span>
                                    @else
                                        <span style="color: #dc3545;">Reprobado</span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            @endif
        @endfor

        @if($estudianteMateria->calificaciones->count() == 0)
            <p class="text-center">No hay calificaciones registradas aún</p>
        @endif

        <div style="margin-top: 20px;">
            <a href="{{ route('notas.index') }}" class="btn btn-primary" style="width: auto;">Volver a Mis Notas</a>
        </div>
    </div>
</div>
@endsection

