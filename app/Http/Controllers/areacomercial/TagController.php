<?php

namespace App\Http\Controllers\areacomercial;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Mostrar todos los tags del usuario (API)
    public function index()
    {
        $tags = Auth::user()->tags()->withCount('notes')->get();
        return response()->json(['tags' => $tags]);
    }

    // Guardar nuevo tag (API)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tags')->where('user_id', Auth::id())
            ],
            'color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'description' => 'nullable|string'
        ]);

        $tag = Auth::user()->tags()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tag creado exitosamente',
            'tag' => $tag
        ], 201);
    }

    // Actualizar tag (API)
    public function update(Request $request, Tag $tag)
    {
        // Autorización automática gracias al modelo policy
        $this->authorize('update', $tag);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tags')->where('user_id', Auth::id())->ignore($tag->id)
            ],
            'color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'description' => 'nullable|string'
        ]);

        $tag->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tag actualizado exitosamente',
            'tag' => $tag
        ]);
    }

    // Eliminar tag
    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);

        $tag->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tag eliminado exitosamente'
        ]);
    }
}