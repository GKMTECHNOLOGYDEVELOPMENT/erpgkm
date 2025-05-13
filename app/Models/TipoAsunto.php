<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoAsunto extends Model
{
    protected $table = 'tipoasunto';
    protected $primaryKey = 'idTipoAsunto';
    public $timestamps = false;

    protected $fillable = ['nombre'];
}
