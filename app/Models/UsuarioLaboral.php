<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioLaboral extends Model
{
    use HasFactory;

         public $timestamps = false;
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $table = 'usuarios_laboral';
    protected $primaryKey = 'idUsuario';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'idUsuario',
        'tipoContrato',
        'fechaInicio',
        'fechaTermino',
        'horaInicioJornada',
        'horaFinJornada',
        'areaTexto',
        'cargoTexto',
    ];

    protected $casts = [
        'fechaInicio' => 'date',
        'fechaTermino' => 'date',
        'horaInicioJornada' => 'datetime:H:i',
        'horaFinJornada' => 'datetime:H:i',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

      public function tipoContrato()
    {
        return $this->belongsTo(TipoContrato::class, 'idTipoContrato', 'idTipoContrato');
    }
}