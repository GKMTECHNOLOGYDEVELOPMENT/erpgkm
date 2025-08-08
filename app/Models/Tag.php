<?php

// app/Models/Tag.php
// php artisan make:model Tag

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'description',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

   // En App/Models/Tag.php
public function user(): BelongsTo
{
    return $this->belongsTo(Usuario::class, 'user_id', 'idUsuario');
    // user_id en tags -> idUsuario en usuarios
}

public function notes(): HasMany
{
    return $this->hasMany(Note::class, 'tag_id', 'id');
    // tag_id en notes -> id en tags
}

    // Scope para obtener solo los tags del usuario autenticado
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Accessor para obtener el conteo de notas
    public function getNotesCountAttribute()
    {
        return $this->notes()->count();
    }
}