<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioFichaGeneral extends Model
{
    use HasFactory;

         public $timestamps = false;
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $table = 'usuarios_ficha_general';
    protected $primaryKey = 'idUsuario';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'idUsuario',
        'nacimientoDepartamento',
        'nacimientoProvincia',
        'nacimientoDistrito',
        'estadoCivil',
        'telefonoFijo',
        'domicilioVia',
        'domicilioMzLt',
        'domicilioUrb',
        'domicilioDepartamento',
        'domicilioProvincia',
        'domicilioDistrito',
        'entidadBancaria',
        'tipoCuenta',
        'moneda',
        'numeroCuenta',
        'numeroCCI',
        'seguroSalud',
        'sistemaPensiones',
        'afpCompania',
    ];

    protected $casts = [
        'estadoCivil' => 'integer',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}