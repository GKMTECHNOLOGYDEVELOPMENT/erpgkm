<?php
namespace App\Http\Controllers\areacomercial;
use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Contactos;
use App\Models\Seguimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactoController extends Controller
{
  public function store(Request $request)
{
    $request->validate([
        'tipo_documento' => 'required|string',
        'numero_documento' => 'required|string',
        'nombre_completo' => 'required|string',
        'cargo' => 'nullable|string',
        'correo' => 'nullable|email',
        'telefono' => 'nullable|string',
        'nivel_decision_id' => 'nullable|exists:niveles_decision,id'
    ]);

    $contacto = Contactos::create([
    'tipo_documento' => $request->tipo_documento,
    'numero_documento' => $request->numero_documento,
    'nombre_completo' => $request->nombre_completo,
    'cargo' => $request->cargo,
    'correo_electronico' => $request->correo,
    'telefono_whatsapp' => $request->telefono,
    'nivel_decision_id' => $request->nivel_decision_id
]);

$seguimiento = Seguimiento::create([
    'idContacto' => $contacto->id,
    'idUsuario' => auth()->id(),
    'tipoRegistro' => 2,
    'fechaIngreso' => now(),
]);

return response()->json([
    'success' => true,
    'contacto' => $contacto,
    'idSeguimiento' => $seguimiento->idSeguimiento
]);
}


public function update(Request $request, $id)
{
    try {
        $validated = $request->validate([
            'tipo_documento' => 'required|exists:tipodocumento,idTipoDocumento',
            'numero_documento' => 'required|string|max:50',
            'nombre_completo' => 'required|string|max:255',
            'cargo' => 'nullable|string|max:255',
            'correo' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'nivel_decision' => 'nullable|exists:niveles_decision,id',
        ]);

        $contacto = Contactos::findOrFail($id);
        
        // Mapea los campos correctamente según tu modelo
        $contacto->update([
            'tipo_documento' => $validated['tipo_documento'],
            'numero_documento' => $validated['numero_documento'],
            'nombre_completo' => $validated['nombre_completo'],
            'cargo' => $validated['cargo'],
            'correo_electronico' => $validated['correo'], // Nota el cambio aquí
            'telefono_whatsapp' => $validated['telefono'], // Y aquí
            'nivel_decision_id' => $validated['nivel_decision'], // Y aquí
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contacto actualizado correctamente.',
            'data' => $contacto->fresh()
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error de validación',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Error al actualizar contacto: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el contacto: ' . $e->getMessage()
        ], 500);
    }
}

}