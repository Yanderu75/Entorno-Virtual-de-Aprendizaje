<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Materia;
use App\Models\User;

class Evento extends Model
{
    protected $table = 'eventos';
    protected $primaryKey = 'id_evento';

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'tipo',
        'id_materia',
        'id_usuario',
    ];

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
