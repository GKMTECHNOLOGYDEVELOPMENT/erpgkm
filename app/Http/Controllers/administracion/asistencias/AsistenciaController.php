<?php

namespace App\Http\Controllers\administracion\asistencias;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Asistencia;
use App\Models\Usuario;

class AsistenciaController extends Controller
{
    public function index()
    {
        return view('administracion.asistencia.index');
    }


    public function getAsistencias(Request $request)
    {
        $fechaInicio = $request->startDate ?? now()->toDateString();
        $fechaFin = $request->endDate ?? now()->toDateString();
    
        // 1. Traer TODOS los usuarios
        $usuarios = \App\Models\Usuario::where('estado', 1)->get();
    
        // 2. Traer asistencias solo entre fechas
        $asistencias = \App\Models\Asistencia::whereBetween('fechaHora', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])->get();
    
        // 3. Agrupar por idUsuario-fecha
        $agrupadas = $asistencias->groupBy(function ($asistencia) {
            return $asistencia->idUsuario . '-' . \Carbon\Carbon::parse($asistencia->fechaHora)->format('Y-m-d');
        });
    
        $datos = [];
    
        foreach ($usuarios as $usuario) {
            // Iterar fechas entre startDate y endDate
            $periodo = \Carbon\CarbonPeriod::create($fechaInicio, $fechaFin);
            foreach ($periodo as $fecha) {
                $key = $usuario->idUsuario . '-' . $fecha->format('Y-m-d');
                $grupo = $agrupadas[$key] ?? collect();
    
                $entrada = $grupo->firstWhere('idTipoHorario', 1);
                $inicioBreak = $grupo->firstWhere('idTipoHorario', 2);
                $finBreak = $grupo->firstWhere('idTipoHorario', 3);
                $salida = $grupo->firstWhere('idTipoHorario', 4);
    
                $datos[] = [
                    'empleado' => strtoupper(trim($usuario->Nombre . ' ' . $usuario->apellidoPaterno . ' ' . $usuario->apellidoMaterno)),
                    'fecha' => $fecha->format('Y-m-d'),
                    'entrada' => $entrada?->fechaHora ? \Carbon\Carbon::parse($entrada->fechaHora)->format('h:i A') : null,
                    'ubicacion_entrada' => $entrada?->ubicacion,
                    'inicio_break' => $inicioBreak?->fechaHora ? \Carbon\Carbon::parse($inicioBreak->fechaHora)->format('h:i A') : null,
                    'fin_break' => $finBreak?->fechaHora ? \Carbon\Carbon::parse($finBreak->fechaHora)->format('h:i A') : null,
                    'salida' => $salida?->fechaHora ? \Carbon\Carbon::parse($salida->fechaHora)->format('h:i A') : null,
                    'ubicacion_salida' => $salida?->ubicacion,
                    'asistencia' => $entrada ? 'ASISTIÓ' : 'NO ASISTIÓ',
                ];
            }
        }
    
        $ordenado = collect($datos)->sortByDesc(function ($item) {
            return $item['asistencia'] === 'NO ASISTIÓ';
        })->values();
        
        
        return response()->json(['data' => $ordenado]);
    }
    
}
