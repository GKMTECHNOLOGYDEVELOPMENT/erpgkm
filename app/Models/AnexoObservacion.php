<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnexoObservacion extends Model
{
    use HasFactory;

    protected $table = 'anexo_observaciones';
    protected $primaryKey = 'idAnexo_observaciones';
    public $timestamps = false;

    protected $fillable = [
        'foto',
        'idObservaciones',
    ];
}
