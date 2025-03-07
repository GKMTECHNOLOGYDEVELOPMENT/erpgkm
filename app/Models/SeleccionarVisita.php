<?php

// app/Models/SeleccionarVisita.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeleccionarVisita extends Model
{
    use HasFactory;

    protected $table = 'seleccionarvisita';
    public $timestamps = false;

    protected $primaryKey = 'idselecionarvisita';



    protected $fillable = ['idTickets', 'idVisitas', 'vistaseleccionada'];


    // Relación con el modelo Visita
    public function visita()
    {
        return $this->belongsTo(Visita::class, 'idVisitas', 'idVisitas');
    }

    
}
