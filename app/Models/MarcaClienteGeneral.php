<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarcaClienteGeneral extends Model
{
    protected $table = 'marca_clientegeneral';
    protected $primaryKey = 'idMarcaClienteGeneral';
    public $timestamps = false;

    protected $fillable = ['idMarca', 'idClienteGeneral'];

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'idMarca');
    }

    public function clienteGeneral()
    {
        return $this->belongsTo(Clientegeneral::class, 'idClienteGeneral');
    }
}

