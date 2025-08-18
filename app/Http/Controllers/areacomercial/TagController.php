<?php

namespace App\Http\Controllers\areacomercial;

use App\Http\Controllers\Controller;
use App\Models\SeleccionarSeguimiento;
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

 public function index(Request $request)
{
    $idSeguimiento = $request->get('idseguimiento');
    $idPersona = $request->get('idpersona');

    $query = Auth::user()->tags()->withCount('notes');

    if ($idSeguimiento) {
        $query->where('idseguimiento', $idSeguimiento);
    }

    if ($idPersona) {
        $query->where('idpersona', $idPersona);
    }

    $tags = $query->get();

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
        'description' => 'nullable|string',
        'idseguimiento' => 'required|integer'
    ]);

    // Obtener idpersona relacionado al seguimiento
    $idPersona = SeleccionarSeguimiento::where('idseguimiento', $validated['idseguimiento'])->value('idpersona');
    if (!$idPersona) {
        return response()->json([
            'success' => false,
            'message' => 'No se encontró idpersona para este seguimiento'
        ], 400);
    }

    // Crear el tag incluyendo idpersona
    $tag = Auth::user()->tags()->create([
        ...$validated,
        'idpersona' => $idPersona
    ]);

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