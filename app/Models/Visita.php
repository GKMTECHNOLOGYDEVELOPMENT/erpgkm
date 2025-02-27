<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Visita
 * 
 * @property int $idVisitas
 * @property string|null $nombre
 * @property Carbon|null $fecha_programada
 * @property Carbon|null $fecha_asignada
 * @property Carbon|null $fechas_desplazamiento
 * @property Carbon|null $fecha_llegada
 * @property Carbon|null $fecha_inicio
 * @property Carbon|null $fecha_final
 * @property bool|null $estado
 * @property int|null $idTickets
 * 
 * @property Ticket|null $ticket
 * @property Collection|AccionesVisita[] $acciones_visitas
 * @property AnexosVisita $anexos_visita
 *
 * @package App\Models
 */
class Visita extends Model
{
	protected $table = 'visitas';
	protected $primaryKey = 'idVisitas';
	public $timestamps = false;

	protected $casts = [
		'fecha_programada' => 'datetime',
		'fecha_asignada' => 'datetime',
		'fechas_desplazamiento' => 'datetime',
		'fecha_llegada' => 'datetime',
		'fecha_inicio' => 'datetime',
		'fecha_final' => 'datetime',
		'fecha_inicio_hora' => 'datetime',
		'fecha_final_hora' => 'datetime',
		'estado' => 'bool',
		'idTickets' => 'int'
	];

	protected $fillable = [
		'nombre',
		'fecha_programada',
		'fecha_asignada',
		'fechas_desplazamiento',
		'fecha_llegada',
		'fecha_inicio',
		'fecha_final',
		'estado',
		'idTickets',
		'fecha_inicio_hora',   // Añadimos fecha_inicio_hora
		'fecha_final_hora',
		'necesita_apoyo',
		'idTecnico', // Añadimos fecha_final_hora
	];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class, 'idTickets');
	}

	public function acciones_visitas()
	{
		return $this->hasMany(AccionesVisita::class, 'idVisitas');
	}

	// Relación con la tabla anexos_visitas (Trae todas las imágenes de anexos de una visita)
	public function anexos_visitas()
	{
		return $this->hasMany(AnexosVisita::class, 'idVisitas', 'idVisitas');
	}

	// // Relación con TicketApoyo
	// public function ticketApoyo()
	// {
	//     return $this->hasOne(TicketApoyo::class, 'idVisita', 'idVisitas');
	// }
	// En el modelo de Visita (Visita.php)
	// Relación con la tabla fotostickest (Trae todas las imágenes de fotos del ticket en la visita)
	public function fotostickest()
	{
		return $this->hasMany(Fotostickest::class, 'idVisitas', 'idVisitas');
	}

	public function ticketApoyos()
	{
		return $this->hasMany(TicketApoyo::class, 'idVisita');
	}

	// En el modelo de TicketApoyo (TicketApoyo.php)
	public function visita()
	{
		return $this->belongsTo(Visita::class, 'idVisita');
	}

	// Relación con Usuario (Técnico)
	public function tecnico()
	{
		return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
	}


	// Relación con el modelo CondicionesTicket
	public function condicionesTickets()
	{
		return $this->hasMany(CondicionesTicket::class, 'idVisitas', 'idVisitas');
	}
}
