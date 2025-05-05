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
    
        $usuarios = \App\Models\Usuario::where('estado', 1)->get();
        $asistencias = \App\Models\Asistencia::whereBetween('fechaHora', [
            $fechaInicio . ' 00:00:00',
            $fechaFin . ' 23:59:59'
        ])->get();
    
        $agrupadas = $asistencias->groupBy(function ($a) {
            return $a->idUsuario . '-' . \Carbon\Carbon::parse($a->fechaHora)->format('Y-m-d');
        });
    
        $datos = [];
    
        foreach ($usuarios as $usuario) {
            $periodo = \Carbon\CarbonPeriod::create($fechaInicio, $fechaFin);
    
            // Agrupar por semana
            $semanas = collect($periodo)->groupBy(function ($fecha) {
                return $fecha->copy()->startOfWeek()->format('Y-m-d');
            });
    
            foreach ($semanas as $diasSemana) {
                $registroSemanal = [];
                $conteoAmarillos = 0;
                $indicesAmarillos = [];
    
                // 1. Recorrer y marcar entradas
                foreach ($diasSemana as $fecha) {
                    $key = $usuario->idUsuario . '-' . $fecha->format('Y-m-d');
                    $grupo = $agrupadas[$key] ?? collect();
                    $entrada = $grupo->firstWhere('idTipoHorario', 1);
    
                    $estadoColor = null;
                    $esAmarillo = false;
                    $horaEntrada = null;
    
                    if ($entrada) {
                        $horaEntrada = \Carbon\Carbon::parse($entrada->fechaHora);
                    
                        if ($fecha->isSunday()) {
                            $estadoColor = 'azul'; // 👈 Siempre azul los domingos
                        } else {
                            $limiteAzul = $fecha->isSaturday()
                                ? $horaEntrada->copy()->setTime(9, 0, 59)
                                : $horaEntrada->copy()->setTime(8, 0, 59);
                    
                            $limiteAmarillo = $fecha->isSaturday()
                                ? $horaEntrada->copy()->setTime(9, 5, 59)
                                : $horaEntrada->copy()->setTime(8, 5, 59);
                    
                            if ($horaEntrada->lte($limiteAzul)) {
                                $estadoColor = 'azul';
                            } elseif ($horaEntrada->lte($limiteAmarillo)) {
                                $estadoColor = 'amarillo';
                                $esAmarillo = true;
                                $conteoAmarillos++;
                                $indicesAmarillos[] = count($registroSemanal);
                            } else {
                                $estadoColor = 'rojo';
                            }
                        }
                    }
                    
    
                    $registroSemanal[] = [
                        'empleado' => strtoupper(trim($usuario->Nombre . ' ' . $usuario->apellidoPaterno . ' ' . $usuario->apellidoMaterno)),
                        'fecha' => $fecha->format('Y-m-d'),
                        'entrada' => $entrada?->fechaHora ? $horaEntrada->format('h:i A') : null,
                        'ubicacion_entrada' => $entrada?->ubicacion,
                        'inicio_break' => $grupo->firstWhere('idTipoHorario', 2)?->fechaHora ? \Carbon\Carbon::parse($grupo->firstWhere('idTipoHorario', 2)->fechaHora)->format('h:i A') : null,
                        'fin_break' => $grupo->firstWhere('idTipoHorario', 3)?->fechaHora ? \Carbon\Carbon::parse($grupo->firstWhere('idTipoHorario', 3)->fechaHora)->format('h:i A') : null,
                        'salida' => $grupo->firstWhere('idTipoHorario', 4)?->fechaHora ? \Carbon\Carbon::parse($grupo->firstWhere('idTipoHorario', 4)->fechaHora)->format('h:i A') : null,
                        'ubicacion_salida' => $grupo->firstWhere('idTipoHorario', 4)?->ubicacion,
                        'asistencia' => $entrada ? 'ASISTIÓ' : 'NO ASISTIÓ',
                        'estado_entrada' => $estadoColor,
                        'es_amarillo' => $esAmarillo
                    ];
                }
    
                // 2. Si hay 3 amarillos, cambiar todos los días NO AMARILLOS ni rojos a rojo
                if ($conteoAmarillos >= 3) {
                    foreach ($registroSemanal as &$registro) {
                        if ($registro['estado_entrada'] === 'azul') {
                            $registro['estado_entrada'] = 'rojo';
                        }
                        unset($registro['es_amarillo']); // limpieza
                        $datos[] = $registro;
                    }
                } else {
                    foreach ($registroSemanal as &$registro) {
                        unset($registro['es_amarillo']);
                        $datos[] = $registro;
                    }
                }
            }
        }    
        $ordenado = collect($datos)->sortBy([
            // 1. Primero los que no asistieron
            ['asistencia', 'desc'], // 'NO ASISTIÓ' > 'ASISTIÓ'
            
            // 2. Luego por entrada: más tarde primero
            function ($a, $b) {
                if ($a['asistencia'] === 'NO ASISTIÓ' && $b['asistencia'] === 'NO ASISTIÓ') return 0;
                if ($a['asistencia'] === 'NO ASISTIÓ') return -1;
                if ($b['asistencia'] === 'NO ASISTIÓ') return 1;
        
                // Ambas asistencias presentes, compara horas
                return strtotime($b['entrada'] ?? '') <=> strtotime($a['entrada'] ?? '');
            }
        ])->values();        
    
        return response()->json(['data' => $ordenado]);
    }   
}
