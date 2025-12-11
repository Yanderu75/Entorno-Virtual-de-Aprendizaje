@extends('layouts.app')

@section('title', 'Subir Nuevo Material')

@section('main_content_body')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title">Subir Nuevo Material de Estudio</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('recursos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="titulo">Título o Descripción del Archivo</label>
                <input type="text" name="titulo" class="form-control" required placeholder="Ej: Guía de Matemáticas - Lapso 1">
            </div>

            <div class="form-group">
                <label for="id_materia">Seleccione Materia</label>
                <select name="id_materia" class="form-control" required>
                    <option value="">-- Seleccionar Materia --</option>
                    @foreach($materias as $materia)
                        <option value="{{ $materia->id_materia }}">
                            {{ $materia->nombre }} - {{ $materia->grado }} "{{ $materia->seccion }}"
                        </option>
                    @endforeach
                </select>
                @if($materias->isEmpty())
                    <small class="text-danger">No tienes materias asignadas para subir recursos.</small>
                @endif
            </div>

            <div class="form-group">
                <label for="archivo">Seleccionar Archivo</label>
                <input type="file" name="archivo" class="form-control-file" required>
                <small class="form-text text-muted">Formatos permitidos: PDF, Word, Imagenes. Max: 20MB</small>
            </div>

            <div class="d-flex justify-content-between pt-3">
                <a href="{{ route('recursos.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Subir Material</button>
            </div>
        </form>
    </div>
</div>
@endsection
