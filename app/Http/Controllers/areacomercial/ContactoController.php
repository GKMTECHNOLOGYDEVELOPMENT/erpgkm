<?php
namespace App\Http\Controllers\areacomercial;
use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Contactos;
use App\Models\Seguimiento;
use Illuminate\Http\Request;

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



public function update(Request $request, Contacto $contacto)
{
    $validated = $request->validate([
        'tipo_documento' => 'required|exists:tipos_documento,id',
        'numero_documento' => 'required|string|max:20',
        'nombre_completo' => 'required|string|max:255',
        'cargo' => 'nullable|string|max:100',
        'correo' => 'nullable|email|max:100',
        'telefono' => 'nullable|string|max:20',
        'nivel_decision' => 'nullable|exists:niveles_decision,id',
    ]);

    $contacto->update($validated);

    return redirect()->route('seguimientos.edit', $request->idSeguimiento)
        ->with('success', 'Contacto actualizado correctamente');
}

}