<?php
// app/Models/Task.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'project_id', 
        'title', 
        'description', 
        'idseguimiento',
        'image', 
        'date',
        'tags',
        'idpersona',
        'fecha_inicio',
        'fecha_fin',
        'duracion',
    ];

    protected $casts = [
        'tags' => 'array',
        'date' => 'date'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

public function cotizaciones()
{
    return $this->hasMany(TaskCotizacion::class);
}

public function reuniones()
{
    return $this->hasMany(TaskReunion::class);
}
public function levantamientos()
{
    return $this->hasMany(TaskLevantamiento::class);
}

public function ganados()
{
    return $this->hasMany(TaskGanado::class);
}

public function observados()
{
    return $this->hasMany(TaskObservado::class);
}

public function rechazados()
{
    return $this->hasMany(TaskRechazado::class);
}

}