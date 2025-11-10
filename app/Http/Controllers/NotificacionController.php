<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    // Ver todas las notificaciones del usuario
    public function index()
    {
        $notificaciones = Notificacion::where('id_usuario', Auth::id())
            ->orderBy('fecha', 'desc')
            ->paginate(20);

        return view('notificaciones.index', compact('notificaciones'));
    }

    // Marcar notificación como leída
    public function marcarLeida($id)
    {
        $notificacion = Notificacion::where('id_notificacion', $id)
            ->where('id_usuario', Auth::id())
            ->firstOrFail();

        $notificacion->marcarComoLeida();

        return response()->json(['success' => true]);
    }

    // Marcar todas como leídas
    public function marcarTodasLeidas()
    {
        Notificacion::where('id_usuario', Auth::id())
            ->where('leido', false)
            ->update(['leido' => true]);

        return back()->with('success', 'Todas las notificaciones han sido marcadas como leídas');
    }

    // Contar notificaciones no leídas (API)
    public function contarNoLeidas()
    {
        $count = Notificacion::where('id_usuario', Auth::id())
            ->where('leido', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    // Eliminar notificación
    public function destroy($id)
    {
        $notificacion = Notificacion::where('id_notificacion', $id)
            ->where('id_usuario', Auth::id())
            ->firstOrFail();

        $notificacion->delete();

        return back()->with('success', 'Notificación eliminada');
    }
}

