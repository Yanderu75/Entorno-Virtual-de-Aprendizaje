<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;

    protected $table = 'calificaciones';
    protected $primaryKey = 'id_calificacion';

    protected $fillable = [
        'id_estudiante_materia',
        'lapso',
        'tipo',
        'nota',
        'porcentaje',
    ];

    public function estudianteMateria()
    {
        return $this->belongsTo(EstudianteMateria::class, 'id_estudiante_materia');
    }
}
