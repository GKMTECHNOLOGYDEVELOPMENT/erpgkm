<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudArticulo extends Model
{
    protected $table = 'solicitud_articulos';

    protected $primaryKey = 'idArticuloSolicitud';

    public $timestamps = false; // si no tienes created_at y updated_at

    protected $fillable = [
        'idSolicitud',
        'codigoSolicitud',
        'idArticulo',
        'cantidad',
        'descripcion',
    ];

    // Relación: cada artículo pertenece a una solicitud
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'idSolicitud');
    }

    

    
}
