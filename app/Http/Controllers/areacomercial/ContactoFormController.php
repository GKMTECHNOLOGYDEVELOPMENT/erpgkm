<?php
namespace App\Http\Controllers\areacomercial;
use App\Http\Controllers\Controller;
use App\Models\Contactosform;
use App\Models\Empresa;
use App\Models\Seguimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContactoFormController extends Controller
{
public function store(Request $request)
    {
        $validated = $request->validate([
            'idSeguimiento' => 'required|integer',
            'tipo_documento_id' => 'nullable|integer',
            'numero_documento' => 'required|string|max:20',
            'nombre_completo' => 'required|string|max:255',
            'cargo' => 'nullable|string|max:100',
            'correo_electronico' => 'nullable|email|max:100',
            'telefono_whatsapp' => 'nullable|string|max:20',
            'nivel_decision_id' => 'nullable|integer'
        ]);

        $contacto = Contactosform::create($validated);

        return response()->json([
            'success' => true,
            'data' => $contacto, 
            'message' => 'Contacto creado exitosamente'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $contacto = Contactosform::findOrFail($id);

        $validated = $request->validate([
            'tipo_documento_id' => 'sometimes|integer',
            'numero_documento' => 'sometimes|string|max:20',
            'nombre_completo' => 'sometimes|string|max:255',
            'cargo' => 'nullable|string|max:100',
            'correo_electronico' => 'nullable|email|max:100',
            'telefono_whatsapp' => 'nullable|string|max:20',
            'nivel_decision_id' => 'nullable|integer'
        ]);

        $contacto->update($validated);

        return response()->json([
            'success' => true,
            'data' => $contacto,
            'message' => 'Contacto actualizado exitosamente'
        ]);
    }

    public function destroy($id)
    {
        $contacto = Contactosform::findOrFail($id);
        $contacto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contacto eliminado exitosamente'
        ]);
    }


    public function getBySeguimiento($idSeguimiento)
{
    $contactos = ContactosForm::where('idSeguimiento', $idSeguimiento)->get();
    return response()->json($contactos);
}

}