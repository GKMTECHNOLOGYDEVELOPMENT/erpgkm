<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotizacionProducto extends Model
{
    use HasFactory;

    protected $table = 'cotizacion_productos';

    protected $fillable = [
        'cotizacion_id',
        'articulo_id',
        'descripcion',
        'codigo_repuesto',
        'precio_unitario',
        'cantidad',
        'subtotal'
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relaciones
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'cotizacion_id');
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'articulo_id');
    }
}