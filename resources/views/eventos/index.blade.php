@extends('layouts.app')

@section('title', 'Calendario Académico')

@section('content_header')
    <h1>Planificación Académica</h1>
@stop

@section('main_content_body')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="sticky-top mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Eventos Recientes</h4>
                    </div>
                    <div class="card-body">
                        <!-- the events -->
                        <div id="external-events">
                            <p class="text-muted">Los eventos se visualizan en el calendario.</p>
                            @if(Auth::user()->rol != 'estudiante')
                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-create-event">
                                <i class="fas fa-plus"></i> Añadir Evento
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="card card-primary">
                <div class="card-body p-0">
                    <!-- THE CALENDAR -->
                    <div id="calendar"></div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>

<!-- Modal Create Event -->
<div class="modal fade" id="modal-create-event">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('eventos.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Nuevo Evento Acádémico</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Título</label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Tipo</label>
                        <select name="tipo" class="form-control">
                            <option value="clase">Clase</option>
                            <option value="examen">Examen</option>
                            <option value="entrega">Entrega / Tarea</option>
                            <option value="general">General / Evento</option>
                        </select>
                    </div>
                    @if(isset($materias) && count($materias) > 0)
                    <div class="form-group">
                        <label>Materia (Opcional)</label>
                        <select name="id_materia" class="form-control">
                            <option value="">-- General --</option>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id_materia }}">{{ $materia->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group">
                        <label>Fecha Inicio</label>
                        <input type="datetime-local" name="fecha_inicio" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Fecha Fin</label>
                        <input type="datetime-local" name="fecha_fin" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Evento</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css')
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <style>
        #calendar {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
        }
    </style>
@stop

@section('js')
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js'></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          locale: 'es',
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
          },
          events: '{{ route("eventos.index") }}', // AJAX fetching
          editable: false,
          selectable: true,
          eventClick: function(info) {
            alert('Evento: ' + info.event.title);
            // Future: Show modal details or delete option
          }
        });
        calendar.render();
      });
    </script>
@stop
