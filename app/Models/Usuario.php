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
use Illuminate\Database\Eloquent\Relations\HasMany;

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
	    'correo_personal', // Nuevo campo
		'clave',
		'token',
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
		'idRol',
		'firma',
		'estadocivil'
	];

	public function sucursal()
	{
		return $this->belongsTo(Sucursal::class, 'idSucursal');
	}

	public function tipoDocumento()
	{
		return $this->belongsTo(Tipodocumento::class, 'idTipoDocumento', 'idTipoDocumento');
	}



	public function tipoUsuario()
	{
		return $this->belongsTo(Tipousuario::class, 'idTipoUsuario');
	}


	public function sexo()
	{
		return $this->belongsTo(Sexo::class, 'idSexo');
	}

	public function tipoArea()
	{
		return $this->belongsTo(Tipoarea::class, 'idTipoArea', 'idTipoArea');
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


	

public function asignaciones(): HasMany
    {
        return $this->hasMany(Asignacion::class, 'idUsuario', 'idUsuario');
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
	public function vehiculo()
	{
		return $this->hasOne(Vehiculo::class, 'idUsuario', 'idUsuario');
	}
	public function etiquetas()
	{
		return $this->hasMany(Etiqueta::class, 'user_id', 'idUsuario');
	}
	public function actividades(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(Actividad::class, 'user_id', 'idUsuario');
	}

	public function notes()
    {
    return $this->hasMany(Note::class, 'user_id', 'idUsuario');
    }

    /**
     * Obtener los tags del usuario
     */
    public function tags()
    {
    return $this->hasMany(Tag::class, 'user_id', 'idUsuario');
    }


	   // Relación con HarvestRetiro
    public function harvestRetiros()
    {
        return $this->hasMany(HarvestRetiro::class, 'id_responsable', 'idUsuario');
    }

    // Accesor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return $this->Nombre . ' ' . $this->apellidoPaterno . ' ' . $this->apellidoMaterno;
    }





	// app/Models/Usuario.php
// Agrega estos métodos a tu clase Usuario existente

public function combinacionPermisos()
{
    return $this->hasOne(CombinacionPermiso::class, 'idCombinacion', 'idCombinacion')
        ->where('estado', 1);
}

public function obtenerCombinacionPermisos()
{
    // Buscar la combinación que coincide con rol, tipoUsuario y tipoArea del usuario
    return CombinacionPermiso::where('idRol', $this->idRol)
        ->where('idTipoUsuario', $this->idTipoUsuario)
        ->where('idTipoArea', $this->idTipoArea)
        ->where('estado', 1)
        ->first();
}

public function obtenerPermisos()
{
    $combinacion = $this->obtenerCombinacionPermisos();
    
    if ($combinacion) {
        // Obtener permisos activos a través de la tabla pivote
        return $combinacion->permisos()
            ->where('permisos.estado', 1)
            ->wherePivot('estado', 1)
            ->get();
    }
    
    return collect();
}

public function tienePermiso($nombrePermiso)
{
    $combinacion = $this->obtenerCombinacionPermisos();
    
    if (!$combinacion) {
        return false;
    }
    
    // Consulta directa para mejor performance
    return Permiso::where('nombre', $nombrePermiso)
        ->where('estado', 1)
        ->whereHas('combinaciones', function($query) use ($combinacion) {
            $query->where('combinaciones_permisos.idCombinacion', $combinacion->idCombinacion)
                ->where('combinacion_permisos.estado', 1);
        })
        ->exists();
}
// app/Models/Usuario.php
// Actualiza el método getDashboardsDisponibles()

public function getDashboardsDisponibles()
{
    $dashboards = [];
    
    // Mapeo de permisos a dashboards usando tus rutas reales
    $mapDashboards = [
        'VER DASHBOARD' => [
            'route' => 'index',
            'url' => '/', // URL directa
            'nombre' => 'Dashboard Principal',
            'prioridad' => 1,
            'icon' => 'fas fa-home'
        ],
        'VER DASHBOARD ADMINISTRACION' => [
            'route' => 'index',
            'url' => '/', // Misma URL que principal
            'nombre' => 'Dashboard Administración',
            'prioridad' => 2,
            'icon' => 'fas fa-cogs'
        ],
        'VER DASHBOARD ALMACEN' => [
            'route' => 'almacen',
            'url' => '/almacen',
            'nombre' => 'Dashboard Almacén',
            'prioridad' => 3,
            'icon' => 'fas fa-warehouse'
        ],
        'VER DASHBOARD TICKETS' => [
            'route' => 'tickets',
            'url' => '/tickets',
            'nombre' => 'Dashboard Tickets',
            'prioridad' => 4,
            'icon' => 'fas fa-ticket-alt'
        ],
        'VER DASHBOARD COMERCIAL' => [
            'route' => 'commercial', // Nota: tu ruta se llama 'commercial', no 'comercial'
            'url' => '/comercial',
            'nombre' => 'Dashboard Comercial',
            'prioridad' => 5,
            'icon' => 'fas fa-chart-line'
        ]
    ];
    
    // Verificar cada dashboard
    foreach ($mapDashboards as $permiso => $dashboardInfo) {
        if ($this->tienePermiso($permiso)) {
            $dashboards[] = $dashboardInfo;
        }
    }
    
    // Ordenar por prioridad
    usort($dashboards, function($a, $b) {
        return $a['prioridad'] <=> $b['prioridad'];
    });
    
    return $dashboards;
}
public function getDashboardPrincipal()
{
    $dashboards = $this->getDashboardsDisponibles();
    
    if (empty($dashboards)) {
        return null;
    }
    
    // Siempre retornar el primero (tiene mayor prioridad)
    return $dashboards[0];
}




// app/Models/Usuario.php
public function getMenuDashboards()
{
    $dashboards = $this->getDashboardsDisponibles();
    $menuItems = [];
    $yaIncluidoIndex = false;
    
    foreach ($dashboards as $dashboard) {
        // Evitar duplicar el dashboard principal
        if (($dashboard['route'] == 'index' || $dashboard['url'] == '/') && $yaIncluidoIndex) {
            continue;
        }
        
        if ($dashboard['route'] == 'index' || $dashboard['url'] == '/') {
            $yaIncluidoIndex = true;
        }
        
        $menuItems[] = [
            'nombre' => $dashboard['nombre'],
            'route' => $dashboard['route'],
            'url' => $dashboard['url'],
            'icon' => $dashboard['icon'] ?? 'fas fa-chart-bar'
        ];
    }
    
    return $menuItems;
}

  
}