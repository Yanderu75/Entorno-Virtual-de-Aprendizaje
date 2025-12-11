@extends('layouts.app')

@section('title', 'Materiales de Estudio')

@section('main_content_body')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title"><i class="fas fa-book-reader"></i> Repositorio de Materiales de Estudio</h3>
        <div class="card-tools">
            @if(Auth::user()->rol == 'docente' || Auth::user()->rol == 'admin')
            <a href="{{ route('recursos.create') }}" class="btn btn-tool" style="color: white; font-weight: bold;">
                <i class="fas fa-plus"></i> Subir Nuevo Material
            </a>
            @endif
        </div>
    </div>
    <div class="card-body">
        
        <!-- Filters -->
        <form method="GET" action="{{ route('recursos.index') }}" class="mb-4 p-3 bg-light rounded">
            <div class="row">
                <div class="col-md-4">
                    <label>Filtrar por Año</label>
                    <select name="grado" class="form-control" onchange="this.form.submit()">
                        <option value="">Todos los Años</option>
                        @foreach(['1er Año', '2do Año', '3er Año', '4to Año', '5to Año'] as $g)
                            <option value="{{ $g }}" {{ request('grado') == $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Buscar por Profesor</label>
                    <input type="text" name="docente" class="form-control" placeholder="Nombre del docente..." value="{{ request('docente') }}">
                </div>
                <div class="col-md-2" style="padding-top: 32px;">
                    <button type="submit" class="btn btn-primary btn-block">Buscar</button>
                </div>
                <div class="col-md-2" style="padding-top: 32px;">
                    <a href="{{ route('recursos.index') }}" class="btn btn-default btn-block">Limpiar</a>
                </div>
            </div>
        </form>

        @if($recursos->count() > 0)
        <div class="row">
            @foreach($recursos as $recurso)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge badge-info">{{ $recurso->materia->grado }} "{{ $recurso->materia->seccion }}"</span>
                            <small class="text-muted">{{ $recurso->created_at->format('d/m/Y') }}</small>
                        </div>
                        <h5 class="card-title text-primary font-weight-bold mb-2">{{ $recurso->titulo }}</h5>
                        <p class="card-text mb-1">
                            <strong>Materia:</strong> {{ $recurso->materia->nombre }}
                        </p>
                        <p class="card-text text-muted mb-3">
                            <i class="fas fa-chalkboard-teacher"></i> {{ $recurso->materia->docente->nombre ?? 'Sin asignar' }}
                        </p>
                        
                        <div class="mt-auto">
                            <a href="{{ route('recursos.download', $recurso->id_recurso) }}" class="btn btn-success btn-block">
                                <i class="fas fa-download"></i> Descargar {{ strtoupper($recurso->tipo) }}
                            </a>
                            
                            @if(Auth::user()->rol == 'admin' || (Auth::user()->rol == 'docente' && Auth::id() == $recurso->materia->id_docente))
                            <form action="{{ route('recursos.destroy', $recurso->id_recurso) }}" method="POST" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm btn-block" onclick="return confirm('¿Estás seguro de eliminar este archivo?')">
                                    Eliminar
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
            <div class="alert alert-info text-center">
                No se encontraron materiales de estudio con los filtros seleccionados.
            </div>
        @endif
    </div>
</div>
@endsection
