@extends('layouts.app')

@section('title', 'Crear Examen')

@section('main_content_body')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Crear Nuevo Examen</h3>
    </div>
    <form action="{{ route('examenes.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Materia</label>
                <select name="id_materia" class="form-control" required>
                    <option value="">Seleccione una materia</option>
                    @foreach($materias as $materia)
                        <option value="{{ $materia->id_materia }}">
                            {{ $materia->nombre }} - {{ $materia->grado }} "{{ $materia->seccion }}"
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Lapso</label>
                <select name="lapso" class="form-control" required>
                    <option value="">Seleccione el lapso</option>
                    <option value="1">Lapso 1</option>
                    <option value="2">Lapso 2</option>
                    <option value="3">Lapso 3</option>
                </select>
            </div>

            <div class="form-group">
                <label>Número de Evaluación</label>
                <select name="numero_evaluacion" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="1">Evaluación 1</option>
                    <option value="2">Evaluación 2</option>
                    <option value="3">Evaluación 3</option>
                    <option value="4">Evaluación 4</option>
                    <option value="5">Evaluación 5</option>
                </select>
            </div>

            <div class="form-group">
                <label>Título del Examen</label>
                <input type="text" name="titulo" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3"></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Fecha Inicio</label>
                        <input type="datetime-local" name="fecha_inicio" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Fecha Fin</label>
                        <input type="datetime-local" name="fecha_fin" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success">Crear y Gestionar Preguntas</button>
            <a href="{{ route('examenes.index_docente') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
