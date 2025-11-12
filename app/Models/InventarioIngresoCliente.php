<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioIngresoCliente extends Model
{
    protected $table = 'inventario_ingresos_clientes';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'compra_id',
        'articulo_id',
        'tipo_ingreso',
        'ingreso_id',
        'cliente_general_id',
        'numero_orden',
        'cantidad',
        'codigo_solicitud',
    ];

    /**
     * Relación con la compra (tabla compra)
     */
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    /**
     * Relación con el artículo (tabla articulos)
     */
    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'articulo_id', 'idArticulos');
    }

    /**
     * Relación con el detalle de compra (tabla detalle_compra)
     */
    public function detalleCompra()
    {
        return $this->belongsTo(DetalleCompra::class, 'ingreso_id');
    }

    /**
     * Relación con el cliente general (tabla clientegeneral)
     */
    public function clienteGeneral()
    {
        return $this->belongsTo(ClienteGeneral::class, 'cliente_general_id', 'idClienteGeneral');
    }
}
