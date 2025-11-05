<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CombinacionPermiso extends Model
{
    use HasFactory;

    protected $table = 'combinaciones_permisos';
    protected $primaryKey = 'idCombinacion';

    protected $fillable = [
        'idRol',
        'idTipoUsuario',
        'idTipoArea',
        'nombre_combinacion',
        'descripcion',
        'estado'
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idRol');
    }

    public function tipoUsuario()
    {
        return $this->belongsTo(TipoUsuario::class, 'idTipoUsuario');
    }

    public function tipoArea()
    {
        return $this->belongsTo(TipoArea::class, 'idTipoArea');
    }

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'combinacion_permisos', 'idCombinacion', 'idPermiso');
    }

    public function getNombreCompletoAttribute()
    {
        if ($this->nombre_combinacion) {
            return $this->nombre_combinacion;
        }
        
        $rol = $this->rol ? $this->rol->nombre : 'Sin Rol';
        $tipoUsuario = $this->tipoUsuario ? $this->tipoUsuario->nombre : 'Sin Tipo';
        $tipoArea = $this->tipoArea ? $this->tipoArea->nombre : 'Sin Área';
        
        return $rol . ' - ' . $tipoUsuario . ' - ' . $tipoArea;
    }
}