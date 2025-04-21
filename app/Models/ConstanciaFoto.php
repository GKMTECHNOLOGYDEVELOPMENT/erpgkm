<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ConstanciaFoto extends Model
{
    protected $table = 'constancia_fotos';
    protected $primaryKey = 'idfoto';
    
    protected $fillable = [
        'idconstancia',
        'imagen',
        'descripcion'
    ];

   

    /**
     * Relación con la constancia de entrega
     */
    public function constancia(): BelongsTo
    {
        return $this->belongsTo(ConstanciaEntrega::class, 'idconstancia');
    }

    /**
     * Obtener la imagen como recurso para mostrarla
     */
    public function getImagenParaMostrar()
    {
        return "data:image/jpeg;base64," . base64_encode($this->imagen);
    }



    /**
     * Obtener el tamaño de la imagen formateado
     */
    public function getTamanioFormateadoAttribute()
    {
        $tamanio = strlen($this->imagen);
        if ($tamanio < 1024) {
            return $tamanio . ' B';
        } elseif ($tamanio < 1048576) {
            return round($tamanio / 1024, 2) . ' KB';
        } else {
            return round($tamanio / 1048576, 2) . ' MB';
        }
    }
}