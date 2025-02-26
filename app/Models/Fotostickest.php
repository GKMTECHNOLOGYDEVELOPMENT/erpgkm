<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fotostickest extends Model
{
    protected $table = 'fotostickest';
    protected $primaryKey = 'idfotostickest';
    public $timestamps = false;

    protected $fillable = [
        'idTickets',
        'idVisitas',
        'foto',
        'descripcion'
    ];

    public function visita()
    {
        return $this->belongsTo(Visita::class, 'idVisitas', 'idVisitas');
    }
}
