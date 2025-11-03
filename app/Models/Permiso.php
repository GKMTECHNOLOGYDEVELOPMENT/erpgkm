<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $table = 'permisos';
    protected $primaryKey = 'idPermiso';

    protected $fillable = [
        'nombre',
        'descripcion',
        'modulo',
        'estado'
    ];

    public function combinaciones()
    {
        return $this->belongsToMany(CombinacionPermiso::class, 'combinacion_permisos', 'idPermiso', 'idCombinacion');
    }
}