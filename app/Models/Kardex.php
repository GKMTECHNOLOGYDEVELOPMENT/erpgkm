<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kardex extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kardex';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha',
        'idArticulo',
        'unidades_entrada',
        'costo_unitario_entrada',
        'unidades_salida',
        'costo_unitario_salida',
        'inventario_inicial',
        'inventario_actual',
        'costo_inventario'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fecha' => 'date',
        'unidades_entrada' => 'integer',
        'costo_unitario_entrada' => 'decimal:2',
        'unidades_salida' => 'integer',
        'costo_unitario_salida' => 'decimal:2',
        'inventario_inicial' => 'integer',
        'inventario_actual' => 'integer',
        'costo_inventario' => 'decimal:2',
    ];

    /**
     * Relación con el modelo Articulo
     */
    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'idArticulo', 'idArticulos');
    }

    /**
     * Scope para filtrar por artículo
     */
    public function scopePorArticulo($query, $idArticulo)
    {
        return $query->where('idArticulo', $idArticulo);
    }

    /**
     * Scope para filtrar por rango de fechas
     */
    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
    }

    /**
     * Accesor para el costo total de entrada
     */
    public function getCostoTotalEntradaAttribute()
    {
        return $this->unidades_entrada * $this->costo_unitario_entrada;
    }

    /**
     * Accesor para el costo total de salida
     */
    public function getCostoTotalSalidaAttribute()
    {
        return $this->unidades_salida * $this->costo_unitario_salida;
    }

    /**
     * Accesor para el movimiento (entrada/salida)
     */
    public function getTipoMovimientoAttribute()
    {
        return $this->unidades_entrada > 0 ? 'Entrada' : 'Salida';
    }

    /**
     * Accesor para la cantidad neta
     */
    public function getCantidadNetaAttribute()
    {
        return $this->unidades_entrada - $this->unidades_salida;
    }
}