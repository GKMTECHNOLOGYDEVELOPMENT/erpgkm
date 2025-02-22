<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fotostickest extends Model
{
    protected $table = 'fotostickest';

    protected $fillable = [
        'idTickets',
        'idVisitas',
        'foto',
        'descripcion'
    ];

    public $timestamps = false; // Si no tienes campos created_at y updated_at en tu tabla
}
