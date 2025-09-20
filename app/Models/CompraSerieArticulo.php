<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraSerieArticulo extends Model
{
    use HasFactory;

    protected $table = 'compra_serie_articulos';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'compra_id',
        'detalle_compra_id',
        'articulo_id',
        'serie',
        'estado'
    ];
    
    protected $casts = [
        'fecha_ingreso' => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];
    
    // Relación con la compra
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id', 'idCompra');
    }
    
    // Relación con el detalle de compra
    public function detalleCompra()
    {
        return $this->belongsTo(DetalleCompra::class, 'detalle_compra_id', 'idDetalleCompra');
    }
    
    // Relación con el artículo
    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'articulo_id', 'idArticulos');
    }
    
    // Scope para buscar por serie
    public function scopePorSerie($query, $serie)
    {
        return $query->where('serie', $serie);
    }
    
    // Scope para filtrar por estado
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }
    
    // Scope para artículos de una compra específica
    public function scopePorCompra($query, $compraId)
    {
        return $query->where('compra_id', $compraId);
    }
    
    // Scope para series de un detalle de compra específico
    public function scopePorDetalleCompra($query, $detalleCompraId)
    {
        return $query->where('detalle_compra_id', $detalleCompraId);
    }
}