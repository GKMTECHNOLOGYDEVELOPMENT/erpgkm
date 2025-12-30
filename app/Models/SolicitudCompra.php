<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCompra extends Model
{
    use HasFactory;

    protected $table = 'solicitud_compra';
    protected $primaryKey = 'idSolicitudCompra';

    protected $fillable = [
        'codigo_solicitud',
        'idSolicitudAlmacen',
        'solicitante_compra',
        'solicitante_almacen',
        'solicitante',
        'idTipoArea',
        'idPrioridad',
        'fecha_requerida',
        'idCentroCosto',
        'proyecto_asociado',
        'justificacion',
        'observaciones',
        'subtotal',
        'iva',
        'total',
        'total_unidades',
        'estado',
        'motivo_rechazo',
        'fecha_aprobacion',
        'fecha_observacion',
        'comentario_observacion',
        'fecha_reprogramacion',
        'observado_por',
        'aprobado_por'
    ];

    protected $casts = [
        'fecha_requerida' => 'date',
        'fecha_aprobacion' => 'datetime',
        'subtotal' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    // Relaciones
    public function solicitudAlmacen()
    {
        return $this->belongsTo(SolicitudAlmacen::class, 'idSolicitudAlmacen');
    }

    public function tipoArea()
    {
        return $this->belongsTo(TipoArea::class, 'idTipoArea');
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
        return $this->hasMany(SolicitudCompraDetalle::class, 'idSolicitudCompra');
    }

    public function archivos()
    {
        return $this->hasMany(SolicitudCompraArchivo::class, 'idSolicitudCompra');
    }

    // Scope para búsquedas
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'aprobada');
    }
}