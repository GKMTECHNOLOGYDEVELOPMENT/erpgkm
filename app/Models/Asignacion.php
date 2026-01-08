<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Asignacion extends Model
{
    protected $table = 'asignaciones';
    
    protected $fillable = [
        'codigo_asignacion',
        'idUsuario',
        'idSolicitud',
        'codigo_solicitud',
        'id_area_destino',
        'fecha_asignacion',
        'fecha_devolucion',
        'fecha_entrega_real',
        'observaciones',
        'tipo_asignacion',
        'estado',
        'total_articulos',
        'total_cantidad',
        'con_devolucion',
        'sin_devolucion',
        'id_usuario_creador'
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
        'fecha_devolucion' => 'date',
        'fecha_entrega_real' => 'date',
        'con_devolucion' => 'integer',
        'sin_devolucion' => 'integer',
        'total_articulos' => 'integer',
        'total_cantidad' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $attributes = [
        'estado' => 'pendiente',
        'tipo_asignacion' => 'prestamo'
    ];

    /**
     * Obtener el usuario al que se le asignó
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    /**
     * Obtener el usuario que creó la asignación
     */
    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_creador', 'idUsuario');
    }

    /**
     * Obtener el área destino
     */
    public function areaDestino(): BelongsTo
    {
        return $this->belongsTo(TipoArea::class, 'id_area_destino', 'idTipoArea');
    }

    /**
     * Obtener la solicitud relacionada
     */
    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitudesordene::class, 'idSolicitud', 'idsolicitudesordenes');
    }

    /**
     * Obtener los detalles de la asignación
     */
    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleAsignacion::class, 'asignacion_id');
    }

    /**
     * Obtener los artículos asignados
     */
    public function articulos()
    {
        return $this->belongsToMany(Articulo::class, 'detalle_asignaciones', 'asignacion_id', 'articulo_id')
                    ->withPivot([
                        'cantidad', 
                        'numero_serie', 
                        'estado_articulo',
                        'codigo_articulo',
                        'nombre_articulo',
                        'tipo',
                        'fecha_entrega_esperada',
                        'fecha_entrega_real',
                        'fecha_devolucion_esperada',
                        'fecha_devolucion_real',
                        'requiere_devolucion',
                        'observaciones'
                    ])
                    ->withTimestamps();
    }

    /**
     * Obtener los artículos pendientes
     */
    public function articulosPendientes()
    {
        return $this->articulos()->wherePivot('estado_articulo', 'pendiente');
    }

    /**
     * Obtener los artículos activos (entregados)
     */
    public function articulosActivos()
    {
        return $this->articulos()->wherePivot('estado_articulo', 'activo');
    }

    /**
     * Obtener los artículos con devolución
     */
    public function articulosConDevolucion()
    {
        return $this->articulos()->wherePivot('requiere_devolucion', 1);
    }

    /**
     * Obtener los artículos para uso diario
     */
    public function articulosUsoDiario()
    {
        return $this->articulos()->wherePivot('tipo', 'uso_diario');
    }

    /**
     * Obtener los artículos de préstamo
     */
    public function articulosPrestamo()
    {
        return $this->articulos()->wherePivot('tipo', 'prestamo');
    }

    /**
     * Verificar si la asignación está pendiente
     */
    public function estaPendiente(): bool
    {
        return $this->estado === 'pendiente';
    }

    /**
     * Verificar si la asignación está activa
     */
    public function estaActiva(): bool
    {
        return $this->estado === 'activo';
    }

    /**
     * Verificar si la asignación está devuelta
     */
    public function estaDevuelta(): bool
    {
        return $this->estado === 'devuelto';
    }

    /**
     * Verificar si la asignación está vencida
     */
    public function estaVencida(): bool
    {
        return $this->estado === 'vencido';
    }

    /**
     * Verificar si es asignación de uso diario
     */
    public function esUsoDiario(): bool
    {
        return $this->tipo_asignacion === 'uso_diario';
    }

    /**
     * Verificar si es asignación de préstamo
     */
    public function esPrestamo(): bool
    {
        return $this->tipo_asignacion === 'prestamo';
    }

    /**
     * Calcular días restantes para devolución
     */
    public function diasParaDevolucion(): ?int
    {
        if (!$this->fecha_devolucion || $this->estaDevuelta()) {
            return null;
        }

        return now()->diffInDays($this->fecha_devolucion, false);
    }

    /**
     * Marcar como entregada
     */
    public function marcarComoEntregada($fechaEntrega = null): bool
    {
        return $this->update([
            'estado' => 'activo',
            'fecha_entrega_real' => $fechaEntrega ?? now()
        ]);
    }

    /**
     * Marcar como devuelta
     */
    public function marcarComoDevuelta(): bool
    {
        return $this->update([
            'estado' => 'devuelto',
            'updated_at' => now()
        ]);
    }

    /**
     * Scope para asignaciones pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para asignaciones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para asignaciones de un usuario específico
     */
    public function scopeDeUsuario($query, $usuarioId)
    {
        return $query->where('idUsuario', $usuarioId);
    }

    /**
     * Scope para asignaciones con devolución pendiente
     */
    public function scopeConDevolucionPendiente($query)
    {
        return $query->where('estado', 'activo')
                     ->whereNotNull('fecha_devolucion')
                     ->where('fecha_devolucion', '<', now());
    }
}