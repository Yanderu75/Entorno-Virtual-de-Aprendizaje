@extends('layouts.app')

@section('title', 'Detalles del Usuario')

@section('content_header')
    <h1>Perfil de Usuario</h1>
@stop

@section('main_content_body')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="https://ui-avatars.com/api/?name={{ urlencode($usuario->nombre) }}&background=random" alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{ $usuario->nombre }}</h3>
                <p class="text-muted text-center">{{ ucfirst($usuario->rol) }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right">{{ $usuario->correo }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Estado</b> <a class="float-right">{{ ucfirst($usuario->estado ?? 'Activo') }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Miembro desde</b> <a class="float-right">{{ $usuario->created_at->format('d/m/Y') }}</a>
                    </li>
                </ul>

                <a href="{{ route('users.edit', $usuario->id_usuario) }}" class="btn btn-primary btn-block"><b>Editar</b></a>
                <a href="{{ route('users.index') }}" class="btn btn-default btn-block"><b>Volver</b></a>
            </div>
        </div>
    </div>
</div>
@stop
