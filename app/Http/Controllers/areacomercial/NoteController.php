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

    // Mostrar todas las notas del usuario
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');
        
        $query = $user->notes()->with('tag');

        switch ($filter) {
            case 'favorites':
                $query->where('is_favorite', true);
                break;
            case 'personal':
            case 'work':
            case 'social':
            case 'important':
                $query->byTag($filter);
                break;
            default:
                // 'all' - no filtro adicional
                break;
        }

        $notes = $query->orderBy('created_at', 'desc')->get();
        $tags = $user->tags;

        if ($request->wantsJson()) {
            return response()->json([
                'notes' => $notes->map(function ($note) {
                    return [
                        'id' => $note->id,
                        'title' => $note->title,
                        'description' => $note->description,
                        'is_favorite' => $note->is_favorite,
                        'tag' => $note->tag_name,
                        'tag_color' => $note->tag_color,
                        'date' => $note->formatted_date,
                        'user' => $note->user->name,
                    ];
                }),
                'tags' => $tags
            ]);
        }

        return view('notes.index', compact('notes', 'tags', 'filter'));
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

    // Alternar favorito
    public function toggleFavorite(Note $note)
    {
        $this->authorize('update', $note);

        $note->update(['is_favorite' => !$note->is_favorite]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_favorite' => $note->is_favorite,
                'message' => $note->is_favorite ? 'Agregado a favoritos' : 'Removido de favoritos'
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
}