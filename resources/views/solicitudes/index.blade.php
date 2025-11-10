@extends('layouts.app')

@section('title', 'Mis Solicitudes de Inscripción')

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Mis Solicitudes de Inscripción</h1>
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

        @if($solicitudes->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Materia</th>
                        <th>Estado</th>
                        <th>Fecha Solicitud</th>
                        <th>Fecha Resolución</th>
                        <th>Motivo Rechazo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($solicitudes as $solicitud)
                    <tr>
                        <td>{{ $solicitud->estudiante->nombre }}</td>
                        <td>{{ $solicitud->materia->nombre }}</td>
                        <td>
                            @if($solicitud->estado === 'pendiente')
                                <span class="badge badge-warning">Pendiente</span>
                            @elseif($solicitud->estado === 'aprobada')
                                <span class="badge badge-success">Aprobada</span>
                            @else
                                <span class="badge badge-danger">Rechazada</span>
                            @endif
                        </td>
                        <td>{{ $solicitud->fecha_solicitud->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($solicitud->fecha_resolucion)
                                {{ $solicitud->fecha_resolucion->format('d/m/Y H:i') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($solicitud->motivo_rechazo)
                                <span style="color: #dc3545;">{{ $solicitud->motivo_rechazo }}</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center">No tienes solicitudes de inscripción</p>
        @endif

        <div style="margin-top: 20px;">
            <a href="{{ route('materias.index') }}" class="btn" style="background: #6c757d; color: white; width: auto;">Volver a Materias</a>
        </div>
    </div>
</div>
@endsection

