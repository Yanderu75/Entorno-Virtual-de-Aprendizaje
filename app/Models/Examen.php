<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;

    protected $table = 'examenes';

    protected $fillable = [
        'id_materia',
        'lapso',
        'numero_evaluacion',
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'publicado',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'publicado' => 'boolean',
    ];

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia', 'id_materia');
    }

    public function preguntas()
    {
        return $this->hasMany(Pregunta::class, 'id_examen');
    }

    public function intentos()
    {
        return $this->hasMany(IntentoExamen::class, 'id_examen');
    }
}
