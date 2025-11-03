<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CombinacionPermisoDetalle extends Model
{
    use HasFactory;

    protected $table = 'combinacion_permisos';
    protected $primaryKey = 'idCombinacionPermiso';

    protected $fillable = [
        'idCombinacion',
        'idPermiso',
        'estado'
    ];

    public function combinacion()
    {
        return $this->belongsTo(CombinacionPermiso::class, 'idCombinacion');
    }

    public function permiso()
    {
        return $this->belongsTo(Permiso::class, 'idPermiso');
    }
}