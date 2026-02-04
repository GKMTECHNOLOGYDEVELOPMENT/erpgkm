<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificacionSolicitudAsistencia extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'notificaciones_solicitud_asistencia';

    /**
     * La clave primaria asociada a la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'id_notificacion_solicitud';

    /**
     * Indica si el modelo tiene timestamps.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_solicitud_asistencia',
        'estado_web',
        'estado_app',
        'fecha',
        'tipo',
        'created_at',
        'updated_at'
    ];

    /**
     * Los atributos que deben ser casteados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'estado_web' => 'integer',
        'estado_app' => 'integer',
        'fecha' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Los valores por defecto para los atributos del modelo.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'estado_web' => 0,
        'estado_app' => 0,
    ];

    /**
     * Relación con la tabla solicitud_asistencia.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function solicitudAsistencia(): BelongsTo
    {
        return $this->belongsTo(
            SolicitudAsistencia::class,
            'id_solicitud_asistencia',
            'id_solicitud_asistencia'
        );
    }

    /**
     * Relación con el usuario (si tienes tabla usuarios).
     * Descomentar si necesitas relacionar con usuario.
     */
    /*
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'id_usuario',
            'id'
        );
    }
    */

    /**
     * Scope para obtener notificaciones no leídas en web.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNoLeidasWeb($query)
    {
        return $query->where('estado_web', 0);
    }

    /**
     * Scope para obtener notificaciones no leídas en app.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNoLeidasApp($query)
    {
        return $query->where('estado_app', 0);
    }

    /**
     * Scope para obtener notificaciones leídas en web.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLeidasWeb($query)
    {
        return $query->where('estado_web', 1);
    }

    /**
     * Scope para obtener notificaciones leídas en app.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLeidasApp($query)
    {
        return $query->where('estado_app', 1);
    }

    /**
     * Scope para filtrar por tipo de notificación.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $tipo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para obtener notificaciones recientes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $dias
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecientes($query, int $dias = 7)
    {
        return $query->where('fecha', '>=', now()->subDays($dias));
    }

    /**
     * Marcar notificación como leída en web.
     *
     * @return bool
     */
    public function marcarLeidaWeb(): bool
    {
        return $this->update(['estado_web' => 1]);
    }

    /**
     * Marcar notificación como leída en app.
     *
     * @return bool
     */
    public function marcarLeidaApp(): bool
    {
        return $this->update(['estado_app' => 1]);
    }

    /**
     * Verificar si la notificación está leída en web.
     *
     * @return bool
     */
    public function estaLeidaWeb(): bool
    {
        return $this->estado_web === 1;
    }

    /**
     * Verificar si la notificación está leída en app.
     *
     * @return bool
     */
    public function estaLeidaApp(): bool
    {
        return $this->estado_app === 1;
    }

    /**
     * Obtener el texto descriptivo del tipo de notificación.
     *
     * @return string
     */
    public function getTipoDescripcionAttribute(): string
    {
        $tipos = [
            'SOLICITUD_ASISTENCIA_CREADA' => 'Nueva solicitud creada',
            'SOLICITUD_ASISTENCIA_ACTUALIZADA' => 'Solicitud actualizada',
            'SOLICITUD_ASISTENCIA_APROBADA' => 'Solicitud aprobada',
            'SOLICITUD_ASISTENCIA_DENEGADA' => 'Solicitud denegada',
            'SOLICITUD_ASISTENCIA_ELIMINADA' => 'Solicitud eliminada',
        ];

        return $tipos[$this->tipo] ?? 'Notificación de solicitud';
    }

    /**
     * Obtener el icono según el tipo de notificación.
     *
     * @return string
     */
    public function getIconoAttribute(): string
    {
        $iconos = [
            'SOLICITUD_ASISTENCIA_CREADA' => 'fas fa-plus-circle',
            'SOLICITUD_ASISTENCIA_ACTUALIZADA' => 'fas fa-edit',
            'SOLICITUD_ASISTENCIA_APROBADA' => 'fas fa-check-circle',
            'SOLICITUD_ASISTENCIA_DENEGADA' => 'fas fa-times-circle',
            'SOLICITUD_ASISTENCIA_ELIMINADA' => 'fas fa-trash-alt',
        ];

        return $iconos[$this->tipo] ?? 'fas fa-bell';
    }

    /**
     * Obtener el color según el tipo de notificación.
     *
     * @return string
     */
    public function getColorAttribute(): string
    {
        $colores = [
            'SOLICITUD_ASISTENCIA_CREADA' => 'info',
            'SOLICITUD_ASISTENCIA_ACTUALIZADA' => 'warning',
            'SOLICITUD_ASISTENCIA_APROBADA' => 'success',
            'SOLICITUD_ASISTENCIA_DENEGADA' => 'danger',
            'SOLICITUD_ASISTENCIA_ELIMINADA' => 'dark',
        ];

        return $colores[$this->tipo] ?? 'primary';
    }

    /**
     * Verificar si la notificación es reciente (últimas 24 horas).
     *
     * @return bool
     */
    public function getEsRecienteAttribute(): bool
    {
        return $this->fecha >= now()->subDay();
    }

    /**
     * Formatear fecha para mostrar.
     *
     * @return string
     */
    public function getFechaFormateadaAttribute(): string
    {
        return $this->fecha->format('d/m/Y H:i');
    }

    /**
     * Obtener tiempo transcurrido desde la notificación.
     *
     * @return string
     */
    public function getTiempoTranscurridoAttribute(): string
    {
        return $this->fecha->diffForHumans();
    }
}