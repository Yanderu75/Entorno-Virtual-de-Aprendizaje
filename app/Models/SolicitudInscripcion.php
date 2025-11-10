<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudInscripcion extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_inscripcion';
    protected $primaryKey = 'id_solicitud';

    protected $fillable = [
        'id_docente',
        'id_estudiante',
        'id_materia',
        'estado',
        'motivo_rechazo',
        'fecha_resolucion',
        'id_admin_resolutor',
    ];

    protected $casts = [
        'fecha_solicitud' => 'datetime',
        'fecha_resolucion' => 'datetime',
    ];

    public function docente()
    {
        return $this->belongsTo(User::class, 'id_docente');
    }

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'id_estudiante');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia');
    }

    public function adminResolutor()
    {
        return $this->belongsTo(User::class, 'id_admin_resolutor');
    }

    public function estaPendiente()
    {
        return $this->estado === 'pendiente';
    }

    public function estaAprobada()
    {
        return $this->estado === 'aprobada';
    }

    public function estaRechazada()
    {
        return $this->estado === 'rechazada';
    }
}

