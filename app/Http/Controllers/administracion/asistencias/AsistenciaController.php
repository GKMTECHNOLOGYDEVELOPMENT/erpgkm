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
        $search = $request->input('search.value');

        $usuarios = \App\Models\Usuario::where('estado', 1)
            ->select('idUsuario', 'Nombre', 'apellidoPaterno', 'apellidoMaterno')
            ->get();

        $usuarioIds = $usuarios->pluck('idUsuario');

        $asistencias = Asistencia::whereIn('idUsuario', $usuarioIds)
            ->whereBetween('fechaHora', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->get();

        $observaciones = Observacion::with('anexos')
            ->whereIn('idUsuario', $usuarioIds)
            ->whereBetween('fechaHora', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->get()
            ->groupBy('idUsuario');

        $agrupadas = $asistencias->groupBy(fn($a) => $a->idUsuario . '-' . Carbon::parse($a->fechaHora)->format('Y-m-d'));

        $datos = [];

        foreach ($usuarios as $usuario) {
            $tieneHistorial = Observacion::where('idUsuario', $usuario->idUsuario)->exists();
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

                $obsDelDiaTodas = $obsPorDia[$fecha->format('Y-m-d')] ?? collect();
                $obsAprobada = $obsDelDiaTodas->firstWhere('estado', 1);
                $obsPendiente = $obsDelDiaTodas->firstWhere('estado', 0);
                $obsDenegada = $obsDelDiaTodas->firstWhere('estado', 2);
                $obsFinal = $obsAprobada ?? $obsPendiente ?? $obsDenegada;

                if ($obsFinal === $obsAprobada) {
                    $obsTotal = 1;
                    $obsIndex = 1;
                } else {
                    $obsTotal = $obsDelDiaTodas->count();
                    $obsIndex = $obsDelDiaTodas->search(fn($o) => $o->idObservaciones === $obsFinal?->idObservaciones) + 1;
                }

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
                    'tiene_historial' => $tieneHistorial,
                    'idUsuario' => $usuario->idUsuario,
                    'observacion' => $obsFinal ? [
                        'idObservaciones' => $obsFinal->idObservaciones,
                        'mensaje' => $obsFinal->mensaje,
                        'estado' => $obsFinal->estado,
                        'idTipoAsunto' => $obsFinal->idTipoAsunto,
                        'fechaHora' => $obsFinal->fechaHora, // 👈 agregado
                        'ubicacion' => $obsFinal->ubicacion, // 👈 agregado
                        'lat' => $obsFinal->lat,             // 👈 agregado
                        'lng' => $obsFinal->lng,             // 👈 agregado
                        'total' => $obsTotal,
                        'index' => $obsIndex
                    ] : null
                ];
            }
        }

        $filtrados = collect($datos)->filter(function ($item) use ($search) {
            if (!$search) return true;

            $valores = [
                $item['empleado'],
                $item['fecha'],
                $item['entrada'],
                $item['inicio_break'],
                $item['fin_break'],
                $item['salida'],
                $item['ubicacion_entrada'],
                $item['ubicacion_salida'],
                $item['asistencia'],
                $item['estado_entrada'],
                isset($item['observacion']) ? (
                    ($item['observacion']['estado'] === 1) ? 'APROBADO' : (($item['observacion']['estado'] === 2) ? 'DENEGADO' : 'OBSERVACIÓN')
                ) : '',
                $item['observacion']['mensaje'] ?? ''
            ];

            return collect($valores)->some(function ($valor) use ($search) {
                return str_contains(strtolower(strval($valor)), strtolower($search));
            });
        });



        $sorted = $filtrados->sortBy([['asistencia', 'desc'], fn($a, $b) => strtotime($b['entrada'] ?? '') <=> strtotime($a['entrada'] ?? '')])->values();

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => count($datos),
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
    public function verHistorialUsuario($idUsuario)
    {
        $usuario = \App\Models\Usuario::findOrFail($idUsuario);

        $observaciones = \App\Models\Observacion::with('anexos')
            ->where('idUsuario', $idUsuario)
            ->orderBy('fechaHora', 'desc')
            ->get();

        return view('administracion.asistencia.historial', compact('usuario', 'observaciones'));
    }
}
