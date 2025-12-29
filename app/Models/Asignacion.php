<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asignacion extends Model
{
    protected $table = 'asignaciones';
    
    protected $fillable = [
        'idUsuario',
        'fecha_asignacion',
        'fecha_devolucion',
        'observaciones',
        'estado'
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
        'fecha_devolucion' => 'date',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleAsignacion::class, 'asignacion_id');
    }

    public function articulos()
    {
        return $this->belongsToMany(Articulo::class, 'detalle_asignaciones', 'asignacion_id', 'articulo_id')
                    ->withPivot('cantidad', 'numero_serie', 'estado_articulo')
                    ->withTimestamps();
    }
}