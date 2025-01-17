<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tienda
 * 
 * @property int $idTienda
 * @property string|null $nombre
 * 
 * @property Collection|Cliente[] $subsidiarios
 *
 * @package App\Models
 */
class Tienda extends Model
{
	protected $table = 'tienda';
	protected $primaryKey = 'idTienda';
	public $timestamps = false;

	protected $fillable = [
        'ruc', 'nombre', 'celular', 'email', 'direccion', 'referencia', 'lat', 'lng', 'idCliente', 'provincia', 'distrito','departamento',

    ];

	// Relación uno a muchos (Un cliente tiene muchas tiendas)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente', 'idCliente');
    }
}
