<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactoFinal extends Model
{
    use HasFactory;

    protected $table = 'contactofinal';
    protected $primaryKey = 'idContactoFinal';

    protected $fillable = [
        'nombre_completo',
        'idTipoDocumento',
        'numero_documento',
        'correo',
        'telefono',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    /**
     * Relación con TipoDocumento
     */
    public function tipoDocumento()
    {
        return $this->belongsTo(Tipodocumento::class, 'idTipoDocumento', 'idTipoDocumento');
    }
}