<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudAlmacenDetalle extends Model
{
    use HasFactory;

    protected $table = 'solicitud_almacen_detalle';
    protected $primaryKey = 'idSolicitudAlmacenDetalle';
    
    protected $fillable = [
        'idSolicitudAlmacen',
        'idArticulo',
        'descripcion_producto',
        'cantidad',
        'unidad',
        'precio_unitario_estimado',
        'total_producto',
        'categoria',
        'codigo_producto',
        'marca',
        'especificaciones_tecnicas',
        'proveedor_sugerido',
        'justificacion_producto',
        'estado',
        'cantidad_aprobada',
        'observaciones_detalle'
    ];

    protected $casts = [
        'precio_unitario_estimado' => 'decimal:2',
        'total_producto' => 'decimal:2'
    ];

    public $timestamps = true;

    // Relaciones
    public function solicitud()
    {
        return $this->belongsTo(SolicitudAlmacen::class, 'idSolicitudAlmacen');
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'idArticulo');
    }
}