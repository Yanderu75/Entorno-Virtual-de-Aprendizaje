@extends('layouts.app')

@section('title', 'Gestión de Materias')

@php
use Illuminate\Support\Str;
@endphp

@section('main_content_body')
<div class="card">
    <div class="card-header">
        <h1>Gestión de Materias</h1>
    </div>
    <div class="card-body">
        @if(Auth::user()->rol === 'admin')
            <div class="row mb-3">
                <div class="col-md-2">
                    <a href="{{ route('materias.create') }}" class="btn btn-primary btn-block">Crear Nueva Materia</a>
                </div>
                <div class="col-md-10">
                    <form method="GET" action="{{ route('materias.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="nombre" class="form-control" onchange="this.form.submit()">
                                    <option value="">Filtrar por Materia</option>
                                    @foreach(['Castellano', 'Matemáticas', 'Inglés', 'GHC (Geografía, Historia)', 'Arte y Patrimonio', 'Educación Física', 'Ciencias Naturales', 'Biología', 'Física', 'Química', 'Soberanía', 'Grupo de Recreación'] as $m)
                                        <option value="{{ $m }}" {{ (isset($filterNombre) && $filterNombre == $m) ? 'selected' : '' }}>{{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="grado" class="form-control" onchange="this.form.submit()">
                                    <option value="">Filtrar por Grado</option>
                                    @foreach(['1er Año', '2do Año', '3er Año', '4to Año', '5to Año'] as $g)
                                        <option value="{{ $g }}" {{ (isset($filterGrado) && $filterGrado == $g) ? 'selected' : '' }}>{{ $g }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="seccion" class="form-control" onchange="this.form.submit()">
                                    <option value="">Filtrar por Sección</option>
                                    @foreach(['A', 'B', 'C', 'D', 'E', 'U'] as $s)
                                        <option value="{{ $s }}" {{ (isset($filterSeccion) && $filterSeccion == $s) ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('materias.index') }}" class="btn btn-default btn-block">Limpiar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($materias->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Docente</th>
                        <th>Periodo</th>
                        <th>Horario</th>
                        <th>Estudiantes</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materias as $materia)
                    <tr>
                        <td>
                            {{ $materia->nombre }}
                            @if($materia->grado)
                                <br><span class="badge badge-secondary">{{ $materia->grado }} "{{ $materia->seccion }}"</span>
                            @endif
                        </td>
                        <td>{{ Str::limit($materia->descripcion ?? 'Sin descripción', 50) }}</td>
                        <td>
                            <b>{{ $materia->docente->nombre ?? 'Sin asignar' }}</b>
                            @if($materia->docente && $materia->docente->especialidad)
                                <br><small class="text-muted">{{ $materia->docente->especialidad }}</small>
                            @endif
                        </td>
                        <td>{{ $materia->periodo ?? '-' }}</td>
                        <td>{{ $materia->horario ?? '-' }}</td>
                        <td>
                            <span class="badge badge-info">{{ $materia->estudiantes->count() }} Estudiantes</span>
                        </td>
                        <td>
                            @if(Auth::user()->rol === 'admin')
                                <a href="{{ route('materias.show', $materia->id_materia) }}" class="btn btn-info" style="width: auto; padding: 5px 15px; margin-right: 5px;">Ver</a>
                                <a href="{{ route('materias.edit', $materia->id_materia) }}" class="btn btn-warning" style="width: auto; padding: 5px 15px; margin-right: 5px;">Editar</a>
                                <form action="{{ route('materias.destroy', $materia->id_materia) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="width: auto; padding: 5px 15px;" onclick="return confirm('¿Estás seguro de eliminar esta materia?')">Eliminar</button>
                                </form>
                            @elseif(Auth::user()->rol === 'docente')
                                <a href="{{ route('materias.show', $materia->id_materia) }}" class="btn btn-info" style="width: auto; padding: 5px 15px; margin-right: 5px;">Ver</a>
                                <!-- Assignment button removed as per automation rules -->
                                <a href="{{ route('notas.docente.index', $materia->id_materia) }}" class="btn btn-success" style="width: auto; padding: 5px 15px; margin-right: 5px;">Gestionar Notas</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center">No hay materias registradas</p>
        @endif
    </div>
</div>
@endsection

