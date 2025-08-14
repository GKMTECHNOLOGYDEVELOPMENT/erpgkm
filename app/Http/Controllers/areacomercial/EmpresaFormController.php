<?php
namespace App\Http\Controllers\areacomercial;
use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Empresasform;
use App\Models\Seguimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EmpresaFormController extends Controller
{
 public function store(Request $request)
    {
        $validated = $request->validate([
            'idSeguimiento' => 'required|integer',
            'nombre_razon_social' => 'required|string|max:255',
            'ruc' => 'required|string|max:20',
            'giro_comercial' => 'required|string|max:255',
            'ubicacion_geografica' => 'nullable|string|max:255',
            'fuente_captacion_id' => 'required|integer'
        ]);

        $empresa = Empresasform::create($validated);

        return response()->json([
            'success' => true,
            'data' => $empresa,
            'message' => 'Empresa creada exitosamente'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $empresa = Empresasform::findOrFail($id);

        $validated = $request->validate([
            'nombre_razon_social' => 'sometimes|string|max:255',
            'ruc' => 'sometimes|string|max:20',
            'giro_comercial' => 'sometimes|string|max:255',
            'ubicacion_geografica' => 'nullable|string|max:255',
            'fuente_captacion_id' => 'sometimes|integer'
        ]);

        $empresa->update($validated);

        return response()->json([
            'success' => true,
            'data' => $empresa,
            'message' => 'Empresa actualizada exitosamente'
        ]);
    }

    public function destroy($id)
    {
        $empresa = Empresasform::findOrFail($id);
        $empresa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Empresa eliminada exitosamente'
        ]);
    }


    public function getBySeguimiento($idSeguimiento)
{
    $empresas = Empresasform::where('idSeguimiento', $idSeguimiento)->get();
    return response()->json($empresas);
}

}