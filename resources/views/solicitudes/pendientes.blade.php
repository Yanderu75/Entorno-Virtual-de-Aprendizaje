@extends('layouts.app')

@section('title', 'Solicitudes Pendientes')

@section('main_content_body')
<div class="card">
    <div class="card-header">
        <h1>Solicitudes de Inscripción Pendientes</h1>
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
                        <th>Docente</th>
                        <th>Estudiante</th>
                        <th>Materia</th>
                        <th>Cupos Disponibles</th>
                        <th>Fecha Solicitud</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($solicitudes as $solicitud)
                    <tr>
                        <td>{{ $solicitud->docente->nombre }}</td>
                        <td>{{ $solicitud->estudiante->nombre }}</td>
                        <td>{{ $solicitud->materia->nombre }}</td>
                        <td>
                            @if($solicitud->materia->cupo_maximo === null)
                                Sin límite
                            @else
                                {{ $solicitud->materia->cuposDisponibles() }} de {{ $solicitud->materia->cupo_maximo }}
                            @endif
                        </td>
                        <td>{{ $solicitud->fecha_solicitud->format('d/m/Y H:i') }}</td>
                        <td>
                            <div style="display: flex; gap: 10px;">
                                <form action="{{ route('admin.solicitudes.aprobar', $solicitud->id_solicitud) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success" style="width: auto; padding: 5px 15px;" onclick="return confirm('¿Aprobar esta solicitud?')">Aprobar</button>
                                </form>
                                <button type="button" class="btn btn-danger" style="width: auto; padding: 5px 15px;" onclick="mostrarFormularioRechazo({{ $solicitud->id_solicitud }})">Rechazar</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center">No hay solicitudes pendientes</p>
        @endif

        <div style="margin-top: 20px;">
            <a href="{{ route('dashboard.admin') }}" class="btn" style="background: #6c757d; color: white; width: auto;">Volver al Dashboard</a>
        </div>
    </div>
</div>

<!-- Modal para rechazar solicitud -->
<div id="modalRechazo" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 8px; max-width: 500px; width: 90%;">
        <h2>Rechazar Solicitud</h2>
        <form id="formRechazo" method="POST" action="">
            @csrf
            <div class="form-group">
                <label for="motivo_rechazo">Motivo del rechazo (mínimo 10 caracteres):</label>
                <textarea id="motivo_rechazo" name="motivo_rechazo" rows="4" required minlength="10" maxlength="500" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
            </div>
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-danger" style="flex: 1;">Confirmar Rechazo</button>
                <button type="button" class="btn" style="background: #6c757d; color: white; flex: 1;" onclick="cerrarModal()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
function mostrarFormularioRechazo(idSolicitud) {
    document.getElementById('formRechazo').action = '{{ url("/admin/solicitudes") }}/' + idSolicitud + '/rechazar';
    document.getElementById('modalRechazo').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('modalRechazo').style.display = 'none';
    document.getElementById('motivo_rechazo').value = '';
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalRechazo').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});
</script>
@endsection

