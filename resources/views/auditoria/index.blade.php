@extends('layouts.app')

@section('title', 'Historial de Auditoría')

@section('main_content_body')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Historial de Auditoría y Seguridad</h3>
        <div class="card-tools">
            <form action="{{ route('auditoria.reporte') }}" method="GET" target="_blank" class="d-inline">
                <!-- Preserve filters for report -->
                <input type="hidden" name="id_usuario" value="{{ request('id_usuario') }}">
                <input type="hidden" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
                <input type="hidden" name="fecha_fin" value="{{ request('fecha_fin') }}">
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> Descargar Reporte PDF
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <form action="{{ route('auditoria.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Usuario</label>
                        <select name="id_usuario" class="form-control">
                            <option value="">-- Todos --</option>
                            @foreach($usuarios as $user)
                                <option value="{{ $user->id_usuario }}" {{ request('id_usuario') == $user->id_usuario ? 'selected' : '' }}>
                                    {{ $user->nombre }} ({{ $user->rol }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha Fin</label>
                        <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-group w-100">
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-filter"></i> Filtrar</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Table -->
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Fecha/Hora</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Acción</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>
                        @if($log->usuario)
                            {{ $log->usuario->nombre }}
                        @else
                            <span class="text-muted">Desconocido/Sistema</span>
                        @endif
                    </td>
                    <td>
                        @if($log->usuario)
                            <span class="badge badge-{{ $log->usuario->rol == 'admin' ? 'danger' : ($log->usuario->rol == 'docente' ? 'warning' : 'info') }}">
                                {{ ucfirst($log->usuario->rol) }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $log->accion }}</td>
                    <td>{{ $log->ip }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">No hay registros de auditoría.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
