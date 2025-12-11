@extends('layouts.app')

@section('title', 'Bandeja de Entrada')

@section('main_content_body')
<div class="row">
    <div class="col-md-3">
        <a href="{{ route('mensajes.create') }}" class="btn btn-primary btn-block mb-3">Redactar</a>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Carpetas</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item active">
                        <a href="{{ route('mensajes.index') }}" class="nav-link">
                            <i class="fas fa-inbox"></i> Entrada
                            <span class="badge bg-primary float-right">{{ $mensajesRecibidos->where('leido', 0)->count() }}</span>
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
                <h3 class="card-title">Bandeja de Entrada</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <div class="table-responsive mailbox-messages">
                    <table class="table table-hover table-striped">
                        <tbody>
                        @forelse($mensajesRecibidos as $mensaje)
                            <tr class="{{ $mensaje->leido ? '' : 'font-weight-bold' }}">
                                <td class="mailbox-name"><a href="{{ route('mensajes.show', $mensaje->id_mensaje) }}">{{ $mensaje->emisor->nombre }}</a></td>
                                <td class="mailbox-subject">
                                    {{ Str::limit($mensaje->mensaje, 50) }}
                                </td>
                                <td class="mailbox-attachment"></td>
                                <td class="mailbox-date">{{ $mensaje->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No tienes mensajes nuevos.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
