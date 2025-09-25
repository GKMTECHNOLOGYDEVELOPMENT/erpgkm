<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SolicitudIngreso extends Model
{
    use HasFactory;

    protected $table = 'solicitud_ingreso';
    protected $primaryKey = 'idSolicitudIngreso';
    public $timestamps = true;

    protected $fillable = [
        'origen', 'origen_id', 'articulo_id', 'cantidad', 'fecha_origen',
        'proveedor_id', 'cliente_general_id', 'ubicacion', 'lote',
        'fecha_vencimiento', 'observaciones', 'estado', 'usuario_id',
    ];

    protected $casts = [
        'fecha_origen' => 'date',
        'fecha_vencimiento' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // 🔗 Relaciones CORREGIDAS

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'articulo_id', 'idArticulos');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedore::class, 'proveedor_id', 'idProveedor');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_general_id', 'idCliente');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'idUsuario');
    }

    // Relación con Compra
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'origen_id', 'idCompra');
    }

    // Relación con EntradaProveedor (CORREGIDA)
    public function entradaProveedor()
    {
        return $this->belongsTo(EntradaProveedor::class, 'origen_id', 'id');
    }

    // Accesor para obtener el origen específico según el tipo
    public function getOrigenEspecificoAttribute()
    {
        if ($this->origen === 'compra') {
            return $this->compra;
        } elseif ($this->origen === 'entrada_proveedor') {
            return $this->entradaProveedor;
        }
        return null;
    }

    // Accesor para obtener el código del origen
    public function getCodigoOrigenAttribute()
    {
        if ($this->origen === 'compra') {
            return $this->compra->codigocompra ?? 'N/A';
        } elseif ($this->origen === 'entrada_proveedor') {
            return $this->entradaProveedor->codigo_entrada ?? 'N/A';
        }
        return 'N/A';
    }

    // Scope para filtrar por origen
    public function scopePorOrigen($query, $origen)
    {
        return $query->where('origen', $origen);
    }

    // Scope para solicitudes pendientes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    // Scope para solicitudes recibidas
    public function scopeRecibidas($query)
    {
        return $query->where('estado', 'recibido');
    }

    // Scope para solicitudes ubicadas
    public function scopeUbicadas($query)
    {
        return $query->where('estado', 'ubicado');
    }

    public function clienteGeneral()
{
    return $this->belongsTo(ClienteGeneral::class, 'cliente_general_id', 'idClienteGeneral');
}


// En tu modelo SolicitudIngreso.php agrega esta relación:

// En app/Models/SolicitudIngreso.php

public function ubicaciones()
{
    return $this->hasMany(ArticuloUbicacion::class, 'origen_id', 'origen_id')
        ->where('origen', $this->origen)
        ->where('articulo_id', $this->articulo_id);
}
}