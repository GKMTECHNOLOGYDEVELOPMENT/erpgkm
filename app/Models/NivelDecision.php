<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NivelDecision extends Model
{
    use HasFactory;

    protected $table = 'niveles_decision';

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function contactos()
    {
        return $this->hasMany(Contacto::class, 'nivel_decision_id');
    }
}
