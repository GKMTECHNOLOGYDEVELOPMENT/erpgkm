<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Articulo
 * 
 * @property int $idArticulos
 * @property string|null $codigo
 * @property string|null $nombre
 * @property int|null $stock_total
 * @property int|null $stock_minimo
 * @property int|null $moneda_compra
 * @property int|null $moneda_venta
 * @property float|null $precio_compra
 * @property float|null $precio_venta
 * @property varbinary|null $foto
 * @property string|null $serie
 * @property float|null $peso
 * @property bool|null $mostrarWeb
 * @property bool|null $estado
 * @property int $idUnidad
 * @property int $idTipoArticulo
 * @property int $idModelo
 * 
 * @property Unidad $unidad
 * @property Tipoarticulo $tipoarticulo
 * @property Modelo $modelo
 * @property Collection|Articulosprestado[] $articulosprestados
 * @property Collection|Boveda[] $bovedas
 * @property Collection|Compra[] $compras
 * @property Collection|DetalleCotizacion[] $detalle_cotizacions
 * @property Collection|Kit[] $kits
 * @property Collection|Movimientoarticulo[] $movimientoarticulos
 * @property Collection|Ordenesarticulo[] $ordenesarticulos
 *
 * @package App\Models
 */
class Articulo extends Model
{
	protected $table = 'articulos';
	protected $primaryKey = 'idArticulos';
	public $timestamps = false;

	protected $casts = [
		'stock_total' => 'int',
		'stock_minimo' => 'int',
		'moneda_compra' => 'int',
		'moneda_venta' => 'int',
		'precio_compra' => 'float',
		'precio_venta' => 'float',
		'foto' => 'string',
		'peso' => 'float',
		'mostrarWeb' => 'bool',
		'estado' => 'bool',
		'idUnidad' => 'int',
		'idTipoArticulo' => 'int',
		'idModelo' => 'int',
		'idsubcategoria' => 'int'
	];

	protected $fillable = [
		'codigo_barras',
		'nombre',
		'stock_total',
		'stock_minimo',
		'maneja_serie',
		'moneda_compra',
		'moneda_venta',
		'precio_compra',
		'precio_venta',
		'foto',
		'sku',
		'peso',
		'mostrarWeb',
		'estado',
		'idUnidad',
		'idTipoArticulo',
		'idModelo',
		'foto_codigobarras',
		'fotosku',
		'br-codigo-repuesto',
		'pulgadas',
		'codigo_repuesto',
		'ficha_tecnica',
		'idsubcategoria',
		'garantia_fabrica',
		'unidad_tiempo_garantia',
		'idProveedor',
	];

	public function unidad()
	{
		return $this->belongsTo(Unidad::class, 'idUnidad');
	}

	public function tipoarticulo()
	{
		return $this->belongsTo(Tipoarticulo::class, 'idTipoArticulo');
	}

	public function modelo()
	{
		return $this->belongsTo(Modelo::class, 'idModelo');
	}

	public function articulosprestados()
	{
		return $this->hasMany(Articulosprestado::class, 'idArticulos');
	}

	public function bovedas()
	{
		return $this->hasMany(Boveda::class, 'idArticulos');
	}

	public function compras()
	{
		return $this->belongsToMany(Compra::class, 'compraarticulo', 'idArticulos', 'idCompra')
					->withPivot('idCompraArticulo', 'sku', 'nro');
	}

	public function detalle_cotizacions()
	{
		return $this->hasMany(DetalleCotizacion::class, 'idArticulos');
	}

	public function kits()
	{
		return $this->belongsToMany(Kit::class, 'kit_articulo', 'idArticulos', 'idKit')
					->withPivot('idkit_articulo', 'cantidad');
	}

	public function movimientoarticulos()
	{
		return $this->hasMany(Movimientoarticulo::class, 'idArticulos');
	}

	public function ordenesarticulos()
	{
		return $this->hasMany(Ordenesarticulo::class, 'idArticulos');
	}
	public function modelos()
	{
		return $this->belongsToMany(Modelo::class, 'articulo_modelo', 'articulo_id', 'modelo_id');
	}


	public function subcategoria()
	{
		return $this->belongsTo(Subcategoria::class, 'idsubcategoria');
	}

	  public function kardex()
    {
        return $this->hasMany(Kardex::class, 'idArticulo', 'idArticulos');
    }

	 public function ultimoMovimiento()
    {
        return $this->hasOne(Kardex::class, 'idArticulo', 'idArticulos')
                   ->latest('fecha');
    }

	public function proveedor()
	{
		return $this->belongsTo(Proveedore::class, 'idProveedor');
	}
	 public function monedaCompra()
    {
        return $this->belongsTo(Moneda::class, 'idMonedaCompra', 'idMoneda');
    }

protected $appends = ['stock_disponible', 'tiene_serie'];

    public function detalleAsignaciones(): HasMany
    {
        return $this->hasMany(DetalleAsignacion::class, 'articulo_id', 'idArticulos');
    }

    public function getStockDisponibleAttribute()
    {
        // Obtener la suma de cantidad de artículos asignados activos
        $asignado = $this->detalleAsignaciones()
            ->whereHas('asignacion', function ($query) {
                $query->whereIn('estado', ['activo', 'vencido']);
            })
            ->sum('cantidad');
        
        return max(0, $this->stock_total - $asignado);
    }

    public function getTieneSerieAttribute()
    {
        return $this->maneja_serie == 1;
    }

    // Método para verificar si hay stock disponible
    public function tieneStockDisponible($cantidad)
    {
        return $this->stock_disponible >= $cantidad;
    }

}
