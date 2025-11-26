<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudAlmacen extends Model
{
    use HasFactory;

    protected $table = 'solicitud_almacen';
    protected $primaryKey = 'idSolicitudAlmacen';
    
    protected $fillable = [
        'codigo_solicitud',
         'idSolicitudAlmacen',
        'solicitante_compra',
        'solicitante_almacen',
        'titulo',
        'idTipoSolicitud',
        'solicitante',
        'idPrioridad',
        'fecha_requerida',
        'idCentroCosto',
        'descripcion',
        'justificacion',
        'observaciones',
        'subtotal',
        'iva',
        'total',
        'total_unidades',
        'estado',
        'idTipoArea',
        'motivo_rechazo',
        'fecha_aprobacion',
        'aprobado_por'
    ];

    protected $casts = [
        'fecha_requerida' => 'date',
        'fecha_aprobacion' => 'datetime',
        'subtotal' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public $timestamps = true;

    // Relaciones
    public function tipoSolicitud()
    {
        return $this->belongsTo(TipoSolicitud::class, 'idTipoSolicitud');
    }

    public function prioridad()
    {
        return $this->belongsTo(PrioridadSolicitud::class, 'idPrioridad');
    }

    public function centroCosto()
    {
        return $this->belongsTo(CentroCosto::class, 'idCentroCosto');
    }

    public function detalles()
    {
        return $this->hasMany(SolicitudAlmacenDetalle::class, 'idSolicitudAlmacen');
    }

    // Relación con archivos adjuntos (si existe la tabla)
    public function archivos()
    {
        return $this->hasMany(SolicitudAlmacenArchivos::class, 'idSolicitudAlmacen', 'idSolicitudAlmacen');
    }

    // Relación con historial (si existe la tabla)
    public function historial()
    {
        return $this->hasMany(SolicitudAlmacenHistorial::class, 'idSolicitudAlmacen', 'idSolicitudAlmacen');
    }

    public function area()
{
    return $this->belongsTo(Tipoarea::class, 'idTipoArea', 'idTipoArea');
}
    public function articulo()
{
    return $this->belongsTo(Articulo::class, 'idArticulo', 'idArticulos');
}

  public function solicitudesCompra()
    {
        return $this->hasMany(SolicitudCompra::class, 'idSolicitudAlmacen', 'idSolicitudAlmacen');
    }
}