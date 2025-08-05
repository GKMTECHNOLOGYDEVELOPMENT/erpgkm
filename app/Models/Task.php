<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'description', 
        'status_id', 
        'user_id',
        'seguimiento_id',
        'empresa_id',
        'contacto_id'
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seguimiento()
    {
        return $this->belongsTo(Seguimiento::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function contacto()
    {
        return $this->belongsTo(Contactos::class);
    }
}