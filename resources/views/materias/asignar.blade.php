@extends('layouts.app')

@section('title', 'Asignar Estudiantes a Materia')

@section('main_content_body')
<div class="card">
    <div class="card-header">
        <h1>Asignar Estudiantes - {{ $materia->nombre }}</h1>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="margin-bottom: 30px;">
            @if(isset($cuposDisponibles))
                <div class="alert alert-info" style="margin-bottom: 15px;">
                    <strong>Cupos disponibles:</strong> 
                    @if($cuposDisponibles === null)
                        Sin límite
                    @else
                        {{ $cuposDisponibles }} de {{ $materia->cupo_maximo }}
                    @endif
                </div>
            @endif

            <h2>
                @if(Auth::user()->rol === 'admin')
                    Asignar Nuevo Estudiante (Asignación Directa)
                @else
                    Solicitar Inscripción de Estudiante
                @endif
            </h2>

            <!-- Student Filter Form -->
            <form method="GET" action="{{ route('materias.asignar', $materia->id_materia) }}" style="background: #f4f6f9; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <div class="row">
                    <div class="col-md-5">
                        <select name="grado" class="form-control" onchange="this.form.submit()">
                            <option value="">Filtrar por Grado</option>
                            @foreach(['1er Año', '2do Año', '3er Año', '4to Año', '5to Año'] as $g)
                                <option value="{{ $g }}" {{ (isset($filterGrado) && $filterGrado == $g) ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <select name="seccion" class="form-control" onchange="this.form.submit()">
                            <option value="">Filtrar por Sección</option>
                            @foreach(['A', 'B', 'C', 'D', 'E', 'U'] as $s)
                                <option value="{{ $s }}" {{ (isset($filterSeccion) && $filterSeccion == $s) ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('materias.asignar', $materia->id_materia) }}" class="btn btn-default btn-block">Limpiar</a>
                    </div>
                </div>
            </form>
            <form method="POST" action="{{ route('materias.asignar.store', $materia->id_materia) }}" style="display: flex; gap: 10px; align-items: flex-end;">
                @csrf
                <div class="form-group" style="flex: 1;">
                    <label for="id_estudiante">Seleccionar Estudiante</label>
                    <select id="id_estudiante" name="id_estudiante" required>
                        <option value="">Seleccione un estudiante</option>
                        @foreach($estudiantes as $estudiante)
                            @if(!$estudiantesAsignados->contains('id_estudiante', $estudiante->id_usuario))
                                <option value="{{ $estudiante->id_usuario }}">{{ $estudiante->nombre }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width: auto; padding: 12px 30px;">
                    @if(Auth::user()->rol === 'admin')
                        Asignar
                    @else
                        Solicitar Inscripción
                    @endif
                </button>
            </form>
            @if(Auth::user()->rol === 'docente')
                <p style="margin-top: 10px; color: #666; font-size: 14px;">
                    <em>Nota: La inscripción requiere aprobación del administrador. Serás notificado cuando se resuelva tu solicitud.</em>
                </p>
            @endif
        </div>

        <h2>Estudiantes Asignados</h2>
        @if($estudiantesAsignados->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Promedio</th>
                        <th>Avance</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($estudiantesAsignados as $estudianteMateria)
                    <tr>
                        <td>{{ $estudianteMateria->estudiante->nombre }}</td>
                        <td>{{ number_format($estudianteMateria->promedio, 2) }}</td>
                        <td>{{ number_format($estudianteMateria->avance, 2) }}%</td>
                        <td>
                            <form action="{{ route('materias.asignar.destroy', [$materia->id_materia, $estudianteMateria->id_estudiante_materia]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="width: auto; padding: 5px 15px;" onclick="return confirm('¿Estás seguro de desasignar a este estudiante?')">Desasignar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center">No hay estudiantes asignados a esta materia</p>
        @endif

        <div style="margin-top: 20px;">
            <a href="{{ route('materias.index') }}" class="btn" style="background: #6c757d; color: white; width: auto;">Volver a Materias</a>
        </div>
    </div>
</div>
@endsection

