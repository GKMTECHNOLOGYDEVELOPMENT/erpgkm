<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
/**
 * Class Usuario
 * 
 * @property int $idUsuario
 * @property string|null $apellidoPaterno
 * @property string|null $apellidoMaterno
 * @property string|null $Nombre
 * @property Carbon|null $fechaNacimiento
 * @property string|null $telefono
 * @property string|null $correo
 * @property string|null $usuario
 * @property string|null $clave
 * @property string|null $nacionalidad
 * @property string|null $departamento
 * @property string|null $provincia
 * @property string|null $distrito
 * @property string|null $direccion
 * @property varbinary|null $avatar
 * @property string|null $documento
 * @property float|null $sueldoPorHora
 * @property int $idSucursal
 * @property int $idTipoDocumento
 * @property int $idTipoUsuario
 * @property int $idSexo
 * @property int $idArea
 * @property int $idRol
 * 
 * @property Sucursal $sucursal
 * @property Tipodocumento $tipodocumento
 * @property Tipousuario $tipousuario
 * @property Sexo $sexo
 * @property Area $area
 * @property Rol $rol
 * @property Collection|Asistencia[] $asistencias
 * @property Collection|Conversacione[] $conversaciones
 * @property Collection|Facturacion[] $facturacions
 * @property Collection|Lectura[] $lecturas
 * @property Collection|Mensaje[] $mensajes
 * @property Collection|Prestamosherramienta[] $prestamosherramientas
 * @property Collection|Proyecto[] $proyectos
 * @property Collection|ProyectoParticipante[] $proyecto_participantes
 * @property Collection|Solicitud[] $solicituds
 * @property Collection|Solicitudesordene[] $solicitudesordenes
 * @property Collection|Solucion[] $solucions
 * @property Collection|Ticket[] $tickets
 * @property Collection|Ticketsoporte[] $ticketsoportes
 *
 * @package App\Models
 */
class Usuario extends Authenticatable
{
	use Notifiable;

	protected $table = 'usuarios';
	protected $primaryKey = 'idUsuario';
	public $timestamps = false;

	protected $casts = [
		'fechaNacimiento' => 'datetime',
		// 'avatar' => 'binary',
		'sueldoPorHora' => 'float',
		'idSucursal' => 'int',
		'idTipoDocumento' => 'int',
		'idTipoUsuario' => 'int',
		'idSexo' => 'int',
		'idArea' => 'int',
		'idRol' => 'int'
	];

	protected $fillable = [
		'apellidoPaterno',
		'apellidoMaterno',
		'Nombre',
		'fechaNacimiento',
		'telefono',
		'correo',
		'usuario',
		'clave',
		'nacionalidad',
		'departamento',
		'provincia',
		'distrito',
		'direccion',
		'avatar',
		'documento',
		'sueldoPorHora',
		'idSucursal',
		'idTipoDocumento',
		'idTipoUsuario',
		'idSexo',
		'idArea',
		'idRol'
	];

	public function sucursal()
	{
		return $this->belongsTo(Sucursal::class, 'idSucursal');
	}

	public function tipodocumento()
	{
		return $this->belongsTo(Tipodocumento::class, 'idTipoDocumento');
	}

	public function tipousuario()
	{
		return $this->belongsTo(Tipousuario::class, 'idTipoUsuario');
	}

	public function sexo()
	{
		return $this->belongsTo(Sexo::class, 'idSexo');
	}

	public function area()
	{
		return $this->belongsTo(Area::class, 'idArea');
	}

	public function rol()
	{
		return $this->belongsTo(Rol::class, 'idRol');
	}

	public function asistencias()
	{
		return $this->hasMany(Asistencia::class, 'idUsuario');
	}

	public function conversaciones()
	{
		return $this->belongsToMany(Conversacione::class, 'usuariosconversaciones', 'idUsuario', 'idConversacion')
					->withPivot('idUsuariosConversacion', 'fechaAgregado', 'rol');
	}

	public function facturacions()
	{
		return $this->hasMany(Facturacion::class, 'idUsuario');
	}

	public function lecturas()
	{
		return $this->hasMany(Lectura::class, 'idUsuario');
	}

	public function mensajes()
	{
		return $this->hasMany(Mensaje::class, 'idUsuario');
	}

	public function prestamosherramientas()
	{
		return $this->hasMany(Prestamosherramienta::class, 'idUsuario');
	}

	public function proyectos()
	{
		return $this->hasMany(Proyecto::class, 'idEncargado');
	}

	public function proyecto_participantes()
	{
		return $this->hasMany(ProyectoParticipante::class, 'idUsuario');
	}

	public function solicituds()
	{
		return $this->hasMany(Solicitud::class, 'idEncargado');
	}

	public function solicitudesordenes()
	{
		return $this->hasMany(Solicitudesordene::class, 'idUsuario');
	}

	public function solucions()
	{
		return $this->hasMany(Solucion::class, 'idUsuario');
	}

	public function tickets()
	{
		return $this->hasMany(Ticket::class, 'idUsuario');
	}

	public function ticketsoportes()
	{
		return $this->hasMany(Ticketsoporte::class, 'idUsuario');
	}

	// Relación con TicketApoyo
    public function ticketApoyos()
    {
        return $this->hasMany(TicketApoyo::class, 'idTecnico', 'idUsuario');
    }
}
