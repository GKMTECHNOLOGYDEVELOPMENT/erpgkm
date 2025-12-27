<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoUsuario extends Model
{
    protected $table = 'documentos_usuario';
    protected $primaryKey = 'idDocumento';
    public $timestamps = true;

    protected $fillable = [
        'idUsuario',
        'tipo_documento',
        'nombre_archivo',
        'ruta_archivo',
        'mime_type',
        'tamano'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}