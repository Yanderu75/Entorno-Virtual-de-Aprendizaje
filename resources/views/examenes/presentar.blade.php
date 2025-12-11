@extends('layouts.app')

@section('title', 'Presentar Examen')

@section('main_content_body')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title">{{ $examen->titulo }}</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> 
            <strong>Materia:</strong> {{ $examen->materia->nombre }}<br>
            <strong>Tiempo disponible:</strong> Hasta {{ $examen->fecha_fin->format('d/m/Y H:i') }}
        </div>

        <p>{{ $examen->descripcion }}</p>
        
        <form action="{{ route('examenes.guardar', $examen->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de enviar tus respuestas? No podrás modificarlas después.')">
            @csrf
            
            @foreach($examen->preguntas as $index => $pregunta)
                <div class="card mb-3">
                    <div class="card-header">
                        <strong>Pregunta {{ $index + 1 }}</strong> 
                        <span class="badge badge-info float-right">{{ $pregunta->puntaje }} pts</span>
                    </div>
                    <div class="card-body">
                        <p>{{ $pregunta->enunciado }}</p>
                        
                        @if($pregunta->tipo == 'opcion_simple')
                            @foreach($pregunta->opciones as $opcion)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="respuesta_{{ $pregunta->id }}" value="{{ $opcion }}" required>
                                    <label class="form-check-label">
                                        {{ $opcion }}
                                    </label>
                                </div>
                            @endforeach
                        @elseif($pregunta->tipo == 'verdadero_falso')
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="respuesta_{{ $pregunta->id }}" value="Verdadero" required>
                                <label class="form-check-label">Verdadero</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="respuesta_{{ $pregunta->id }}" value="Falso" required>
                                <label class="form-check-label">Falso</label>
                            </div>
                        @else
                            <textarea name="respuesta_{{ $pregunta->id }}" class="form-control" rows="4" required></textarea>
                        @endif
                    </div>
                </div>
            @endforeach
            
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-paper-plane"></i> Enviar Examen
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
