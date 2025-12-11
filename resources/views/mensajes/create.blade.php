@extends('layouts.app')

@section('title', 'Redactar Mensaje')

@section('main_content_body')
<div class="row">
    <div class="col-md-3">
        <a href="{{ route('mensajes.index') }}" class="btn btn-primary btn-block mb-3">Volver a Recibidos</a>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Carpetas</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a href="{{ route('mensajes.index') }}" class="nav-link">
                            <i class="fas fa-inbox"></i> Entrada
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('mensajes.sent') }}" class="nav-link">
                            <i class="far fa-envelope"></i> Enviados
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Redactar Nuevo Mensaje</h3>
            </div>
            <!-- /.card-header -->
            <form action="{{ route('mensajes.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Para:</label>
                        @if(isset($replyUser) && $replyUser)
                            <input type="hidden" name="destinatario" value="{{ $replyUser->id_usuario }}">
                            <div class="form-control" readonly style="background-color: #e9ecef;">
                                <strong>{{ $replyUser->nombre }}</strong> ({{ ucfirst($replyUser->rol) }})
                            </div>
                        @else
                            <select class="form-control" name="destinatario" required>
                                <option value="">Seleccione un destinatario...</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id_usuario }}">{{ $usuario->nombre }} ({{ ucfirst($usuario->rol) }})</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Mensaje:</label>
                        <textarea id="compose-textarea" name="mensaje" class="form-control" style="height: 300px" required placeholder="Escriba su mensaje aquí...">{{ $body ?? '' }}</textarea>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="float-right">
                        <button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> Enviar</button>
                    </div>
                    <button type="reset" class="btn btn-default"><i class="fas fa-times"></i> Cancelar</button>
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
@endsection
