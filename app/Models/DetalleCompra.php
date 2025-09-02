<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'detalle_compra';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idDetalleCompra';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idCompra',
        'idProducto',
        'cantidad',
        'precio',
        'precio_venta',
        'subtotal',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'cantidad' => 'integer',
        'precio' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the compra that owns the detalle.
     */
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'idCompra', 'idCompra');
    }

    /**
     * Get the articulo that owns the detalle.
     */
    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'idProducto', 'idArticulos');
    }

    /**
     * Get the producto that owns the detalle.
     * (Alias para articulo por si prefieres ese nombre)
     */
    public function producto()
    {
        return $this->belongsTo(Articulo::class, 'idProducto', 'idArticulos');
    }
}