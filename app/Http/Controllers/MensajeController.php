<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MensajePrivado;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MensajeController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $mensajesRecibidos = MensajePrivado::where('id_receptor', $userId)
                                           ->with('emisor')
                                           ->orderBy('created_at', 'desc')
                                           ->get();


        return view('mensajes.index', compact('mensajesRecibidos'));
    }

    public function sent()
    {
        $userId = Auth::id();
        $mensajesEnviados = MensajePrivado::where('id_emisor', $userId)
                                           ->with('receptor')
                                           ->orderBy('created_at', 'desc')
                                           ->get();

        return view('mensajes.sent', compact('mensajesEnviados'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $usuarios = [];

        if ($user->rol == 'estudiante') {
            $usuarios = User::where('rol', 'docente')->orWhere('rol', 'admin')->get();
        } elseif ($user->rol == 'docente') {
            $usuarios = User::where('rol', 'estudiante')->orWhere('rol', 'admin')->get();
        } else {
            $usuarios = User::where('id_usuario', '!=', $user->id_usuario)->get();
        }

        $replyUser = null;
        $originalMessage = null;
        $body = '';

        if ($request->has('reply_to')) {
            $replyUser = User::find($request->reply_to);
        }

        if ($request->has('ref')) {
            $originalMessage = MensajePrivado::find($request->ref);
            if ($originalMessage) {
                $body = "\n\n\n--- En respuesta a ---\n" .
                        "De: " . $originalMessage->emisor->nombre . "\n" .
                        "Fecha: " . $originalMessage->created_at . "\n" .
                        "> " . str_replace("\n", "\n> ", $originalMessage->mensaje);
            }
        }

        return view('mensajes.create', compact('usuarios', 'replyUser', 'body'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'destinatario' => 'required|exists:usuarios,id_usuario',
            'mensaje' => 'required|string|max:2000',
        ]);

        $mensaje = MensajePrivado::create([
            'id_emisor' => Auth::id(),
            'id_receptor' => $request->destinatario,
            'mensaje' => $request->mensaje,
            'leido' => false,
        ]);

        \App\Models\Notificacion::crearNotificacion(
            $request->destinatario,
            'mensaje',
            'Nuevo Mensaje',
            'Has recibido un mensaje de ' . Auth::user()->nombre
        );

        return redirect()->route('mensajes.index')->with('success', 'Mensaje enviado correctamente.');
    }

    public function show($id)
    {
        $mensaje = MensajePrivado::with(['emisor', 'receptor'])->findOrFail($id);

        if (Auth::id() != $mensaje->id_emisor && Auth::id() != $mensaje->id_receptor) {
            abort(403);
        }

        if (Auth::id() == $mensaje->id_receptor && !$mensaje->leido) {
            $mensaje->leido = true;
            $mensaje->save();
        }

        return view('mensajes.show', compact('mensaje'));
    }
}
