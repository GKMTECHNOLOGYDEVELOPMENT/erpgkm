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
 * @property string|null $departamento
 * @property string|null $provincia
 * @property bool|null $estado
 * @property int|null $idTipoDocumento
 * @property string|null $distrito
 * @property string|null $clientecol
 * @property string|null $esTienda
 * 
 * @property Tipodocumento|null $tipodocumento
 * @property Collection|Cotizacione[] $cotizaciones
 * @property Collection|Firma[] $firmas
 * @property Collection|Proyecto[] $proyectos
 * @property Collection|Ticket[] $tickets
 * @property Collection|Tienda[] $tiendas
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
		'estado' => 'bool',
		'idTipoDocumento' => 'int'
	];

	protected $fillable = [
		'nombre',
		'documento',
		'telefono',
		'email',
		'fecha_registro',
		'direccion',
		'departamento',
		'provincia',
		'estado',
		'idTipoDocumento',
		'distrito',
		'clientecol',
		'esTienda',
		'idservicio',
		'idtipoarea'
	];

	public function tipodocumento()
	{
		return $this->belongsTo(Tipodocumento::class, 'idTipoDocumento');
	}

	public function cotizaciones()
	{
		return $this->hasMany(Cotizacione::class, 'idCliente');
	}

	 public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'idservicio', 'idServicios');
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
	 // Relación muchos a muchos con ClienteGeneral
	 public function clienteGenerales()
	 {
		 return $this->belongsToMany(ClienteGeneral::class, 'cliente_clientegeneral', 'idCliente', 'idClienteGeneral');
	 }

	 	// NUEVA RELACIÓN: Muchos a muchos con Contactosform
	public function contactos()
	{
		return $this->belongsToMany(Contactosform::class, 'contacto_cliente', 'cliente_id', 'contacto_id', 'idCliente', 'id');
	}
}
