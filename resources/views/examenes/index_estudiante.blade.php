@extends('layouts.app')

@section('title', 'Mis Exámenes')

@section('main_content_body')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Exámenes Disponibles</h3>
    </div>
    <div class="card-body">
        @if($examenes->isEmpty())
            <p class="text-muted">No hay exámenes disponibles en este momento.</p>
        @else
            <div class="row">
                @foreach($examenes as $examen)
                    <div class="col-md-6 mb-3">
                        <div class="card {{ $examen->intento ? 'border-success' : 'border-primary' }}">
                            <div class="card-header">
                                <h5>{{ $examen->titulo }}</h5>
                                <small class="text-muted">{{ $examen->materia->nombre }}</small>
                            </div>
                            <div class="card-body">
                                <p>{{ $examen->descripcion }}</p>
                                <p><strong>Disponible:</strong> {{ $examen->fecha_inicio->format('d/m/Y H:i') }} - {{ $examen->fecha_fin->format('d/m/Y H:i') }}</p>
                                
                                @if($examen->intento)
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle"></i> Presentado
                                        <br><strong>Nota:</strong> {{ $examen->intento->nota_final }}
                                        @if(!$examen->intento->correccion_docente)
                                            <br><small>(Pendiente de corrección manual)</small>
                                        @endif
                                    </div>
                                @else
                                    @if(now()->between($examen->fecha_inicio, $examen->fecha_fin))
                                        <a href="{{ route('examenes.presentar', $examen->id) }}" class="btn btn-primary btn-block">
                                            <i class="fas fa-edit"></i> Presentar Examen
                                        </a>
                                    @else
                                        <button class="btn btn-secondary btn-block" disabled>
                                            No disponible
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
