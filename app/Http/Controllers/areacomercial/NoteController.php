<?php

// app/Http/Controllers/NoteController.php
// php artisan make:controller NoteController --resource

namespace App\Http\Controllers\areacomercial;
use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

   public function index(Request $request)
{
    $user = Auth::user();
    $filter = $request->get('filter', 'all');
    
    $query = $user->notes()->with('tag');

    switch ($filter) {
        case 'favorites':
            $query->where('is_favorite', true);
            break;
        case 'all':
            // No filter needed
            break;
        default:
            // Filtra por nombre del tag (case insensitive)
            $query->whereHas('tag', function($q) use ($filter) {
                $q->where('name', 'LIKE', $filter);
                // o para coincidencia exacta:
                // $q->where('name', $filter);
            });
            break;
    }

    $notes = $query->orderBy('created_at', 'desc')->get();
    $tags = $user->tags;

    return response()->json([
        'notes' => $notes->map(function ($note) {
            return [
                'id' => $note->id,
                'title' => $note->title,
                'description' => $note->description,
                'is_favorite' => $note->is_favorite,
                'tag_id' => $note->tag_id,
                'tag' => $note->tag ? $note->tag->name : null,
                'tag_color' => $note->tag ? $note->tag->color : null,
                'date' => optional($note->created_at)->format('d/m/Y'),
                'user' => $note->user->name
            ];
        }),
        'tags' => $tags
    ]);
}

    // Mostrar formulario para crear nueva nota
    public function create()
    {
        $tags = Auth::user()->tags;
        return view('notes.create', compact('tags'));
    }

    // Guardar nueva nota
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tag_id' => 'nullable|exists:tags,id',
            'is_favorite' => 'boolean'
        ]);

        $note = Auth::user()->notes()->create([
            'title' => $request->title,
            'description' => $request->description,
            'tag_id' => $request->tag_id,
            'is_favorite' => $request->boolean('is_favorite', false)
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Nota creada exitosamente',
                'note' => [
                    'id' => $note->id,
                    'title' => $note->title,
                    'description' => $note->description,
                    'is_favorite' => $note->is_favorite,
                    'tag' => $note->tag_name,
                    'tag_color' => $note->tag_color,
                    'date' => $note->formatted_date,
                    'user' => $note->user->name,
                ]
            ]);
        }

        return redirect()->route('notes.index')
            ->with('success', 'Nota creada exitosamente');
    }

    // Mostrar una nota específica
    public function show(Note $note)
    {
        $this->authorize('view', $note);
        
        if (request()->wantsJson()) {
            return response()->json([
                'id' => $note->id,
                'title' => $note->title,
                'description' => $note->description,
                'is_favorite' => $note->is_favorite,
                'tag' => $note->tag_name,
                'tag_color' => $note->tag_color,
                'date' => $note->formatted_date,
                'user' => $note->user->name,
            ]);
        }

        return view('notes.show', compact('note'));
    }

    // Mostrar formulario para editar nota
    public function edit(Note $note)
    {
        $this->authorize('update', $note);
        $tags = Auth::user()->tags;
        return view('notes.edit', compact('note', 'tags'));
    }

    // Actualizar nota
    public function update(Request $request, Note $note)
    {
        $this->authorize('update', $note);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tag_id' => 'nullable|exists:tags,id',
            'is_favorite' => 'boolean'
        ]);

        $note->update([
            'title' => $request->title,
            'description' => $request->description,
            'tag_id' => $request->tag_id,
            'is_favorite' => $request->boolean('is_favorite', false)
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Nota actualizada exitosamente',
                'note' => [
                    'id' => $note->id,
                    'title' => $note->title,
                    'description' => $note->description,
                    'is_favorite' => $note->is_favorite,
                    'tag' => $note->tag_name,
                    'tag_color' => $note->tag_color,
                    'date' => $note->formatted_date,
                    'user' => $note->user->name,
                ]
            ]);
        }

        return redirect()->route('notes.index')
            ->with('success', 'Nota actualizada exitosamente');
    }

    // Eliminar nota
    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);

        $note->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Nota eliminada exitosamente'
            ]);
        }

        return redirect()->route('notes.index')
            ->with('success', 'Nota eliminada exitosamente');
    }

public function toggleFavorite(Note $note)
{
    $this->authorize('update', $note);

    // Actualiza y obtiene el nuevo estado inmediatamente
    $updated = $note->update(['is_favorite' => !$note->is_favorite]);
    $note->refresh(); // Asegura los datos actualizados

    if (request()->wantsJson()) {
        return response()->json([
            'success' => true,
            'is_favorite' => (bool)$note->is_favorite, // Forzamos booleano
            'message' => $note->is_favorite ? 'Agregado a favoritos' : 'Removido de favoritos',
            'note_id' => $note->id // Para identificar la nota en frontend
        ]);
    }

    return back()->with('success', 
        $note->is_favorite ? 'Agregado a favoritos' : 'Removido de favoritos'
    );
}

    // Cambiar tag de la nota
    public function updateTag(Request $request, Note $note)
    {
        $this->authorize('update', $note);

        $request->validate([
            'tag_id' => 'nullable|exists:tags,id'
        ]);

        $note->update(['tag_id' => $request->tag_id]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tag actualizado exitosamente',
                'tag' => $note->tag_name,
                'tag_color' => $note->tag_color
            ]);
        }

        return back()->with('success', 'Tag actualizado exitosamente');
    }


    // En el modelo Note
public function scopeByTag($query, $tagIdentifier)
{
    // Si es numérico, asumimos que es ID, si no, es nombre
    if (is_numeric($tagIdentifier)) {
        return $query->where('tag_id', $tagIdentifier);
    } else {
        return $query->whereHas('tag', function($q) use ($tagIdentifier) {
            $q->where('name', $tagIdentifier);
        });
    }
}
}