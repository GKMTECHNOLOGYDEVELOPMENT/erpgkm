<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCompraArchivo extends Model
{
    use HasFactory;

    protected $table = 'solicitud_compra_archivos';
    protected $primaryKey = 'idArchivo';

    protected $fillable = [
        'idSolicitudCompra',
        'nombre_archivo',
        'ruta_archivo',
        'tipo_archivo',
        'tamaño',
        'descripcion'
    ];

    // Relación
    public function solicitudCompra()
    {
        return $this->belongsTo(SolicitudCompra::class, 'idSolicitudCompra');
    }
}