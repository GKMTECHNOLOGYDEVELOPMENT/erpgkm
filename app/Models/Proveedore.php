<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Proveedore
 * 
 * @property int $idProveedor
 * @property string|null $nombre
 * @property bool|null $estado
 * @property string|null $pais
 * @property string|null $departamento
 * @property string|null $provincia
 * @property string|null $distrito
 * @property string|null $direccion
 * @property string|null $codigoPostal
 * @property string|null $telefono
 * @property string|null $email
 * @property string|null $numeroDocumento
 * @property int $idCompra
 * @property int $idSucursal
 * @property int $idTipoDocumento
 * 
 * @property Compra $compra
 * @property Sucursal $sucursal
 * @property Tipodocumento $tipodocumento
 *
 * @package App\Models
 */
class Proveedore extends Model
{
	protected $table = 'proveedores';
	protected $primaryKey = 'idProveedor';
	public $timestamps = false;

	protected $casts = [
		'estado' => 'bool',
		'idCompra' => 'int',
		'idSucursal' => 'int',
		'idTipoDocumento' => 'int'
	];

	protected $fillable = [
		'nombre',
		'estado',
		'pais',
		'departamento',
		'provincia',
		'distrito',
		'direccion',
		'codigoPostal',
		'telefono',
		'email',
		'numeroDocumento',
		'idCompra',
		'idSucursal',
		'idTipoDocumento'
	];

	public function compra()
	{
		return $this->belongsTo(Compra::class, 'idCompra');
	}

	public function sucursal()
	{
		return $this->belongsTo(Sucursal::class, 'idSucursal');
	}

	public function tipodocumento()
	{
		return $this->belongsTo(Tipodocumento::class, 'idTipoDocumento');
	}
}
