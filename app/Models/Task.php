<?php
// app/Models/Task.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'project_id', 
        'title', 
        'description', 
        'idseguimiento',
        'image', 
        'date',
        'tags'
    ];

    protected $casts = [
        'tags' => 'array',
        'date' => 'date'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}