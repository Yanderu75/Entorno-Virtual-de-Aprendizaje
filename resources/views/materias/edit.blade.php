@extends('layouts.app')

@section('title', 'Editar Materia')

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Editar Materia</h1>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('materias.update', $materia->id_materia) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nombre">Nombre de la Materia</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $materia->nombre) }}" required>
                @error('nombre')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="4" style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 16px; font-family: inherit;">{{ old('descripcion', $materia->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="id_docente">Docente</label>
                <select id="id_docente" name="id_docente" required>
                    <option value="">Seleccione un docente</option>
                    @foreach($docentes as $docente)
                        <option value="{{ $docente->id_usuario }}" {{ old('id_docente', $materia->id_docente) == $docente->id_usuario ? 'selected' : '' }}>
                            {{ $docente->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('id_docente')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="periodo">Periodo</label>
                <input type="text" id="periodo" name="periodo" value="{{ old('periodo', $materia->periodo) }}" placeholder="Ej: 2024-1">
                @error('periodo')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="horario">Horario</label>
                <input type="text" id="horario" name="horario" value="{{ old('horario', $materia->horario) }}" placeholder="Ej: Lunes 8:00-10:00">
                @error('horario')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="cupo_maximo">Cupo Máximo (Opcional)</label>
                <input type="number" id="cupo_maximo" name="cupo_maximo" value="{{ old('cupo_maximo', $materia->cupo_maximo) }}" min="1" placeholder="Dejar vacío para sin límite">
                <small style="color: #666; display: block; margin-top: 5px;">Deje vacío si no desea establecer un límite de cupos</small>
                @error('cupo_maximo')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Materia</button>
            <a href="{{ route('materias.index') }}" class="btn" style="background: #6c757d; color: white; width: auto; margin-left: 10px;">Cancelar</a>
        </form>
    </div>
</div>
@endsection

