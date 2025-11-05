<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;

    protected $table = 'cotizaciones';
    protected $primaryKey = 'idCotizaciones';

    protected $fillable = [
        'numero_cotizacion',
        'fecha_emision',
        'valida_hasta',
        'subtotal',
        'igv',
        'total',
        'incluir_igv',
        'terminos_condiciones',
        'dias_validez',
        'terminos_pago',
        'estado_cotizacion',
        'ot',
        'serie',
        'visita_id',
        'idCliente',
        'idMonedas',
        'idTickets',
        'idTienda'
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'valida_hasta' => 'date',
        'subtotal' => 'decimal:2',
        'igv' => 'decimal:2',
        'total' => 'decimal:2',
        'incluir_igv' => 'boolean',
        'dias_validez' => 'integer',
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class, 'idMonedas');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'idTickets');
    }

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'idTienda');
    }

    public function productos()
    {
        return $this->hasMany(CotizacionProducto::class, 'cotizacion_id');
    }

    public function visita()
    {
        return $this->belongsTo(Visita::class, 'visita_id');
    }
}