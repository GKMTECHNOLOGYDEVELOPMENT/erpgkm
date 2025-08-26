<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskReunion extends Model
{
    protected $table = 'task_reuniones';

    protected $fillable = [
        'task_id',
        'fecha_reunion',
        'tipo_reunion',
        'motivo_reunion',
        'responsable_reunion',
        'link_reunion',
        'direccion_fisica',
        'minuta',
        'actividades',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

     // Relación con participantes - CORREGIDO
    public function participantesComercial()
    {
        return $this->hasMany(ReunionParticipanteComercial::class, 'reunion_id');
    }
}
