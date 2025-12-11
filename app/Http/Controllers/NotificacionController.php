<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    public function index()
    {
        $notificaciones = Notificacion::where('id_usuario', Auth::id())
            ->orderBy('fecha', 'desc')
            ->paginate(20);

        return view('notificaciones.index', compact('notificaciones'));
    }

    public function marcarLeida($id)
    {
        $notificacion = Notificacion::where('id_notificacion', $id)
            ->where('id_usuario', Auth::id())
            ->firstOrFail();

        $notificacion->marcarComoLeida();

        return response()->json(['success' => true]);
    }

    public function marcarTodasLeidas()
    {
        Notificacion::where('id_usuario', Auth::id())
            ->where('leido', false)
            ->update(['leido' => true]);

        return back()->with('success', 'Todas las notificaciones han sido marcadas como leÃ­das');
    }

    public function contarNoLeidas()
    {
        $count = Notificacion::where('id_usuario', Auth::id())
            ->where('leido', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function destroy($id)
    {
        $notificacion = Notificacion::where('id_notificacion', $id)
            ->where('id_usuario', Auth::id())
            ->firstOrFail();

        $notificacion->delete();

        return back()->with('success', 'NotificaciÃ³n eliminada');
    }
}

