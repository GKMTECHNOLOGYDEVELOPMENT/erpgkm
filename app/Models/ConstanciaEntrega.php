<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstanciaEntrega extends Model
{
    use SoftDeletes;

    protected $table = 'constancia_entregas';
    protected $primaryKey = 'idconstancia';
    
    protected $fillable = [
        'numeroticket',
        'tipo',
        'fechacompra',
        'nombrecliente',
        'emailcliente',
        'direccioncliente',
        'telefonocliente',
        'observaciones',
        'idticket'
    ];

    protected $dates = [
        'fechacompra',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Relación con las fotos adjuntas
     */
    public function fotos(): HasMany
    {
        return $this->hasMany(ConstanciaFoto::class, 'idconstancia');
    }

    /**
     * Relación con el ticket (si existe)
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'idTickets');
    }

    /**
     * Scope para búsqueda por número de ticket
     */
    public function scopePorNumeroTicket($query, $numeroTicket)
    {
        return $query->where('numeroticket', 'like', "%$numeroTicket%");
    }

    /**
     * Obtener el nombre completo del cliente
     */
    public function getClienteCompletoAttribute()
    {
        return $this->nombrecliente;
    }

    /**
     * Obtener la fecha formateada
     */
    public function getFechaCompraFormateadaAttribute()
    {
        return $this->fechacompra->format('d/m/Y');
    }
}