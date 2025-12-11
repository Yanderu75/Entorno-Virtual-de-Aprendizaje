@extends('layouts.app')

@section('title', 'Gestión de Examen')

@section('main_content_body')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $examen->titulo }}</h3>
                <div class="card-tools">
                    <a href="{{ route('examenes.edit', $examen->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <form action="{{ route('examenes.publicar', $examen->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-{{ $examen->publicado ? 'secondary' : 'success' }}">
                            <i class="fas fa-{{ $examen->publicado ? 'eye-slash' : 'eye' }}"></i>
                            {{ $examen->publicado ? 'Ocultar' : 'Publicar' }}
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <p><strong>Materia:</strong> {{ $examen->materia->nombre }}</p>
                <p><strong>Lapso:</strong> {{ $examen->lapso }} | <strong>Evaluación:</strong> {{ $examen->numero_evaluacion }}</p>
                <p><strong>Descripción:</strong> {{ $examen->descripcion }}</p>
                <p><strong>Período:</strong> {{ $examen->fecha_inicio->format('d/m/Y H:i') }} - {{ $examen->fecha_fin->format('d/m/Y H:i') }}</p>
                
                <hr>
                
                <h4>Preguntas ({{ $examen->preguntas->count() }})</h4>
                
                @if($examen->preguntas->isEmpty())
                    <p class="text-muted">No hay preguntas agregadas aún.</p>
                @else
                    @foreach($examen->preguntas as $index => $pregunta)
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $index + 1 }}.</strong> {{ $pregunta->enunciado }}
                                        <span class="badge badge-info">{{ $pregunta->puntaje }} pts</span>
                                        <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $pregunta->tipo)) }}</span>
                                    </div>
                                    <form action="{{ route('preguntas.destroy', $pregunta->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta pregunta?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                
                                @if($pregunta->tipo == 'opcion_simple' && $pregunta->opciones)
                                    <ul class="mt-2">
                                        @foreach($pregunta->opciones as $opcion)
                                            <li class="{{ $opcion == $pregunta->respuesta_correcta ? 'text-success font-weight-bold' : '' }}">
                                                {{ $opcion }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @elseif($pregunta->tipo == 'verdadero_falso')
                                    <p class="mb-0 mt-2"><strong>Respuesta correcta:</strong> {{ $pregunta->respuesta_correcta }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">Agregar Pregunta</h3>
            </div>
            <form action="{{ route('examenes.preguntas.store', $examen->id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Tipo</label>
                        <select name="tipo" id="tipoPregunta" class="form-control" required>
                            <option value="opcion_simple">Opción Simple</option>
                            <option value="verdadero_falso">Verdadero/Falso</option>
                            <option value="abierta">Abierta</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Enunciado</label>
                        <textarea name="enunciado" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Puntaje</label>
                        <input type="number" name="puntaje" class="form-control" step="0.5" value="1" required>
                    </div>

                    <div id="opcionesContainer" style="display:none;">
                        <div class="form-group">
                            <label>Opciones (una por línea)</label>
                            <input type="text" name="opciones[]" class="form-control mb-1" placeholder="Opción A">
                            <input type="text" name="opciones[]" class="form-control mb-1" placeholder="Opción B">
                            <input type="text" name="opciones[]" class="form-control mb-1" placeholder="Opción C">
                            <input type="text" name="opciones[]" class="form-control" placeholder="Opción D">
                        </div>
                    </div>

                    <div id="respuestaContainer">
                        <div class="form-group" id="respuestaVF" style="display:none;">
                            <label>Respuesta Correcta <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="respuesta_correcta" value="Verdadero" id="rbVerdadero" required>
                                    <label class="form-check-label" for="rbVerdadero">Verdadero</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="respuesta_correcta" value="Falso" id="rbFalso" required>
                                    <label class="form-check-label" for="rbFalso">Falso</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="respuestaTexto" style="display:none;">
                            <label>Respuesta Correcta <span class="text-danger">*</span></label>
                            <input type="text" name="respuesta_correcta" class="form-control" placeholder="Escribe la respuesta correcta exacta" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block">Agregar Pregunta</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
document.getElementById('tipoPregunta').addEventListener('change', function() {
    const tipo = this.value;
    const opcionesContainer = document.getElementById('opcionesContainer');
    const respuestaVF = document.getElementById('respuestaVF');
    const respuestaTexto = document.getElementById('respuestaTexto');
    
    // Hide all first and remove required
    opcionesContainer.style.display = 'none';
    respuestaVF.style.display = 'none';
    respuestaTexto.style.display = 'none';
    
    // Remove required from all
    document.querySelectorAll('#respuestaVF input').forEach(inp => inp.removeAttribute('required'));
    document.querySelector('#respuestaTexto input').removeAttribute('required');
    
    if (tipo === 'opcion_simple') {
        opcionesContainer.style.display = 'block';
        respuestaTexto.style.display = 'block';
        document.querySelector('#respuestaTexto input').setAttribute('required', 'required');
    } else if (tipo === 'verdadero_falso') {
        respuestaVF.style.display = 'block';
        document.querySelectorAll('#respuestaVF input').forEach(inp => inp.setAttribute('required', 'required'));
    }
    // For 'abierta', nothing is shown (no correct answer needed)
});

// Trigger on page load to set initial state
document.getElementById('tipoPregunta').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection
