<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudAlmacenArchivos extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'solicitud_almacen_archivos';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'idArchivo';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idSolicitudAlmacen',
        'nombre_archivo',
        'ruta_archivo',
        'tipo_archivo',
        'tamaño',
        'descripcion',
        'uploaded_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tamaño' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con la solicitud de almacén
     */
    public function solicitud()
    {
        return $this->belongsTo(SolicitudAlmacen::class, 'idSolicitudAlmacen', 'idSolicitudAlmacen');
    }

    /**
     * Relación con el usuario que subió el archivo
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Obtener el tamaño del archivo formateado
     */
    public function getTamañoFormateadoAttribute()
    {
        $bytes = $this->tamaño;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Obtener la extensión del archivo
     */
    public function getExtensionAttribute()
    {
        return pathinfo($this->nombre_archivo, PATHINFO_EXTENSION);
    }

    /**
     * Obtener el icono según el tipo de archivo
     */
    public function getIconoAttribute()
    {
        $extension = strtolower($this->extension);

        $iconos = [
            'pdf' => 'file-pdf',
            'doc' => 'file-word',
            'docx' => 'file-word',
            'xls' => 'file-excel',
            'xlsx' => 'file-excel',
            'jpg' => 'file-image',
            'jpeg' => 'file-image',
            'png' => 'file-image',
            'gif' => 'file-image',
            'zip' => 'file-archive',
            'rar' => 'file-archive',
        ];

        return $iconos[$extension] ?? 'file';
    }

    /**
     * Verificar si el archivo es una imagen
     */
    public function getEsImagenAttribute()
    {
        $imagenExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        return in_array($this->extension, $imagenExtensions);
    }

    /**
     * Scope para archivos por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_archivo', 'like', "%{$tipo}%");
    }

    /**
     * Scope para archivos de imagen
     */
    public function scopeImagenes($query)
    {
        return $query->where(function ($q) {
            $q->where('tipo_archivo', 'like', '%image%')
              ->orWhere('nombre_archivo', 'like', '%.jpg')
              ->orWhere('nombre_archivo', 'like', '%.jpeg')
              ->orWhere('nombre_archivo', 'like', '%.png')
              ->orWhere('nombre_archivo', 'like', '%.gif');
        });
    }

    /**
     * Scope para archivos por solicitud
     */
    public function scopePorSolicitud($query, $solicitudId)
    {
        return $query->where('idSolicitudAlmacen', $solicitudId);
    }
}