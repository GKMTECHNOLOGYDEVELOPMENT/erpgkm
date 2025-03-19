<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudEntrega extends Model
{
    use HasFactory;

    // Especificamos la tabla si el nombre no sigue la convención
    protected $table = 'solicitudentrega';
	public $timestamps = false;

    // Especificamos los campos que son asignables masivamente
    protected $fillable = [
        'idTickets',
        'idVisitas',
        'idUsuario',
        'comentario',
        'estado',
    ];
}
