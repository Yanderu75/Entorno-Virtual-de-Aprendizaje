@extends('layouts.app')

@section('title', 'Mis Notificaciones')

@section('content')
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Mis Notificaciones</h1>
        @if($notificaciones->where('leido', false)->count() > 0)
            <form action="{{ route('notificaciones.marcar-todas') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-primary" style="width: auto; padding: 8px 20px;">Marcar todas como leídas</button>
            </form>
        @endif
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($notificaciones->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach($notificaciones as $notificacion)
                <div class="card" style="border-left: 4px solid {{ $notificacion->leido ? '#6c757d' : '#007bff' }}; padding: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div style="flex: 1;">
                            <h3 style="margin: 0 0 5px 0; color: {{ $notificacion->leido ? '#6c757d' : '#000' }};">
                                {{ $notificacion->titulo }}
                                @if(!$notificacion->leido)
                                    <span class="badge badge-primary" style="font-size: 10px;">Nueva</span>
                                @endif
                            </h3>
                            <p style="margin: 0; color: #666;">{{ $notificacion->mensaje }}</p>
                            <small style="color: #999;">{{ $notificacion->fecha->format('d/m/Y H:i') }}</small>
                        </div>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            @if(!$notificacion->leido)
                                <form action="{{ route('notificaciones.leida', $notificacion->id_notificacion) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm" style="background: #28a745; color: white; padding: 5px 10px; font-size: 12px;">Marcar leída</button>
                                </form>
                            @endif
                            <form action="{{ route('notificaciones.destroy', $notificacion->id_notificacion) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('¿Eliminar esta notificación?')">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div style="margin-top: 20px;">
                {{ $notificaciones->links() }}
            </div>
        @else
            <p class="text-center">No tienes notificaciones</p>
        @endif

        <div style="margin-top: 20px;">
            <a href="{{ route('dashboard.' . Auth::user()->rol) }}" class="btn" style="background: #6c757d; color: white; width: auto;">Volver al Dashboard</a>
        </div>
    </div>
</div>
@endsection

