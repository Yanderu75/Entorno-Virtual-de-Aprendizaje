<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespuestaExamen extends Model
{
    use HasFactory;

    protected $table = 'respuestas_examen';

    protected $fillable = [
        'id_intento',
        'id_pregunta',
        'respuesta_texto',
        'puntaje_obtenido',
    ];

    protected $casts = [
        'puntaje_obtenido' => 'float',
    ];

    public function intento()
    {
        return $this->belongsTo(IntentoExamen::class, 'id_intento');
    }

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'id_pregunta');
    }
}
