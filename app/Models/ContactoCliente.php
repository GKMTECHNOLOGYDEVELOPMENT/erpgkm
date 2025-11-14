<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactoCliente extends Model
{
    protected $table = 'contacto_cliente';
    
    protected $fillable = [
        'contacto_id', 
        'cliente_id'
    ];
    
    public function contacto()
    {
        return $this->belongsTo(ContactoForm::class, 'contacto_id');
    }
    
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'idCliente');
    }
}