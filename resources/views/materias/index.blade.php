@extends('layouts.app')

@section('title', 'Gestión de Materias')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Gestión de Materias</h1>
    </div>
    <div class="card-body">
        @if(Auth::user()->rol === 'docente' || Auth::user()->rol === 'admin')
            <div style="margin-bottom: 20px;">
                <a href="{{ route('materias.create') }}" class="btn btn-primary" style="width: auto;">Crear Nueva Materia</a>
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
                        @if(Auth::user()->rol === 'docente' || Auth::user()->rol === 'admin')
                            <th>Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($materias as $materia)
                    <tr>
                        <td>{{ $materia->nombre }}</td>
                        <td>{{ Str::limit($materia->descripcion ?? 'Sin descripción', 50) }}</td>
                        <td>{{ $materia->docente->nombre ?? 'Sin asignar' }}</td>
                        <td>{{ $materia->periodo ?? '-' }}</td>
                        <td>{{ $materia->horario ?? '-' }}</td>
                        @if(Auth::user()->rol === 'docente' || Auth::user()->rol === 'admin')
                            <td>
                                <a href="{{ route('materias.show', $materia->id_materia) }}" class="btn btn-primary" style="width: auto; padding: 5px 15px; margin-right: 5px;">Ver</a>
                                <a href="{{ route('materias.edit', $materia->id_materia) }}" class="btn btn-warning" style="width: auto; padding: 5px 15px; margin-right: 5px;">Editar</a>
                                @if(Auth::user()->rol === 'docente' && $materia->id_docente === Auth::id() || Auth::user()->rol === 'admin')
                                    <a href="{{ route('materias.asignar', $materia->id_materia) }}" class="btn btn-primary" style="width: auto; padding: 5px 15px; margin-right: 5px; background: #17a2b8;">Asignar Estudiantes</a>
                                    <a href="{{ route('notas.docente.index', $materia->id_materia) }}" class="btn btn-success" style="width: auto; padding: 5px 15px; margin-right: 5px;">Gestionar Notas</a>
                                @endif
                                <form action="{{ route('materias.destroy', $materia->id_materia) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="width: auto; padding: 5px 15px;" onclick="return confirm('¿Estás seguro de eliminar esta materia?')">Eliminar</button>
                                </form>
                            </td>
                        @endif
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

