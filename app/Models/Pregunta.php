<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $table = 'preguntas';

    protected $fillable = [
        'id_examen',
        'enunciado',
        'tipo',
        'puntaje',
        'opciones',
        'respuesta_correcta',
    ];

    protected $casts = [
        'opciones' => 'array',
        'puntaje' => 'float',
    ];

    public function examen()
    {
        return $this->belongsTo(Examen::class, 'id_examen');
    }

    public function respuestas()
    {
        return $this->hasMany(RespuestaExamen::class, 'id_pregunta');
    }
}
