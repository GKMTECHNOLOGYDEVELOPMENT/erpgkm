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

    // Mostrar todos los tags del usuario
    public function index()
    {
        $tags = Auth::user()->tags()->withCount('notes')->get();

        if (request()->wantsJson()) {
            return response()->json(['tags' => $tags]);
        }

        return view('tags.index', compact('tags'));
    }

    // Mostrar formulario para crear nuevo tag
    public function create()
    {
        return view('tags.create');
    }

    // Guardar nuevo tag
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tags')->where('user_id', Auth::id())
            ],
            'color' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'description' => 'nullable|string'
        ]);

        $tag = Auth::user()->tags()->create([
            'name' => $request->name,
            'color' => $request->color,
            'description' => $request->description
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tag creado exitosamente',
                'tag' => $tag
            ]);
        }

        return redirect()->route('tags.index')
            ->with('success', 'Tag creado exitosamente');
    }

    // Mostrar un tag específico
    public function show(Tag $tag)
    {
        $this->authorize('view', $tag);
        
        $notes = $tag->notes()->where('user_id', Auth::id())->get();

        if (request()->wantsJson()) {
            return response()->json([
                'tag' => $tag,
                'notes' => $notes
            ]);
        }

        return view('tags.show', compact('tag', 'notes'));
    }

    // Mostrar formulario para editar tag
    public function edit(Tag $tag)
    {
        $this->authorize('update', $tag);
        return view('tags.edit', compact('tag'));
    }

    // Actualizar tag
    public function update(Request $request, Tag $tag)
    {
        $this->authorize('update', $tag);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tags')->where('user_id', Auth::id())->ignore($tag->id)
            ],
            'color' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'description' => 'nullable|string'
        ]);

        $tag->update([
            'name' => $request->name,
            'color' => $request->color,
            'description' => $request->description
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tag actualizado exitosamente',
                'tag' => $tag
            ]);
        }

        return redirect()->route('tags.index')
            ->with('success', 'Tag actualizado exitosamente');
    }

    // Eliminar tag
    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);

        $tag->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tag eliminado exitosamente'
            ]);
        }

        return redirect()->route('tags.index')
            ->with('success', 'Tag eliminado exitosamente');
    }
}