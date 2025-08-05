<?php
namespace App\Http\Controllers\areacomercial;
use App\Http\Controllers\Controller;
use App\Models\Contactos;
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

    return response()->json(['success' => true, 'contacto' => $contacto]);
}

}