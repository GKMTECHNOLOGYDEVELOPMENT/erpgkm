<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsuarioNotificacion extends Model
{
    use HasFactory;

    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'notificaciones_usuario';

    /**
     * La clave primaria asociada a la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'idNotificacionUsuario';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<string>
     */
    protected $fillable = [
        'idUsuario',
        'estado_web',
        'estado_app',
        'fecha',
        'tipo',
        'created_at',
        'updated_at'
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'fecha' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obtener el usuario asociado a esta notificación.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    /**
     * Scope para filtrar por usuario
     */
    public function scopePorUsuario($query, $idUsuario)
    {
        return $query->where('idUsuario', $idUsuario);
    }

    /**
     * Scope para filtrar por tipo
     */
    public function scopeDeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para notificaciones pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where(function ($q) {
            $q->where('estado_web', '!=', 'leido')
                ->orWhere('estado_web', 'pendiente')
                ->orWhereNull('estado_web');
        });
    }

    /**
     * Método para marcar como leído
     */
    public function marcarComoLeido($canal = 'web'): void
    {
        if ($canal === 'web') {
            $this->estado_web = 'leido';
        } elseif ($canal === 'app') {
            $this->estado_app = 'leido';
        }
        $this->save();
    }
}
