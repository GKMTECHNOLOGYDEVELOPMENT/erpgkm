<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaProveedor extends Model
{
    use HasFactory;

    protected $table = 'entradas_proveedores';
    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'codigo_entrada',
        'tipo_entrada',
        'fecha_ingreso',
        'cliente_general_id',
        'observaciones',
        'archivo_adjunto',
        'estado',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'estado' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // 🔗 Relaciones

    public function clienteGeneral()
    {
        return $this->belongsTo(ClienteGeneral::class, 'cliente_general_id');
    }

    public function solicitudesIngreso()
    {
        return $this->hasMany(SolicitudIngreso::class, 'origen_id', 'id')
                    ->where('origen', 'entrada_proveedor');
    }

    // Accesor para el estado como texto
    public function getEstadoTextoAttribute()
    {
        return $this->estado ? 'Activo' : 'Inactivo';
    }

    // Scope para entradas activas
    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }

    // Scope por tipo de entrada
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_entrada', $tipo);
    }
}