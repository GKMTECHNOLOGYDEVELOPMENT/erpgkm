<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleAsignacion extends Model
{
    protected $table = 'detalle_asignaciones';
    
    protected $fillable = [
        'asignacion_id',
        'articulo_id',
        'cantidad',
        'numero_serie',
        'estado_articulo'
    ];

    public function asignacion(): BelongsTo
    {
        return $this->belongsTo(Asignacion::class, 'asignacion_id');
    }

    public function articulo(): BelongsTo
    {
        return $this->belongsTo(Articulo::class, 'articulo_id', 'idArticulos');
    }
}