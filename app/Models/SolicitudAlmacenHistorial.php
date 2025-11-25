<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudAlmacenHistorial extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'solicitud_almacen_historial';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'idHistorial';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idSolicitudAlmacen',
        'estado_anterior',
        'estado_nuevo',
        'observaciones',
        'usuario_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con la solicitud de almacén
     */
    public function solicitud()
    {
        return $this->belongsTo(SolicitudAlmacen::class, 'idSolicitudAlmacen', 'idSolicitudAlmacen');
    }

    /**
     * Relación con el usuario que realizó el cambio
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Scope para filtrar por solicitud
     */
    public function scopePorSolicitud($query, $solicitudId)
    {
        return $query->where('idSolicitudAlmacen', $solicitudId);
    }

    /**
     * Scope para ordenar por fecha más reciente primero
     */
    public function scopeRecientesPrimero($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope para filtrar por estado nuevo
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado_nuevo', $estado);
    }

    /**
     * Obtener el texto descriptivo del estado anterior
     */
    public function getEstadoAnteriorTextoAttribute()
    {
        return $this->getEstadoTexto($this->estado_anterior);
    }

    /**
     * Obtener el texto descriptivo del estado nuevo
     */
    public function getEstadoNuevoTextoAttribute()
    {
        return $this->getEstadoTexto($this->estado_nuevo);
    }

    /**
     * Método helper para obtener texto descriptivo de estados
     */
    protected function getEstadoTexto($estado)
    {
        $estados = [
            'pendiente' => 'Pendiente',
            'aprobada' => 'Aprobada',
            'rechazada' => 'Rechazada',
            'en_proceso' => 'En Proceso',
            'completada' => 'Completada'
        ];

        return $estados[$estado] ?? $estado;
    }

    /**
     * Obtener la descripción del cambio de estado
     */
    public function getDescripcionCambioAttribute()
    {
        if ($this->estado_anterior && $this->estado_nuevo) {
            return "Cambio de {$this->estado_anterior_texto} a {$this->estado_nuevo_texto}";
        } elseif ($this->estado_nuevo) {
            return "Estado establecido como {$this->estado_nuevo_texto}";
        }

        return "Cambio de estado";
    }

    /**
     * Verificar si el cambio fue realizado por un usuario específico
     */
    public function fueRealizadoPor($usuarioId)
    {
        return $this->usuario_id == $usuarioId;
    }

    /**
     * Obtener la fecha formateada
     */
    public function getFechaFormateadaAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    /**
     * Obtener información resumida del historial
     */
    public function getResumenAttribute()
    {
        return [
            'id' => $this->idHistorial,
            'estado_anterior' => $this->estado_anterior,
            'estado_nuevo' => $this->estado_nuevo,
            'estado_anterior_texto' => $this->estado_anterior_texto,
            'estado_nuevo_texto' => $this->estado_nuevo_texto,
            'observaciones' => $this->observaciones,
            'usuario' => $this->usuario ? $this->usuario->name : 'Sistema',
            'fecha' => $this->fecha_formateada,
            'descripcion_cambio' => $this->descripcion_cambio
        ];
    }
}