@extends('layouts.app')

@section('title', 'Crear Materia')

@section('main_content_body')
<div class="card">
    <div class="card-header">
        <h1>Crear Nueva Materia</h1>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('materias.store') }}">
            @csrf
            <div class="form-group">
                <label for="nombre">Nombre de la Materia</label>
                <select class="form-control" name="nombre" id="nombre" required>
                    <option value="">Seleccione Materia</option>
                    @foreach(['Castellano', 'Matemáticas', 'Inglés', 'GHC (Geografía, Historia)', 'Arte y Patrimonio', 'Educación Física', 'Ciencias Naturales', 'Biología', 'Física', 'Química', 'Soberanía', 'Grupo de Recreación'] as $m)
                        <option value="{{ $m }}" {{ old('nombre') == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
                @error('nombre')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="4" style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 16px; font-family: inherit;">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="id_docente">Docente</label>
                <select id="id_docente" name="id_docente" required>
                    <option value="">Seleccione un docente</option>
                    @foreach($docentes as $docente)
                        <option value="{{ $docente->id_usuario }}" {{ old('id_docente') == $docente->id_usuario ? 'selected' : '' }}>
                            {{ $docente->nombre }} {{ $docente->especialidad ? ' - ' . $docente->especialidad : '' }}
                        </option>
                    @endforeach
                </select>
                @error('id_docente')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="periodo">Periodo</label>
                <input type="text" id="periodo" name="periodo" value="{{ old('periodo') }}" placeholder="Ej: 2024-1">
                @error('periodo')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="horario">Horario</label>
                <input type="text" id="horario" name="horario" value="{{ old('horario') }}" placeholder="Ej: Lunes 8:00-10:00">
                @error('horario')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="grado">Grado / Año</label>
                        <select class="form-control" id="grado" name="grado" required>
                            <option value="">Seleccione Grado</option>
                            @foreach(['1er Año', '2do Año', '3er Año', '4to Año', '5to Año'] as $g)
                                <option value="{{ $g }}" {{ old('grado') == $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="seccion">Sección</label>
                        <select class="form-control" id="seccion" name="seccion" required>
                            <option value="">Seleccione Sección</option>
                            @foreach(['A', 'B', 'C', 'D', 'E', 'U'] as $s)
                                <option value="{{ $s }}" {{ old('seccion') == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="cupo_maximo">Cupo Máximo (Opcional)</label>
                <input type="number" id="cupo_maximo" name="cupo_maximo" value="{{ old('cupo_maximo') }}" min="1" placeholder="Dejar vacío para sin límite">
                <small style="color: #666; display: block; margin-top: 5px;">Deje vacío si no desea establecer un límite de cupos</small>
                @error('cupo_maximo')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Crear Materia</button>
            <a href="{{ route('materias.index') }}" class="btn" style="background: #6c757d; color: white; width: auto; margin-left: 10px;">Cancelar</a>
        </form>
    </div>
</div>
@endsection

