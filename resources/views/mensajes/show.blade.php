@extends('layouts.app')

@section('title', 'Leer Mensaje')

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
                <h3 class="card-title">Leer Mensaje</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <div class="mailbox-read-info">
                    <h5>De: {{ $mensaje->emisor->nombre }} ({{ $mensaje->emisor->correo }})</h5>
                    <h6>Para: {{ $mensaje->receptor->nombre }}
                        <span class="mailbox-read-time float-right">{{ $mensaje->created_at->format('d M. Y h:i A') }}</span>
                    </h6>
                </div>
                <!-- /.mailbox-read-info -->
                <div class="mailbox-read-message">
                    <p style="white-space: pre-wrap;">{{ $mensaje->mensaje }}</p>
                </div>
                <!-- /.mailbox-read-message -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <a href="{{ route('mensajes.create', ['reply_to' => $mensaje->id_emisor, 'ref' => $mensaje->id]) }}" class="btn btn-default"><i class="fas fa-reply"></i> Responder</a>
                <!-- Future: Delete button -->
            </div>
            <!-- /.card-footer -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
@endsection
