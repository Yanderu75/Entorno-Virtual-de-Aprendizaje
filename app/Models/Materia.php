<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $table = 'materias';
    protected $primaryKey = 'id_materia';

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_docente',
        'periodo',
        'horario',
        'cupo_maximo',
    ];

    public function docente()
    {
        return $this->belongsTo(User::class, 'id_docente');
    }

    public function estudiantes()
    {
        return $this->hasMany(EstudianteMateria::class, 'id_materia');
    }

    public function solicitudes()
    {
        return $this->hasMany(SolicitudInscripcion::class, 'id_materia');
    }

    public function tieneCuposDisponibles()
    {
        if ($this->cupo_maximo === null) {
            return true;
        }
        $asignados = $this->estudiantes()->count();
        return $asignados < $this->cupo_maximo;
    }

    public function cuposDisponibles()
    {
        if ($this->cupo_maximo === null) {
            return null;
        }
        return max(0, $this->cupo_maximo - $this->estudiantes()->count());
    }
}
