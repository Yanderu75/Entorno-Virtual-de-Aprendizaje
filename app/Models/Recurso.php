<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
    use HasFactory;

    protected $table = 'recursos';
    protected $primaryKey = 'id_recurso';

    protected $fillable = [
        'id_materia',
        'titulo',
        'tipo',
        'ruta',
    ];

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia');
    }
}
