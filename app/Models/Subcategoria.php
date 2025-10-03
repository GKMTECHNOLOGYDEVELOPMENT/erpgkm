<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;

    protected $table = 'subcategorias';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

     // Relación con artículos
    public function articulos()
    {
        return $this->hasMany(Articulo::class, 'idsubcategoria', 'id');
    }
}
