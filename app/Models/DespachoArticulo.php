<?php
// app/Models/DespachoArticulo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DespachoArticulo extends Model
{
    use HasFactory;

    protected $table = 'despacho_articulos';

    protected $fillable = [
        'despacho_id',
        'articulo_id',
        'codigo',
        'descripcion',
        'stock',
        'unidad',
        'precio',
        'cantidad',
        'subtotal'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relaciones
    public function despacho()
    {
        return $this->belongsTo(Despacho::class);
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'articulo_id', 'idArticulos');
    }
}