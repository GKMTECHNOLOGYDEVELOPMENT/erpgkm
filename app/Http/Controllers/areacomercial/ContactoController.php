<?php
namespace App\Http\Controllers\areacomercial;
use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Contactos;
use App\Models\NivelDecision;
use App\Models\Seguimiento;
use App\Models\Tipodocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactoController extends Controller
{
 public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'tipo_documento' => 'required|string',
            'numero_documento' => 'required|string|unique:contactos,numero_documento',
            'nombre_completo' => 'required|string|max:255',
            'cargo' => 'nullable|string|max:255',
            'correo' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'nivel_decision' => 'nullable|exists:niveles_decision,id'
        ]);

        $contacto = Contactos::create([
            'tipo_documento' => $validated['tipo_documento'],
            'numero_documento' => $validated['numero_documento'],
            'nombre_completo' => $validated['nombre_completo'],
            'cargo' => $validated['cargo'],
            'correo_electronico' => $validated['correo'],
            'telefono_whatsapp' => $validated['telefono'],
            'nivel_decision_id' => $validated['nivel_decision'] // Mapeado correctamente
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

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error de validación',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Error al crear contacto: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al crear contacto: ' . $e->getMessage()
        ], 500);
    }
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


// En tu ContactoController
public function formmul(Request $request)
{
    $contactoId = $request->query('contactoId');
    $contacto = $contactoId ? Contactos::find($contactoId) : null;
    
    return view('areacomercial.partials.contacto-form', [
        'contacto' => $contacto,
        'documentos' => Tipodocumento::all(),
        'niveles' => NivelDecision::all()
    ]);
}

public function listmul()
{
    // Asumiendo que tienes una manera de obtener los contactos del seguimiento actual
    $contactos = Contactos::where(...)->get(); // Ajusta esta consulta
    
    return response()->json($contactos);
}

public function storemul(Request $request)
{
    // Validación y creación del contacto
    // No olvides asociarlo al seguimiento actual
}

public function updatemul(Request $request, Contactos $contacto)
{
    // Validación y actualización del contacto
}

public function destroymul(Contactos $contacto)
{
    // Eliminar el contacto
}

}