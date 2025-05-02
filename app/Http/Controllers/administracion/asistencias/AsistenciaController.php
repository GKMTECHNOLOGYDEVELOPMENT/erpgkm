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
            $conteoAmarillosSemana = 0;
            $banderaDesdeHoyTodoRojo = false;

            foreach ($periodo as $fecha) {
                $key = $usuario->idUsuario . '-' . $fecha->format('Y-m-d');
                $grupo = $agrupadas[$key] ?? collect();
                $entrada = $grupo->firstWhere('idTipoHorario', 1);

                $estadoColor = null;
                if ($entrada) {
                    $horaEntrada = \Carbon\Carbon::parse($entrada->fechaHora);
                    $limiteAzul = $horaEntrada->copy()->setTime(8, 0, 59);
                    $limiteAmarillo = $horaEntrada->copy()->setTime(8, 5, 59);

                    if ($banderaDesdeHoyTodoRojo) {
                        $estadoColor = 'rojo';
                    } else {
                        if ($horaEntrada->lte($limiteAzul)) {
                            $estadoColor = 'azul';
                        } elseif ($horaEntrada->lte($limiteAmarillo)) {
                            $conteoAmarillosSemana++;
                            $estadoColor = 'amarillo';

                            if ($conteoAmarillosSemana == 2) {
                                $banderaDesdeHoyTodoRojo = true;
                            }
                        } else {
                            $estadoColor = 'rojo';
                        }
                    }
                }

                $datos[] = [
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
                    'idUsuario' => $usuario->idUsuario,
                ];
            }
        }

        $ordenado = collect($datos)->sortByDesc(function ($item) {
            return $item['asistencia'] === 'NO ASISTIÓ';
        })->values();

        return response()->json(['data' => $ordenado]);
    }
}
