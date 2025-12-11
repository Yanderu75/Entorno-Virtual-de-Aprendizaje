@extends('layouts.app')

@section('title', 'Editar Examen')

@section('main_content_body')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Editar Examen</h3>
    </div>
    <form action="{{ route('examenes.update', $examen->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Materia</label>
                <select name="id_materia" class="form-control" required>
                    <option value="">Seleccione una materia</option>
                    @foreach($materias as $materia)
                        <option value="{{ $materia->id_materia }}" {{ $examen->id_materia == $materia->id_materia ? 'selected' : '' }}>
                            {{ $materia->nombre }} - {{ $materia->grado }} "{{ $materia->seccion }}"
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Lapso</label>
                <select name="lapso" class="form-control" required>
                    <option value="">Seleccione el lapso</option>
                    <option value="1" {{ $examen->lapso == 1 ? 'selected' : '' }}>Lapso 1</option>
                    <option value="2" {{ $examen->lapso == 2 ? 'selected' : '' }}>Lapso 2</option>
                    <option value="3" {{ $examen->lapso == 3 ? 'selected' : '' }}>Lapso 3</option>
                </select>
            </div>

            <div class="form-group">
                <label>Número de Evaluación</label>
                <select name="numero_evaluacion" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="1" {{ $examen->numero_evaluacion == 1 ? 'selected' : '' }}>Evaluación 1</option>
                    <option value="2" {{ $examen->numero_evaluacion == 2 ? 'selected' : '' }}>Evaluación 2</option>
                    <option value="3" {{ $examen->numero_evaluacion == 3 ? 'selected' : '' }}>Evaluación 3</option>
                    <option value="4" {{ $examen->numero_evaluacion == 4 ? 'selected' : '' }}>Evaluación 4</option>
                    <option value="5" {{ $examen->numero_evaluacion == 5 ? 'selected' : '' }}>Evaluación 5</option>
                </select>
            </div>

            <div class="form-group">
                <label>Título del Examen</label>
                <input type="text" name="titulo" class="form-control" value="{{ $examen->titulo }}" required>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3">{{ $examen->descripcion }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Fecha Inicio</label>
                        <input type="datetime-local" name="fecha_inicio" class="form-control" 
                               value="{{ $examen->fecha_inicio->format('Y-m-d\TH:i') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Fecha Fin</label>
                        <input type="datetime-local" name="fecha_fin" class="form-control" 
                               value="{{ $examen->fecha_fin->format('Y-m-d\TH:i') }}" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <a href="{{ route('examenes.gestion', $examen->id) }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
