<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetalleAsignacion extends Model
{
    protected $table = 'detalle_asignaciones';
    
    protected $fillable = [
        'asignacion_id',
        'articulo_id',
        'codigo_articulo',
        'nombre_articulo',
        'cantidad',
        'tipo',
        'numero_serie',
        'estado_articulo',
        'fecha_entrega_esperada',
        'fecha_entrega_real',
        'fecha_devolucion_esperada',
        'fecha_devolucion_real',
        'requiere_devolucion',
        'observaciones',
        'id_solicitud_detalle'
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'requiere_devolucion' => 'boolean',
        'fecha_entrega_esperada' => 'date',
        'fecha_entrega_real' => 'date',
        'fecha_devolucion_esperada' => 'date',
        'fecha_devolucion_real' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $attributes = [
        'estado_articulo' => 'pendiente',
        'tipo' => 'prestamo',
        'requiere_devolucion' => false
    ];

    /**
     * Obtener la asignación a la que pertenece
     */
    public function asignacion(): BelongsTo
    {
        return $this->belongsTo(Asignacion::class, 'asignacion_id');
    }

    /**
     * Obtener el artículo asignado
     */
    public function articulo(): BelongsTo
    {
        return $this->belongsTo(Articulo::class, 'articulo_id', 'idArticulos');
    }

    /**
     * Obtener el detalle de la solicitud relacionada
     */
    public function solicitudDetalle(): BelongsTo
    {
        return $this->belongsTo(Ordenesarticulo::class, 'id_solicitud_detalle');
    }

    /**
     * Verificar si el artículo está pendiente
     */
    public function estaPendiente(): bool
    {
        return $this->estado_articulo === 'pendiente';
    }

    /**
     * Verificar si el artículo está activo (en uso)
     */
    public function estaActivo(): bool
    {
        return $this->estado_articulo === 'activo';
    }

    /**
     * Verificar si el artículo fue entregado
     */
    public function fueEntregado(): bool
    {
        return $this->estado_articulo === 'entregado';
    }

    /**
     * Verificar si el artículo fue devuelto
     */
    public function fueDevuelto(): bool
    {
        return $this->estado_articulo === 'devuelto';
    }

    /**
     * Verificar si el artículo está dañado
     */
    public function estaDanado(): bool
    {
        return $this->estado_articulo === 'dañado';
    }

    /**
     * Verificar si el artículo está perdido
     */
    public function estaPerdido(): bool
    {
        return $this->estado_articulo === 'perdido';
    }

    /**
     * Verificar si es artículo de uso diario
     */
    public function esUsoDiario(): bool
    {
        return $this->tipo === 'uso_diario';
    }

    /**
     * Verificar si es artículo de préstamo
     */
    public function esPrestamo(): bool
    {
        return $this->tipo === 'prestamo';
    }

    /**
     * Marcar como entregado
     */
    public function marcarComoEntregado($fechaEntrega = null): bool
    {
        return $this->update([
            'estado_articulo' => 'entregado',
            'fecha_entrega_real' => $fechaEntrega ?? now()
        ]);
    }

    /**
     * Marcar como activo (en uso)
     */
    public function marcarComoActivo(): bool
    {
        return $this->update([
            'estado_articulo' => 'activo'
        ]);
    }

    /**
     * Marcar como devuelto
     */
    public function marcarComoDevuelto($fechaDevolucion = null): bool
    {
        return $this->update([
            'estado_articulo' => 'devuelto',
            'fecha_devolucion_real' => $fechaDevolucion ?? now()
        ]);
    }

    /**
     * Marcar como dañado
     */
    public function marcarComoDanado(): bool
    {
        return $this->update([
            'estado_articulo' => 'dañado'
        ]);
    }

    /**
     * Marcar como perdido
     */
    public function marcarComoPerdido(): bool
    {
        return $this->update([
            'estado_articulo' => 'perdido'
        ]);
    }

    /**
     * Calcular días restantes para devolución
     */
    public function diasParaDevolucion(): ?int
    {
        if (!$this->fecha_devolucion_esperada || $this->fueDevuelto()) {
            return null;
        }

        return now()->diffInDays($this->fecha_devolucion_esperada, false);
    }

    /**
     * Verificar si está vencido
     */
    public function estaVencido(): bool
    {
        if (!$this->fecha_devolucion_esperada || $this->fueDevuelto()) {
            return false;
        }

        return now()->greaterThan($this->fecha_devolucion_esperada);
    }

    /**
     * Scope para artículos pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado_articulo', 'pendiente');
    }

    /**
     * Scope para artículos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado_articulo', 'activo');
    }

    /**
     * Scope para artículos con devolución
     */
    public function scopeConDevolucion($query)
    {
        return $query->where('requiere_devolucion', true);
    }

    /**
     * Scope para artículos de uso diario
     */
    public function scopeUsoDiario($query)
    {
        return $query->where('tipo', 'uso_diario');
    }

    /**
     * Scope para artículos de préstamo
     */
    public function scopePrestamo($query)
    {
        return $query->where('tipo', 'prestamo');
    }

    /**
     * Scope para artículos vencidos
     */
    public function scopeVencidos($query)
    {
        return $query->where('requiere_devolucion', true)
                     ->where('estado_articulo', '!=', 'devuelto')
                     ->where('fecha_devolucion_esperada', '<', now());
    }
}