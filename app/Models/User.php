<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nombre',
        'correo',
        'contraseña',
        'rol',
        'estado',
    ];

    protected $hidden = [
        'contraseña',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'contraseña' => 'hashed',
        ];
    }

    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    // Configurar el campo de autenticación
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function username()
    {
        return 'correo';
    }

    public function materiasAsignadas()
    {
        return $this->hasMany(EstudianteMateria::class, 'id_estudiante');
    }

    public function materiasDocente()
    {
        return $this->hasMany(Materia::class, 'id_docente');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'id_usuario');
    }

    public function notificacionesNoLeidas()
    {
        return $this->hasMany(Notificacion::class, 'id_usuario')->where('leido', false);
    }

    public function solicitudesEnviadas()
    {
        return $this->hasMany(SolicitudInscripcion::class, 'id_docente');
    }

    public function solicitudesRecibidas()
    {
        return $this->hasMany(SolicitudInscripcion::class, 'id_estudiante');
    }

    public function solicitudesResueltas()
    {
        return $this->hasMany(SolicitudInscripcion::class, 'id_admin_resolutor');
    }
}
