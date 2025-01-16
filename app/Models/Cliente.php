<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cliente
 * 
 * @property int $idCliente
 * @property string|null $nombre
 * @property string|null $documento
 * @property string|null $telefono
 * @property string|null $email
 * @property Carbon|null $fecha_registro
 * @property string|null $direccion
 * @property string|null $nacionalidad
 * @property string|null $departamento
 * @property string|null $provincia
 * @property string|null $distrito
 * @property string|null $codigo_postal
 * @property bool|null $estado
 * 
 * @property Collection|Cotizacione[] $cotizaciones
 * @property Collection|Firma[] $firmas
 * @property Collection|Proyecto[] $proyectos
 * @property Collection|Ticket[] $tickets
 *
 * @package App\Models
 */
class Cliente extends Model
{
	protected $table = 'cliente';
	protected $primaryKey = 'idCliente';
	public $timestamps = false;

	protected $casts = [
		'fecha_registro' => 'datetime',
		'estado' => 'bool'
	];

	protected $fillable = [
		'nombre',
		'documento',
		'telefono',
		'email',
		'fecha_registro',
		'direccion',
		'nacionalidad',
		'departamento',
		'provincia',
		'distrito',
		'codigo_postal',
		'estado',
		'idTipoDocumento',
		'idClienteGeneral'
	];

	public function cotizaciones()
	{
		return $this->hasMany(Cotizacione::class, 'idCliente');
	}

	public function firmas()
	{
		return $this->hasMany(Firma::class, 'idCliente');
	}

	public function proyectos()
	{
		return $this->hasMany(Proyecto::class, 'idCliente');
	}

	public function tickets()
	{
		return $this->hasMany(Ticket::class, 'idCliente');
	}
	public function tiendas()
    {
        return $this->hasMany(Tienda::class, 'idCliente');
    }
	 // Definir relación con TipoDocumento
	 public function tipoDocumento()
	 {
		 return $this->belongsTo(TipoDocumento::class, 'idTipoDocumento', 'idTipoDocumento');
	 }
 
	 // Definir relación con ClienteGeneral
	 public function clienteGeneral()
	 {
		 return $this->belongsTo(ClienteGeneral::class, 'idClienteGeneral', 'idClienteGeneral');
	 }
}
