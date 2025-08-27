<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Firma
 * 
 * @property int $idFirmas
 * @property varbinary|null $firma_tecnico
 * @property varbinary|null $firma_cliente
 * @property int|null $idTickets
 * @property int $idCliente
 * @property int $idSolicitudesOrdenes
 * @property int $idSolicitud
 * 
 * @property Ticket|null $ticket
 * @property Cliente $cliente
 * @property Solicitudesordene $solicitudesordene
 * @property Solicitud $solicitud
 *
 * @package App\Models
 */
class Firma extends Model
{
	protected $table = 'firmas';
	protected $primaryKey = 'idFirmas';
	public $timestamps = false;

	protected $casts = [
		'firma_tecnico' => 'varbinary',
		'firma_cliente' => 'varbinary',
		'idTickets' => 'int',
		'idCliente' => 'int',
		'idSolicitudesOrdenes' => 'int',
		'idSolicitud' => 'int'
	];

	protected $fillable = [
		'firma_tecnico',
		'firma_cliente',
		'idTickets',
		'idCliente',
		'idSolicitudesOrdenes',
		'idSolicitud',
		'nombreencargado',
		'tipodocumento',
		'documento',
		'cargo'
	];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class, 'idTickets');
	}

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'idCliente');
	}

	public function solicitudesordene()
	{
		return $this->belongsTo(Solicitudesordene::class, 'idSolicitudesOrdenes');
	}

	public function solicitud()
	{
		return $this->belongsTo(Solicitud::class, 'idSolicitud');
	}
}
