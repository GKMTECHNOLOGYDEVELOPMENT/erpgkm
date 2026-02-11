<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioDocumentoCheck extends Model
{
    use HasFactory;

         public $timestamps = false;
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $table = 'usuarios_documentos_check';
    protected $primaryKey = 'idUsuario';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'idUsuario',
        'cv',
        'dniVigente',
        'carnetVacunacion',
        'antecedentesPoliciales',
        'trabajosAnteriores',
        'estudiosTecnicosOtros',
        'declaracionJuradaDomicilio',
        'partidaMatrimonioOtros',
        'dniHijos',
        'huella',
    ];

    protected $casts = [
        'cv' => 'boolean',
        'dniVigente' => 'boolean',
        'carnetVacunacion' => 'boolean',
        'antecedentesPoliciales' => 'boolean',
        'trabajosAnteriores' => 'boolean',
        'estudiosTecnicosOtros' => 'boolean',
        'declaracionJuradaDomicilio' => 'boolean',
        'partidaMatrimonioOtros' => 'boolean',
        'dniHijos' => 'boolean',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    public function archivos()
    {
        return $this->hasMany(UsuarioDocumentoArchivo::class, 'idUsuario', 'idUsuario');
    }
}