@extends('layouts.app')

@section('title', 'Detalle de Materia')

@section('main_content_body')
<div class="card">
    <div class="card-header">
        <h1>{{ $materia->nombre }}</h1>
    </div>
    <div class="card-body">
        <div style="margin-bottom: 20px;">
            <strong>Descripción:</strong>
            <p>{{ $materia->descripcion ?? 'Sin descripción' }}</p>
        </div>

        <div style="margin-bottom: 20px;">
            <strong>Docente:</strong>
            <p>{{ $materia->docente->nombre ?? 'Sin asignar' }}</p>
        </div>

        <div style="margin-bottom: 20px;">
            <strong>Periodo:</strong>
            <p>{{ $materia->periodo ?? '-' }}</p>
        </div>

        <div style="margin-bottom: 20px;">
            <strong>Horario:</strong>
            <p>{{ $materia->horario ?? '-' }}</p>
        </div>

        <hr>



        @if(Auth::user()->rol === 'docente' || Auth::user()->rol === 'admin')
            <div style="margin-top: 30px;">
                <a href="{{ route('materias.edit', $materia->id_materia) }}" class="btn btn-warning" style="width: auto;">Editar</a>
                @if(Auth::user()->rol === 'docente' && $materia->id_docente === Auth::id() || Auth::user()->rol === 'admin')
                    <a href="{{ route('materias.asignar', $materia->id_materia) }}" class="btn btn-primary" style="width: auto; margin-left: 10px; background: #17a2b8;">Asignar Estudiantes</a>
                    <a href="{{ route('notas.docente.index', $materia->id_materia) }}" class="btn btn-success" style="width: auto; margin-left: 10px;">Gestionar Notas</a>
                @endif
                <a href="{{ route('materias.index') }}" class="btn" style="background: #6c757d; color: white; width: auto; margin-left: 10px;">Volver</a>
            </div>
        @else
            <a href="{{ route('notas.index') }}" class="btn btn-primary" style="width: auto;">Volver a Mis Notas</a>
        @endif
    </div>
</div>
@endsection

