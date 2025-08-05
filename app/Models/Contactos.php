<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contactos extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'nombre_completo',
        'cargo',
        'correo_electronico',
        'telefono_whatsapp',
        'nivel_decision_id'
    ];

    public function nivelDecision()
    {
        return $this->belongsTo(NivelDecision::class, 'nivel_decision_id');
    }
}
