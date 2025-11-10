@extends('layouts.app')

@section('title', 'Gestionar Notas - Lapso ' . $lapso)

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Gestionar Notas - Lapso {{ $lapso }}</h1>
        <p style="margin-top: 10px; color: #666;">
            <strong>Materia:</strong> {{ $materia->nombre }} | 
            <strong>Estudiante:</strong> {{ $estudianteMateria->estudiante->nombre }}
        </p>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($promedioLapso !== null)
            <div class="alert alert-info" style="margin-bottom: 20px;">
                <strong>Promedio actual del Lapso {{ $lapso }}:</strong> 
                <span style="font-size: 1.5em; font-weight: bold; color: {{ $promedioLapso >= 10 ? '#28a745' : '#dc3545' }};">
                    {{ $promedioLapso }}
                </span>
                @if($promedioLapso >= 10)
                    <span class="badge badge-success">Aprobado</span>
                @else
                    <span class="badge badge-danger">Reprobado</span>
                @endif
            </div>
        @endif

        <form method="POST" action="{{ route('notas.docente.store', [$materia->id_materia, $estudianteMateria->id_estudiante_materia, $lapso]) }}">
            @csrf
            
            <div id="notas-container">
                <h3>Evaluaciones del Lapso {{ $lapso }}</h3>
                <p class="text-muted">Ingresa las notas de cada evaluación (escala 0-20). El sistema calculará el promedio automáticamente.</p>
                
                @if($calificaciones->count() > 0)
                    @foreach($calificaciones as $index => $calificacion)
                    <div class="nota-item" style="margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">
                            Evaluación {{ $index + 1 }}
                        </label>
                        <input type="number" 
                               name="notas[]" 
                               value="{{ $calificacion->nota }}" 
                               min="0" 
                               max="20" 
                               step="0.01"
                               required 
                               class="form-control"
                               style="max-width: 200px;">
                    </div>
                    @endforeach
                @else
                    <div class="nota-item" style="margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">
                            Evaluación 1
                        </label>
                        <input type="number" 
                               name="notas[]" 
                               min="0" 
                               max="20" 
                               step="0.01"
                               required 
                               class="form-control"
                               style="max-width: 200px;">
                    </div>
                @endif
            </div>

            <div style="margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="agregarEvaluacion()">Agregar Evaluación</button>
                <button type="button" class="btn btn-warning" onclick="eliminarUltimaEvaluacion()">Eliminar Última</button>
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #ddd;">
                <button type="submit" class="btn btn-primary btn-lg">Guardar Notas</button>
                <a href="{{ route('notas.docente.index', $materia->id_materia) }}" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
let contadorEvaluaciones = {{ $calificaciones->count() > 0 ? $calificaciones->count() : 1 }};

function agregarEvaluacion() {
    contadorEvaluaciones++;
    const container = document.getElementById('notas-container');
    const nuevaEvaluacion = document.createElement('div');
    nuevaEvaluacion.className = 'nota-item';
    nuevaEvaluacion.style.cssText = 'margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px;';
    nuevaEvaluacion.innerHTML = `
        <label style="display: block; margin-bottom: 5px; font-weight: bold;">
            Evaluación ${contadorEvaluaciones}
        </label>
        <input type="number" 
               name="notas[]" 
               min="0" 
               max="20" 
               step="0.01"
               required 
               class="form-control"
               style="max-width: 200px;">
    `;
    container.appendChild(nuevaEvaluacion);
}

function eliminarUltimaEvaluacion() {
    const items = document.querySelectorAll('.nota-item');
    if (items.length > 1) {
        items[items.length - 1].remove();
        contadorEvaluaciones--;
    } else {
        alert('Debe haber al menos una evaluación');
    }
}
</script>

<style>
.badge {
    padding: 5px 10px;
    border-radius: 4px;
    font-weight: bold;
    margin-left: 10px;
}
.badge-success {
    background-color: #28a745;
    color: white;
}
.badge-danger {
    background-color: #dc3545;
    color: white;
}
.form-control {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}
</style>
@endsection




