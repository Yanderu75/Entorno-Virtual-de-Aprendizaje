<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstudianteMateria extends Model
{
    use HasFactory;

    protected $table = 'estudiantes_materias';
    protected $primaryKey = 'id_estudiante_materia';

    protected $fillable = [
        'id_estudiante',
        'id_materia',
        'promedio',
        'avance',
    ];

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'id_estudiante');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia');
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'id_estudiante_materia');
    }
}
