@extends('layouts.app')

@section('title', 'Gestión de Exámenes')

@section('main_content_body')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Mis Exámenes</h3>
        <div class="card-tools">
            <a href="{{ route('examenes.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Crear Examen
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($examenes->isEmpty())
            <p class="text-muted">No has creado ningún examen aún.</p>
        @else
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Materia</th>
                        <th>Año</th>
                        <th>Sección</th>
                        <th>Preguntas</th>
                        <th>Fecha Inicio</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($examenes as $examen)
                        <tr>
                            <td>{{ $examen->titulo }}</td>
                            <td>{{ $examen->materia->nombre }}</td>
                            <td>{{ $examen->materia->grado }}</td>
                            <td>{{ $examen->materia->seccion }}</td>
                            <td>{{ $examen->preguntas->count() }}</td>
                            <td>{{ $examen->fecha_inicio->format('d/m/Y') }}</td>
                            <td>
                                @if($examen->publicado)
                                    <span class="badge badge-success">Publicado</span>
                                @else
                                    <span class="badge badge-secondary">Borrador</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('examenes.gestion', $examen->id) }}" class="btn btn-sm btn-primary" title="Gestionar">
                                    <i class="fas fa-tasks"></i>
                                </a>
                                <a href="{{ route('examenes.edit', $examen->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('examenes.destroy', $examen->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este examen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
