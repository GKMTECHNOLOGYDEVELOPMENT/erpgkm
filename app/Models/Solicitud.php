<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Solicitud
 * 
 * @property int $idSolicitud
 * @property int|null $dias
 * @property string|null $codigo
 * @property string|null $estado
 * @property int $idTipoSolicitud
 * @property int $idTenico
 * @property int $idEncargado
 * 
 * @property Tiposolicitud $tiposolicitud
 * @property Usuario $usuario
 * @property Collection|Articulosprestado[] $articulosprestados
 * @property Collection|Firma[] $firmas
 * @property Collection|Prestamosherramienta[] $prestamosherramientas
 *
 * @package App\Models
 */
class Solicitud extends Model
{
	protected $table = 'solicitud';
	protected $primaryKey = 'idSolicitud';
	public $timestamps = false;

	protected $casts = [
		'dias' => 'int',
		'idTipoSolicitud' => 'int',
		'idTenico' => 'int',
		'idEncargado' => 'int',
		
	];

	protected $fillable = [
		'dias',
		'diasrestantes',
        'codigoSolicitud',
        'comentario',
        'fecharequerida',
        'idUsuariosoli',
        'nivelUrgencia',
		'codigo',
		'estado',
		'idTipoSolicitud',
		'idTenico',
		'idEncargado'
	];

	public function tiposolicitud()
	{
		return $this->belongsTo(Tiposolicitud::class, 'idTipoSolicitud');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'idEncargado');
	}

	public function articulosprestados()
	{
		return $this->hasMany(Articulosprestado::class, 'idSolicitud');
	}

	public function firmas()
	{
		return $this->hasMany(Firma::class, 'idSolicitud');
	}

	public function prestamosherramientas()
	{
		return $this->hasMany(Prestamosherramienta::class, 'idSolicitud');
	}

	public function solicitante()
{
    return $this->belongsTo(Usuario::class, 'idUsuariosoli', 'idUsuario');
}

public function encargado()
{
    return $this->belongsTo(Usuario::class, 'idEncargado', 'idUsuario');
}


public function articulos() {
    return $this->belongsToMany(Articulo::class, 'solicitud_articulos', 'idSolicitud', 'idArticulo')
                ->withPivot('cantidad', 'descripcion');
}


 public function solicitudArticulos()
    {
        return $this->hasMany(SolicitudArticulo::class, 'idSolicitud', 'idSolicitud');
    }

	 // Relación con el usuario solicitante
    public function usuarioSolicitante()
    {
        return $this->belongsTo(User::class, 'idUsuariosoli', 'id');
    }

	 // Relación con el área/departamento a través del usuario
    public function area()
    {
        return $this->hasOneThrough(
            TipoArea::class,
            User::class,
            'id', // Foreign key on users table...
            'idTipoArea', // Foreign key on tipo_areas table...
            'idUsuariosoli', // Local key on solicitud table...
            'id' // Local key on users table...
        );
    }


}
