<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCompraDetalle extends Model
{
    use HasFactory;

    protected $table = 'solicitud_compra_detalle';
    protected $primaryKey = 'idSolicitudCompraDetalle';

    protected $fillable = [
        'idSolicitudCompra',
        'idSolicitudAlmacenDetalle',
        'idArticulo',
        'descripcion_producto',
        'categoria',
        'cantidad',
        'unidad',
        'precio_unitario_estimado',
        'total_producto',
        'codigo_producto',
        'marca',
        'especificaciones_tecnicas',
        'proveedor_sugerido',
        'justificacion_producto',
        'estado',
        'cantidad_aprobada',
        'observaciones_detalle',
        'idMonedas' // Agregar este campo
    ];

    protected $casts = [
        'precio_unitario_estimado' => 'decimal:2',
        'total_producto' => 'decimal:2'
    ];

    // Relaciones
    public function solicitudCompra()
    {
        return $this->belongsTo(SolicitudCompra::class, 'idSolicitudCompra');
    }

    public function solicitudAlmacenDetalle()
    {
        return $this->belongsTo(SolicitudAlmacenDetalle::class, 'idSolicitudAlmacenDetalle');
    }

    // Calcular total del producto
    public function calcularTotal()
    {
        $this->total_producto = $this->cantidad * $this->precio_unitario_estimado;
        return $this;
    }

    
    // Relación con moneda
    public function moneda()
    {
        return $this->belongsTo(Moneda::class, 'idMonedas');
    }
}