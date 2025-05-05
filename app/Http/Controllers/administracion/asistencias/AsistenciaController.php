<?php

namespace App\Http\Controllers\administracion\asistencias;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Asistencia;
use App\Models\Observacion;
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
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);

        // Obtener IDs de usuarios activos
        $usuarios = \App\Models\Usuario::where('estado', 1)
            ->select('idUsuario', 'Nombre', 'apellidoPaterno', 'apellidoMaterno')
            ->get();

        $usuarioIds = $usuarios->pluck('idUsuario');

        // Cargar asistencias solo de usuarios activos
        $asistencias = Asistencia::whereIn('idUsuario', $usuarioIds)
            ->whereBetween('fechaHora', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->get();

        // Cargar observaciones solo de usuarios activos
        $observaciones = Observacion::with('anexos')
            ->whereIn('idUsuario', $usuarioIds)
            ->whereBetween('fechaHora', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->get()
            ->groupBy('idUsuario');

        $agrupadas = $asistencias->groupBy(fn($a) => $a->idUsuario . '-' . Carbon::parse($a->fechaHora)->format('Y-m-d'));

        $datos = [];

        foreach ($usuarios as $usuario) {
            $periodo = Carbon::parse($fechaInicio)->toPeriod(Carbon::parse($fechaFin));
            $obsUsuario = $observaciones[$usuario->idUsuario] ?? collect();
            $obsPorDia = $obsUsuario->groupBy(fn($obs) => Carbon::parse($obs->fechaHora)->format('Y-m-d'));

            foreach ($periodo as $fecha) {
                $key = $usuario->idUsuario . '-' . $fecha->format('Y-m-d');
                $grupo = $agrupadas[$key] ?? collect();
                $entrada = $grupo->firstWhere('idTipoHorario', 1);

                $estadoColor = null;
                $horaEntrada = null;

                if ($entrada) {
                    $horaEntrada = Carbon::parse($entrada->fechaHora);

                    $limiteAzul = $fecha->isSaturday() ? $horaEntrada->copy()->setTime(9, 0, 59) : $horaEntrada->copy()->setTime(8, 0, 59);
                    $limiteAmarillo = $fecha->isSaturday() ? $horaEntrada->copy()->setTime(9, 5, 59) : $horaEntrada->copy()->setTime(8, 5, 59);

                    if ($fecha->isSunday() || $horaEntrada->lte($limiteAzul)) {
                        $estadoColor = 'azul';
                    } elseif ($horaEntrada->lte($limiteAmarillo)) {
                        $estadoColor = 'amarillo';
                    } else {
                        $estadoColor = 'rojo';
                    }
                }

                $obsDelDia = $obsPorDia[$fecha->format('Y-m-d')][0] ?? null;

                $datos[] = [
                    'empleado' => strtoupper(trim("{$usuario->Nombre} {$usuario->apellidoPaterno} {$usuario->apellidoMaterno}")),
                    'fecha' => $fecha->format('Y-m-d'),
                    'entrada' => $entrada?->fechaHora ? $horaEntrada->format('h:i A') : null,
                    'ubicacion_entrada' => $entrada?->ubicacion,
                    'inicio_break' => $grupo->firstWhere('idTipoHorario', 2)?->fechaHora ? Carbon::parse($grupo->firstWhere('idTipoHorario', 2)->fechaHora)->format('h:i A') : null,
                    'fin_break' => $grupo->firstWhere('idTipoHorario', 3)?->fechaHora ? Carbon::parse($grupo->firstWhere('idTipoHorario', 3)->fechaHora)->format('h:i A') : null,
                    'salida' => $grupo->firstWhere('idTipoHorario', 4)?->fechaHora ? Carbon::parse($grupo->firstWhere('idTipoHorario', 4)->fechaHora)->format('h:i A') : null,
                    'ubicacion_salida' => $grupo->firstWhere('idTipoHorario', 4)?->ubicacion,
                    'asistencia' => $entrada ? 'ASISTIÓ' : 'NO ASISTIÓ',
                    'estado_entrada' => $estadoColor,
                    'observacion' => $obsDelDia ? [
                        'idObservaciones' => $obsDelDia->idObservaciones,
                        'mensaje' => $obsDelDia->mensaje,
                        'estado' => $obsDelDia->estado,
                        'idTipoAsunto' => $obsDelDia->idTipoAsunto
                    ] : null
                ];
            }
        }

        $sorted = collect($datos)->sortBy([['asistencia', 'desc'], fn($a, $b) => strtotime($b['entrada'] ?? '') <=> strtotime($a['entrada'] ?? '')])->values();

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $sorted->count(),
            'recordsFiltered' => $sorted->count(),
            'data' => $sorted->slice($start)->take($length)->values()
        ]);
    }

    public function actualizarEstadoObservacion(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:observaciones,idObservaciones',
            'estado' => 'required|in:1,2'
        ]);

        $observacion = \App\Models\Observacion::findOrFail($request->id);
        $observacion->estado = $request->estado;
        $observacion->save();

        return response()->json(['success' => true]);
    }
    public function obtenerImagenesObservacion($id)
    {
        $observacion = Observacion::with('anexos')->findOrFail($id);

        return response()->json([
            'imagenes' => $observacion->anexos->map(fn($a) => base64_encode($a->foto))
        ]);
    }
}
