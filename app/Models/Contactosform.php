<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contactosform extends Model
{
    use HasFactory;

    protected $table = 'contactosform';

    protected $fillable = [
        'idSeguimiento',
        'tipo_documento_id',
        'numero_documento',
        'nombre_completo',
        'cargo',
        'correo_electronico',
        'telefono_whatsapp',
        'nivel_decision_id',
    ];

    // Relaciones

    public function seguimiento()
    {
        return $this->belongsTo(Seguimiento::class, 'idSeguimiento', 'idSeguimiento');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id', 'idTipoDocumento');
    }

    public function nivelDecision()
    {
        return $this->belongsTo(NivelDecision::class, 'nivel_decision_id');
    }

        // NUEVA RELACIÓN: Muchos a muchos con Cliente
    public function clientes()
    {
        return $this->belongsToMany(Cliente::class, 'contacto_cliente', 'contacto_id', 'cliente_id', 'id', 'idCliente');
    }
}
