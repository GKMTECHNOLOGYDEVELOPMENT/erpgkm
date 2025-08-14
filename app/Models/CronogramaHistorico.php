<?php
// app/Models/CronogramaHistorico.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CronogramaHistorico extends Model
{
    protected $table = 'cronograma_historico';
    public $timestamps = false; // Solo usa created_at

    protected $fillable = [
        'idSeguimiento',
        'task_id',
        'accion',
        'datos_anteriores',
        'datos_nuevos',
        'usuario_id'
    ];

    protected $casts = [
        'datos_anteriores' => 'array',
        'datos_nuevos' => 'array',
        'created_at' => 'datetime'
    ];

    // Relación con seguimiento
    public function seguimiento(): BelongsTo
    {
        return $this->belongsTo(Seguimiento::class, 'idSeguimiento', 'idSeguimiento');
    }

    // Relación con usuario
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}