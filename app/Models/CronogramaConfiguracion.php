<?php
// app/Models/CronogramaConfiguracion.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CronogramaConfiguracion extends Model
{
    protected $table = 'cronograma_configuraciones';

    protected $fillable = [
        'idSeguimiento',
        'vista_actual',
        'zoom_inicio',
        'zoom_fin',
        'configuracion_json',
        
    ];

    protected $casts = [
        'zoom_inicio' => 'date',
        'zoom_fin' => 'date',
        'configuracion_json' => 'array'
    ];

    // Relación con seguimiento
    public function seguimiento(): BelongsTo
    {
        return $this->belongsTo(Seguimiento::class, 'idSeguimiento', 'idSeguimiento');
    }
}