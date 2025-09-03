<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevolucionCompra extends Model
{
    use HasFactory;

    protected $table = 'devoluciones_compra';
    protected $primaryKey = 'idDevolucionCompra';

    protected $fillable = [
        'idCompra',
        'idProducto',
        'idUsuario',
        'cantidad',
        'precio_unitario',
        'total_devolucion',
        'motivo',
        'estado'
    ];

    protected $casts = [
        'fecha_devolucion' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relaciones
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'idCompra', 'idCompra');
    }

    public function producto()
    {
        return $this->belongsTo(Articulo::class, 'idProducto', 'idArticulos');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}