<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Seguimiento extends Model
{
    protected $table = 'seguimientos';
    protected $primaryKey = 'idSeguimiento';
    public $timestamps = true;

    protected $fillable = [
        'idEmpresa',
        'idContacto',
        'idUsuario',
        'tipoRegistro',
        'fechaIngreso',
    ];

    // Relaciones
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'idEmpresa');
    }

    public function contacto()
    {
        return $this->belongsTo(Contactos::class, 'idContacto');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario');
    }
    public function cronogramaTareas(): HasMany
    {
        return $this->hasMany(CronogramaTarea::class, 'idSeguimiento', 'idSeguimiento');
    }

    public function cronogramaDependencias(): HasMany
    {
        return $this->hasMany(CronogramaDependencia::class, 'idSeguimiento', 'idSeguimiento');
    }

    public function cronogramaConfiguracion(): HasOne
    {
        return $this->hasOne(CronogramaConfiguracion::class, 'idSeguimiento', 'idSeguimiento');
    }

    public function cronogramaHistorico(): HasMany
    {
        return $this->hasMany(CronogramaHistorico::class, 'idSeguimiento', 'idSeguimiento');
    }
}
