<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MensajePrivado extends Model
{
    use HasFactory;

    protected $table = 'mensajes_privados';
    protected $primaryKey = 'id_mensaje';

    protected $fillable = [
        'id_emisor',
        'id_receptor',
        'mensaje',
        'leido',
    ];

    public function emisor()
    {
        return $this->belongsTo(User::class, 'id_emisor');
    }

    public function receptor()
    {
        return $this->belongsTo(User::class, 'id_receptor');
    }
}
