<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FuenteCaptacion extends Model
{
    use HasFactory;

    protected $table = 'fuentes_captacion';

    protected $fillable = ['nombre', 'descripcion'];

    public function empresas()
    {
        return $this->hasMany(Empresa::class, 'fuente_captacion_id');
    }
}
