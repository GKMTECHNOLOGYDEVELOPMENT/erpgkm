<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioDocumentoArchivo extends Model
{
    use HasFactory;

    protected $table = 'usuarios_documentos_archivos';
    protected $primaryKey = 'idArchivo';

    protected $fillable = [
        'idUsuario',
        'tipoDocumento',
        'nombreArchivo',
        'mimeType',
        'contenido',
    ];

    protected $casts = [
        'createdAt' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    public function check()
    {
        return $this->belongsTo(UsuarioDocumentoCheck::class, 'idUsuario', 'idUsuario');
    }
}