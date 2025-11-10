<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';
    protected $primaryKey = 'id_notificacion';

    protected $fillable = [
        'id_usuario',
        'tipo',
        'titulo',
        'mensaje',
        'leido',
    ];

    protected $casts = [
        'leido' => 'boolean',
        'fecha' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function marcarComoLeida()
    {
        $this->update(['leido' => true]);
    }

    public function marcarComoNoLeida()
    {
        $this->update(['leido' => false]);
    }

    public static function crearNotificacion($idUsuario, $tipo, $titulo, $mensaje)
    {
        return self::create([
            'id_usuario' => $idUsuario,
            'tipo' => $tipo,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'leido' => false,
        ]);
    }
}

