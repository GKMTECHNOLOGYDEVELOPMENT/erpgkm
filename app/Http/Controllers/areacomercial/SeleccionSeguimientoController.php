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
    $request->validate([
        'idseguimiento'   => 'required|exists:seguimientos,idSeguimiento',
        'idprospecto'     => 'required|integer',
        'tipo_prospecto'  => 'required|in:empresa,contacto',
        'idpersona'       => 'required|integer'
    ]);

    // Registrar la selección
    $seleccion = SeleccionarSeguimiento::updateOrCreate(
        ['idseguimiento' => $request->idseguimiento],
        [
            'idprospecto'       => $request->idprospecto,
            'idusuario'         => Auth::id(),
            'idpersona'         => $request->idpersona,
            'fecha_seleccionada'=> now()
        ]
    );

    // Definir los estados que deben crearse POR PERSONA
    $estadosRequeridos = ['Lista de proyectos', 'cotizacion', 'reunion', 'levantamiento', 'observado', 'ganado', 'rechazado'];

    // Verificar y crear los proyectos si no existen PARA ESTA PERSONA
    foreach ($estadosRequeridos as $estado) {
        $proyectoExistente = Project::where('idpersona', $request->idpersona)
            ->where('title', $estado)
            ->first();

        if (!$proyectoExistente) {
            Project::create([
                'title' => $estado,
                'idseguimiento' => $request->idseguimiento,
                'idpersona' => $request->idpersona
            ]);
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Selección guardada correctamente y proyectos verificados para la persona',
        'data'    => $seleccion
    ]);
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