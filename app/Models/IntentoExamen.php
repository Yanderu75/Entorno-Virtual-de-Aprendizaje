<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntentoExamen extends Model
{
    use HasFactory;

    protected $table = 'intentos_examen';

    protected $fillable = [
        'id_examen',
        'id_estudiante',
        'nota_final',
        'correccion_docente',
        'fecha_entregado',
    ];

    protected $casts = [
        'fecha_entregado' => 'datetime',
        'correccion_docente' => 'boolean',
        'nota_final' => 'float',
    ];

    public function examen()
    {
        return $this->belongsTo(Examen::class, 'id_examen');
    }

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'id_estudiante', 'id_usuario');
    }

    public function respuestas()
    {
        return $this->hasMany(RespuestaExamen::class, 'id_intento');
    }
}
