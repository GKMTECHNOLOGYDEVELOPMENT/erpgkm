<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_favorite',
        'user_id',
        'tag_id'
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

  // En App/Models/Note.php
public function user(): BelongsTo
{
    return $this->belongsTo(Usuario::class, 'user_id', 'idUsuario');
    // Parámetros:
    // 1. Modelo relacionado (Usuario)
    // 2. Clave foránea en la tabla notes (user_id)
    // 3. Clave primaria en la tabla usuarios (idUsuario)
}

public function tag(): BelongsTo
{
    return $this->belongsTo(Tag::class, 'tag_id', 'id');
    // tag_id en notes -> id en tags
}
    // Scopes para filtrar notas
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeFavorites($query)
    {
        return $query->where('is_favorite', true);
    }

    public function scopeWithTag($query, $tagId)
    {
        return $query->where('tag_id', $tagId);
    }

    public function scopeByTag($query, $tagName)
    {
        return $query->whereHas('tag', function ($q) use ($tagName) {
            $q->where('name', $tagName);
        });
    }

    // Accessor para la fecha formateada
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }

    // Accessor para obtener el nombre del tag
    public function getTagNameAttribute()
    {
        return $this->tag ? $this->tag->name : null;
    }

    // Accessor para obtener el color del tag
    public function getTagColorAttribute()
    {
        return $this->tag ? $this->tag->color : null;
    }
}