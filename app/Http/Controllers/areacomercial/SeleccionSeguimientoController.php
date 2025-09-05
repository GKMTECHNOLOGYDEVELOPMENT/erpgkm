<?php
namespace App\Http\Controllers\areacomercial;
use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Contactos;
use App\Models\NivelDecision;
use App\Models\Project;
use App\Models\Seguimiento;
use App\Models\SeleccionarSeguimiento;
use App\Models\Tipodocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SeleccionSeguimientoController extends Controller
{
public function store(Request $request)
{
    Log::info('Iniciando método store en SeleccionarSeguimientoController', [
        'request' => $request->all(),
        'usuario_id' => Auth::id()
    ]);

    // Validación
    try {
        $request->validate([
            'idseguimiento'   => 'required|exists:seguimientos,idSeguimiento',
            'idprospecto'     => 'required|integer',
            'tipo_prospecto'  => 'required|in:empresa,contacto',
            'idpersona'       => 'required|integer'
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Error de validación en store', [
            'errors' => $e->errors()
        ]);
        throw $e;
    }

    try {
        // Registrar la selección
        $seleccion = SeleccionarSeguimiento::updateOrCreate(
            ['idseguimiento' => $request->idseguimiento],
            [
                'idprospecto'        => $request->idprospecto,
                'idusuario'          => Auth::id(),
                'idpersona'          => $request->idpersona,
                'fecha_seleccionada' => now()
            ]
        );

        Log::info('Selección registrada o actualizada', [
            'seleccion' => $seleccion->toArray()
        ]);

        // Estados requeridos
        $estadosRequeridos = ['Lista de proyectos', 'cotizacion', 'reunion', 'levantamiento', 'observado', 'ganado', 'rechazado'];

        foreach ($estadosRequeridos as $estado) {
            $proyectoExistente = Project::where('idpersona', $request->idpersona)
                ->where('title', $estado)
                ->first();

            if (!$proyectoExistente) {
                $nuevoProyecto = Project::create([
                    'title'         => $estado,
                    'idseguimiento' => $request->idseguimiento,
                    'idpersona'     => $request->idpersona
                ]);

                Log::info("Proyecto creado para estado: {$estado}", [
                    'proyecto' => $nuevoProyecto->toArray()
                ]);
            } else {
                Log::info("Proyecto ya existe para estado: {$estado}", [
                    'proyecto_id' => $proyectoExistente->id
                ]);
            }
        }

        Log::info('Finalización exitosa del método store');

        return response()->json([
            'success' => true,
            'message' => 'Selección guardada correctamente y proyectos verificados para la persona',
            'data'    => $seleccion
        ]);

    } catch (\Exception $e) {
        Log::error('Error en el método store de SeleccionarSeguimientoController', [
            'exception' => $e->getMessage(),
            'trace'     => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error al guardar la selección o crear proyectos.',
            'error'   => $e->getMessage()
        ], 500);
    }
}


public function obtenerSeleccion($idSeguimiento)
{
    $seleccion = SeleccionarSeguimiento::where('idseguimiento', $idSeguimiento)
        ->where('idusuario', Auth::id())
        ->first();

    if (!$seleccion) {
        return response()->json(['success' => false]);
    }

    // Determinar si es empresa o contacto (necesitarás lógica adicional aquí)
    // Por ejemplo, podrías verificar en qué tabla existe el idprospecto
    $tipo = 'contacto'; // O 'empresa' según corresponda

    return response()->json([
        'success' => true,
        'data' => [
            'id' => $seleccion->id,
            'idseguimiento' => $seleccion->idseguimiento,
            'idprospecto' => $seleccion->idprospecto,
            'idpersona' => $seleccion->idpersona,
            'tipo_prospecto' => $tipo // Añadimos este campo
        ]
    ]);
}

}