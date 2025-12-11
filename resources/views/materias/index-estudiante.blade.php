@extends('layouts.app')

@section('title', 'Mis Materias')

@section('main_content_body')
<div class="card">
    <div class="card-header">
        <h1>Mis Materias</h1>
    </div>
    <div class="card-body">
        @if($materiasAsignadas->count() > 0)
            <div class="row">
                @foreach($materiasAsignadas as $estudianteMateria)
                    <div class="col-md-4">
                        <div class="card card-purple card-outline">
                            <div class="card-header">
                                <h3 class="card-title">{{ $estudianteMateria->materia->nombre }}</h3>
                            </div>
                            <div class="card-body">
                                <p><strong>Docente:</strong> {{ $estudianteMateria->materia->docente->nombre ?? 'Sin asignar' }}</p>
                                <p><strong>Periodo:</strong> {{ $estudianteMateria->materia->periodo ?? '-' }}</p>
                                <p><strong>Horario:</strong> {{ $estudianteMateria->materia->horario ?? '-' }}</p>
                                <hr>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <label>Promedio</label>
                                        <h4 class="{{ $estudianteMateria->promedio >= 10 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($estudianteMateria->promedio, 2) }}
                                        </h4>
                                    </div>
                                    <div class="col-6">
                                        <label>Avance</label>
                                        <h4>{{ number_format($estudianteMateria->avance, 2) }}%</h4>
                                    </div>
                                </div>
                                <a href="{{ route('notas.show', $estudianteMateria->materia->id_materia) }}" class="btn btn-purple btn-block mt-3">Ver Notas</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center">No tienes materias asignadas aún</p>
        @endif
    </div>
</div>
@endsection

