<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticuloModelo extends Model
{
    protected $table = 'articulo_modelo';

    protected $primaryKey = 'id';

    protected $fillable = [
        'articulo_id',
        'modelo_id',
    ];

    public $timestamps = true;

    // Relaciones
    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'articulo_id', 'idArticulos');
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class, 'modelo_id', 'idModelo');
    }
}
