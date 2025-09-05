<?php

namespace App\Http\Controllers\areacomercial;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Seguimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    // Log de entrada
    Log::info('Iniciando búsqueda de RUC', [
        'ruc' => $ruc,
        'url' => $url,
        'token_present' => !empty($token), // Para no mostrar el token directamente
    ]);

    if (!$url || !$token) {
        Log::error('Faltan configuraciones de la API RUC', [
            'url' => $url,
            'token' => $token,
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Faltan configuraciones de la API RUC (url o token)'
        ], 500);
    }

    try {
        $response = Http::post($url, [
            'ruc' => $ruc,
            'token' => $token
        ]);

        $data = $response->json();

        Log::info('Respuesta de la API RUC', ['response' => $data]);

        if ($data['success'] && isset($data['result'])) {
            $empresa = $data['result'];

            return response()->json([
                'success' => true,
                'razon_social' => $empresa['social_reason'] ?? '',
                'direccion' => $empresa['address'] ?? '',
                'rubro' => $empresa['economic_activity'] ?? '',
            ]);
        }

        Log::warning('No se encontraron datos para el RUC', ['ruc' => $ruc]);

        return response()->json([
            'success' => false,
            'message' => 'No se encontraron datos para este RUC'
        ]);
    } catch (\Exception $e) {
        Log::error('Error al consultar la API RUC', [
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Ocurrió un error al consultar el RUC'
        ], 500);
    }
}



    public function update(Request $request, $id)
    {
        // Validar datos
        $request->validate([
            'razon_social' => 'required|string|max:255',
            'ruc' => 'required|string|max:20',
            'rubro' => 'required|string|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'fuente_captacion_id' => 'required|exists:fuentes_captacion,id',
        ]);

        $empresa = Empresa::findOrFail($id);

        // Actualizar campos
        $empresa->nombre_razon_social = $request->razon_social;
        $empresa->ruc = $request->ruc;
        $empresa->giro_comercial = $request->rubro;
        $empresa->ubicacion_geografica = $request->ubicacion;
        $empresa->fuente_captacion_id = $request->fuente_captacion_id;

        $empresa->save();

        // Retornar respuesta JSON
        if ($request->ajax()) {
            return response()->json([
                'message' => 'Empresa actualizada correctamente',
                'empresa' => $empresa,
            ]);
        }

        return redirect()->back()->with('success', 'Empresa actualizada correctamente');
    }
}
