<?php
namespace App\Http\Controllers\areacomercial;
use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Seguimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EmpresaController extends Controller
{
  public function store(Request $request)
{
    $request->validate([
        'razon_social' => 'required|string|max:255',
        'ruc' => 'required|string|max:20|unique:empresas,ruc',
        'rubro' => 'required|string|max:255',
        'ubicacion' => 'nullable|string|max:255',
        'fuente_captacion_id' => 'required|exists:fuentes_captacion,id'
    ]);

    $empresa = Empresa::create([
    'nombre_razon_social' => $request->razon_social,
    'ruc' => $request->ruc,
    'giro_comercial' => $request->rubro,
    'ubicacion_geografica' => $request->ubicacion,
    'fuente_captacion_id' => $request->fuente_captacion_id
]);

$seguimiento = Seguimiento::create([
    'idEmpresa' => $empresa->id,
    'idUsuario' => auth()->id(),
    'tipoRegistro' => 1,
    'fechaIngreso' => now(),
]);

return response()->json([
    'success' => true,
    'empresa' => $empresa,
    'idSeguimiento' => $seguimiento->idSeguimiento
]);
}

public function buscarRuc(Request $request)
{
    $ruc = $request->input('ruc');
    $url = config('services.ruc_api.url');
    $token = config('services.ruc_api.token');

    $response = Http::post($url, [
        'ruc' => $ruc,
        'token' => $token
    ]);

    $data = $response->json();

    if ($data['success'] && isset($data['result'])) {
        $empresa = $data['result'];

        return response()->json([
            'success' => true,
            'razon_social' => $empresa['social_reason'] ?? '',
            'direccion' => $empresa['address'] ?? '',
            'rubro' => $empresa['economic_activity'] ?? '', // <- esto es lo nuevo

        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'No se encontraron datos para este RUC'
    ]);
}


public function update(Request $request, Empresa $empresa)
{
    $validated = $request->validate([
        'razon_social' => 'required|string|max:255',
        'ruc' => 'required|string|max:20',
        'rubro' => 'required|string|max:255',
        'ubicacion' => 'nullable|string|max:255',
        'fuente_captacion_id' => 'required|exists:fuentes_captacion,id',
    ]);

    $empresa->update($validated);

    return redirect()->route('areacomercial.seguimiento', $request->idSeguimiento)
        ->with('success', 'Empresa actualizada correctamente');
}


}