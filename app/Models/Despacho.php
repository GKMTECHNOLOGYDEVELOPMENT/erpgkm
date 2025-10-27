<?php
// app/Models/Despacho.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despacho extends Model
{
    use HasFactory;

    protected $table = 'despachos';

    protected $fillable = [
        'tipo_guia',
        'numero',
        'documento',
        'fecha_entrega',
        'fecha_traslado',
        'direccion_partida',
        'departamento_partida',
        'provincia_partida',
        'distrito_partida',
        'direccion_llegada',
        'departamento_llegada',
        'provincia_llegada',
        'distrito_llegada',
        'cliente_id',
        'modo_traslado',
        'vendedor_id',
        'conductor_id',
        'trasbordo',
        'condiciones',
        'tipo_traslado',
        'subtotal',
        'igv',
        'total',
        'observaciones',
        'estado'
    ];

    protected $casts = [
        'fecha_entrega' => 'date',
        'fecha_traslado' => 'date',
        'subtotal' => 'decimal:2',
        'igv' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'idCliente');
    }

    public function vendedor()
    {
        return $this->belongsTo(Usuario::class, 'vendedor_id', 'idUsuario');
    }

    public function conductor()
    {
        return $this->belongsTo(Usuario::class, 'conductor_id', 'idUsuario');
    }

    public function articulos()
    {
        return $this->hasMany(DespachoArticulo::class);
    }
}