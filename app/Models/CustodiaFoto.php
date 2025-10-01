<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class CustodiaFoto extends Model
{
    use HasFactory;

    protected $table = 'custodia_fotos';
    
    protected $fillable = [
        'id_custodia',
        'nombre_archivo',
        'nombre_hash',
        'tipo_archivo',
        'tamaño_archivo',
        'datos_imagen',
        'hash_archivo',
        'descripcion',
        'uploaded_by'
    ];

    // Accesor para obtener la imagen desencriptada
    public function getImagenAttribute()
    {
        if ($this->datos_imagen) {
            return Crypt::decrypt($this->datos_imagen);
        }
        return null;
    }

    // Mutator para encriptar la imagen al guardar
    public function setDatosImagenAttribute($value)
    {
        $this->attributes['datos_imagen'] = Crypt::encrypt($value);
    }

    // Obtener la imagen como base64 para mostrar en HTML
    public function getImagenBase64Attribute()
    {
        if ($this->datos_imagen) {
            $imagenDesencriptada = Crypt::decrypt($this->datos_imagen);
            return 'data:' . $this->tipo_archivo . ';base64,' . base64_encode($imagenDesencriptada);
        }
        return null;
    }

    public function custodia()
    {
        return $this->belongsTo(Custodia::class, 'id_custodia');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}