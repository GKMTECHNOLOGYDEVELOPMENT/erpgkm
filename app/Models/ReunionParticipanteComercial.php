<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReunionParticipanteComercial extends Model
{
    use HasFactory;

    protected $table = 'reunion_participantes_comercial';
    
    protected $fillable = [
        'reunion_id',
        'usuario_id',
        'nombre'
    ];

    // Relación con la reunión
    public function reunion()
    {
        return $this->belongsTo(TaskReunion::class, 'reunion_id');
    }

    // Si tienes tabla de usuarios, agregar esta relación
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}