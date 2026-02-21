<?php

namespace App\Http\Controllers\administracion\horasextras;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\WsBridge;

class HorasExtrasController extends Controller
{
    public function index()
    {
        return view('administracion.horasextras.index');
    }

    public function getHorasExtras(Request $request)
    {
        try {
            $query = DB::table('aprobacion_horas as ah')
                ->join('usuarios as u', 'ah.idUsuario', '=', 'u.idUsuario')
                ->join('asistencias as a', 'ah.idAsistencia', '=', 'a.idAsistencia')
                ->select(
                    'ah.idAprobacion',
                    'ah.idUsuario',
                    'ah.idAsistencia',
                    'ah.fechaHora_original',
                    'ah.tipo_marca',
                    'ah.umbral_minutos',
                    'ah.diferencia_minutos',
                    'ah.fechaHora_modificada',
                    'ah.estado',
                    'ah.comentario',
                    'ah.revisado_por',
                    'ah.revisado_at',
                    'ah.created_at',
                    DB::raw("CONCAT(u.Nombre, ' ', u.apellidoPaterno, ' ', u.apellidoMaterno) as nombre_completo"),
                    'u.sueldoPorHora'
                );

            if ($request->filled('usuario')) {
                $query->where('ah.idUsuario', $request->usuario);
            }

            if ($request->filled('estado')) {
                $query->where('ah.estado', $request->estado);
            }

            if ($request->filled('fecha_desde')) {
                $fechaDesde = Carbon::createFromFormat('d/m/Y', $request->fecha_desde)->startOfDay();
                $query->whereDate('ah.fechaHora_original', '>=', $fechaDesde);
            }

            if ($request->filled('fecha_hasta')) {
                $fechaHasta = Carbon::createFromFormat('d/m/Y', $request->fecha_hasta)->endOfDay();
                $query->whereDate('ah.fechaHora_original', '<=', $fechaHasta);
            }

            $horasExtras = $query->orderBy('ah.fechaHora_original', 'desc')->get();

            $horasExtras = $horasExtras->map(function ($item) {
                $fechaOriginal = Carbon::parse($item->fechaHora_original);

                $horaInicioExtra = $fechaOriginal->copy()->setTime(17, 0, 0);
                $horaFinalExtra = $fechaOriginal;

                $minutosExtras = $item->diferencia_minutos ?? 0;
                $horas = floor($minutosExtras / 60);
                $minutos = $minutosExtras % 60;

                $tiempoFormateado = $horas > 0
                    ? "{$horas}h {$minutos}m"
                    : "{$minutos}m";

                $item->hora_inicio_extra = $horaInicioExtra->format('H:i');
                $item->hora_final_extra = $horaFinalExtra->format('H:i');
                $item->tiempo_extra_formateado = $tiempoFormateado;
                $item->minutos_extras = $minutosExtras;

                return $item;
            });

            return response()->json([
                'success' => true,
                'data' => $horasExtras
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener horas extras: ' . $e->getMessage()
            ], 500);
        }
    }

    public function procesarHorasExtras()
    {
        try {
            DB::beginTransaction();

            $horaUmbral = '17:20:00';

            $asistencias = DB::table('asistencias as a')
                ->leftJoin('aprobacion_horas as ah', 'a.idAsistencia', '=', 'ah.idAsistencia')
                ->where('a.idTipoHorario', 4)
                ->whereTime('a.fechaHora', '>', $horaUmbral)
                ->whereNull('ah.idAprobacion')
                ->select(
                    'a.idAsistencia',
                    'a.idUsuario',
                    'a.fechaHora'
                )
                ->get();

            $registrosCreados = 0;

            foreach ($asistencias as $asistencia) {
                $horaMarcada = Carbon::parse($asistencia->fechaHora);
                $horaSalida = $horaMarcada->copy()->startOfDay()->setTime(17, 0, 0);
                $diferenciaMinutos = $horaSalida->diffInMinutes($horaMarcada, false);

                if ($diferenciaMinutos > 0) {
                    $tipoMarca = $this->determinarTipoMarca($horaMarcada->format('H:i:s'));

                    $idAprobacion = DB::table('aprobacion_horas')->insertGetId([
                        'idAsistencia' => $asistencia->idAsistencia,
                        'idUsuario' => $asistencia->idUsuario,
                        'fechaHora_original' => $asistencia->fechaHora,
                        'tipo_marca' => $tipoMarca,
                        'umbral_minutos' => 20,
                        'diferencia_minutos' => $diferenciaMinutos,
                        'estado' => 'PENDIENTE',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);

                    DB::table('notificaciones_aprobacion_horas')->insert([
                        'idAprobacion' => $idAprobacion,
                        'estado_web' => 'no_leido',
                        'estado_app' => 'no_leido',
                        'fecha' => Carbon::now(),
                        'tipo' => 'APROBACION_HORAS_PENDIENTE',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);

                    $registrosCreados++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Se procesaron {$registrosCreados} registros de horas extras"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar horas extras: ' . $e->getMessage()
            ], 500);
        }
    }

    private function determinarTipoMarca($hora)
    {
        $horaCarbon = Carbon::parse($hora);

        if ($horaCarbon->between('17:20:00', '19:00:00')) {
            return 'HORA_EXTRA_1';
        } elseif ($horaCarbon->between('19:00:01', '22:00:00')) {
            return 'HORA_EXTRA_2';
        } elseif ($horaCarbon->between('22:00:01', '23:59:59')) {
            return 'HORA_EXTRA_NOCTURNA';
        } elseif ($horaCarbon->between('00:00:00', '05:59:59')) {
            return 'HORA_EXTRA_MADRUGADA';
        } else {
            return 'HORA_EXTRA_DIURNA';
        }
    }

    public function aprobar(Request $request)
    {
        // ✅ Debug para saber EXACTAMENTE qué falló (igual estilo que usas)
        $debug = [
            'tx'   => ['committed' => false],
            'notif' => ['ok' => false, 'id' => null, 'error' => null],
            'ws'   => ['ok' => false, 'error' => null, 'payload' => null],
        ];

        try {
            DB::beginTransaction();

            $request->validate([
                'idAprobacion' => 'required|integer',
                'hora_final_modificada' => 'required|string',
                'minutos_extras' => 'required|integer|min:0',
                'fecha_original' => 'required|string'
            ]);

            // Validar formato de hora manualmente
            if (!preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $request->hora_final_modificada)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Formato de hora inválido. Use HH:MM',
                    'debug' => $debug
                ], 400);
            }

            // Validar formato de fecha
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $request->fecha_original)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Formato de fecha inválido. Use YYYY-MM-DD',
                    'debug' => $debug
                ], 400);
            }

            $idUsuario = Auth::id() ?? 1;

            $aprobacion = DB::table('aprobacion_horas')
                ->where('idAprobacion', $request->idAprobacion)
                ->first();

            if (!$aprobacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registro no encontrado',
                    'debug' => $debug
                ], 404);
            }

            $fechaOriginal = Carbon::parse($request->fecha_original);
            $horaFinalModificada = $request->hora_final_modificada;

            $partes = explode(':', $horaFinalModificada);
            if (count($partes) != 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Formato de hora inválido',
                    'debug' => $debug
                ], 400);
            }

            $hora = (int)$partes[0];
            $minuto = (int)$partes[1];

            if ($hora < 0 || $hora > 23 || $minuto < 0 || $minuto > 59) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hora o minuto fuera de rango',
                    'debug' => $debug
                ], 400);
            }

            if ($hora < 17) {
                return response()->json([
                    'success' => false,
                    'message' => 'La hora final no puede ser menor a 17:00',
                    'debug' => $debug
                ], 400);
            }

            $fechaModificada = $fechaOriginal->copy()->setTime($hora, $minuto, 0);
            $fechaModificadaMySQL = $fechaModificada->format('Y-m-d H:i:s');

            // ✅ Actualiza aprobacion_horas
            DB::table('aprobacion_horas')
                ->where('idAprobacion', $request->idAprobacion)
                ->update([
                    'estado' => 'APROBADO',
                    'fechaHora_modificada' => $fechaModificadaMySQL,
                    'diferencia_minutos' => $request->minutos_extras,
                    'revisado_por' => $idUsuario,
                    'revisado_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

            // ✅ Actualiza asistencia
            DB::table('asistencias')
                ->where('idAsistencia', $aprobacion->idAsistencia)
                ->update([
                    'fechaHora' => $fechaModificadaMySQL
                ]);

            // ✅ Actualiza notificación (y obtenemos su ID para WS)
            // OJO: tu tabla se llama notificaciones_aprobacion_horas
            // y estás filtrando por idAprobacion (ok).
            $notif = DB::table('notificaciones_aprobacion_horas')
                ->where('idAprobacion', $request->idAprobacion)
                ->orderByDesc('idNotificacion') // por si hay varias
                ->first();

            if ($notif && !empty($notif->idNotificacion)) {
                $debug['notif']['ok'] = true;
                $debug['notif']['id'] = (int)$notif->idNotificacion;
            } else {
                $debug['notif']['ok'] = false;
                $debug['notif']['error'] = 'No se encontró idNotificacion en notificaciones_aprobacion_horas.';
            }

            // ✅ Actualiza campos de la noti (tipo/estado)
            DB::table('notificaciones_aprobacion_horas')
                ->where('idAprobacion', $request->idAprobacion)
                ->update([
                    'estado_web' => 'leido',
                    'estado_app' => 'leido',
                    'tipo' => 'APROBACION_HORAS_APROBADO',
                    'updated_at' => Carbon::now()
                ]);

            DB::commit();
            $debug['tx']['committed'] = true;

            // ========== 10.5 WS (DESPUÉS DEL COMMIT) ==========
            if (!empty($debug['notif']['id'])) {
                $payload = [
                    'type' => 'aprobacion_horas_evento',

                    // ✅ id de notificación
                    'idNotificacion' => (int)$debug['notif']['id'],
                    'idNotificacionAprobacionHoras' => (int)$debug['notif']['id'],

                    // ✅ id de la aprobación (registro principal)
                    'idAprobacionHoras' => (int)$request->idAprobacion,
                    'idAprobacion' => (int)$request->idAprobacion, // compat por si algún lado usa este nombre

                    // ✅ tipo forzado (CLAVE)
                    'tipoNotificacionForzada' => 'APROBACION_HORAS_APROBADO',

                    // ✅ extras útiles (tu handler los acepta como opcionales)
                    'idUsuarioAprobador' => (int)$idUsuario,
                    'idUsuarioSolicitante' => (int)($aprobacion->idUsuario ?? $aprobacion->id_usuario ?? $aprobacion->idUsuarioSolicitante ?? 0),

                    'horaOriginal' => (string)($aprobacion->fechaHora_original ?? ''),
                    'horaModificada' => (string)$fechaModificadaMySQL,
                    'fecha' => (string)$request->fecha_original,
                    'comentario' => null,
                    'estado' => 'APROBADO',
                ];

                $debug['ws']['payload'] = $payload;

                try {
                    WsBridge::emitSolicitudEvento($payload);
                    $debug['ws']['ok'] = true;
                } catch (\Throwable $e) {
                    $debug['ws']['ok'] = false;
                    $debug['ws']['error'] = $e->getMessage();
                }
            } else {
                $debug['ws']['ok'] = false;
                $debug['ws']['error'] = 'No se envió WS porque no existe idNotificacion.';
            }

            return response()->json([
                'success' => true,
                'message' => 'Hora extra aprobada correctamente. Nueva hora: ' . $horaFinalModificada,
                'debug' => $debug
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $debug['tx']['committed'] = false;

            return response()->json([
                'success' => false,
                'message' => 'Error al aprobar: ' . $e->getMessage(),
                'debug' => $debug
            ], 500);
        }
    }

    public function rechazar(Request $request)
    {
        $debug = [
            'tx'   => ['committed' => false],
            'notif' => ['ok' => false, 'id' => null, 'error' => null],
            'ws'   => ['ok' => false, 'error' => null, 'payload' => null],
        ];

        try {
            DB::beginTransaction();

            $request->validate([
                'idAprobacion' => 'required|integer',
                'comentario' => 'nullable|string|max:500'
            ]);

            $idUsuario = Auth::id() ?? 1;

            $aprobacion = DB::table('aprobacion_horas')
                ->where('idAprobacion', $request->idAprobacion)
                ->first();

            if (!$aprobacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registro no encontrado',
                    'debug' => $debug
                ], 404);
            }

            $fechaHoraModificada = Carbon::parse($aprobacion->fechaHora_original)
                ->startOfDay()
                ->setTime(17, 0, 0);

            $fechaHoraModificadaMySQL = $fechaHoraModificada->format('Y-m-d H:i:s');

            DB::table('aprobacion_horas')
                ->where('idAprobacion', $request->idAprobacion)
                ->update([
                    'estado' => 'DENEGADO',
                    'comentario' => $request->comentario,
                    'fechaHora_modificada' => $fechaHoraModificadaMySQL,
                    'revisado_por' => $idUsuario,
                    'revisado_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

            DB::table('asistencias')
                ->where('idAsistencia', $aprobacion->idAsistencia)
                ->update([
                    'fechaHora' => $fechaHoraModificadaMySQL
                ]);

            // ✅ obtenemos idNotificacion para WS
            $notif = DB::table('notificaciones_aprobacion_horas')
                ->where('idAprobacion', $request->idAprobacion)
                ->orderByDesc('idNotificacion')
                ->first();

            if ($notif && !empty($notif->idNotificacion)) {
                $debug['notif']['ok'] = true;
                $debug['notif']['id'] = (int)$notif->idNotificacion;
            } else {
                $debug['notif']['ok'] = false;
                $debug['notif']['error'] = 'No se encontró idNotificacion en notificaciones_aprobacion_horas.';
            }

            DB::table('notificaciones_aprobacion_horas')
                ->where('idAprobacion', $request->idAprobacion)
                ->update([
                    'estado_web' => 'leido',
                    'estado_app' => 'leido',
                    // ✅ OJO: en tu registro dijiste 2 tipos: APROBADO y RECHAZAR
                    // aquí fuerzo al canon "APROBACION_HORAS_RECHAZADO"
                    'tipo' => 'APROBACION_HORAS_RECHAZADO',
                    'updated_at' => Carbon::now()
                ]);

            DB::commit();
            $debug['tx']['committed'] = true;

            // ========== 10.5 WS (DESPUÉS DEL COMMIT) ==========
            if (!empty($debug['notif']['id'])) {
                $payload = [
                    'type' => 'aprobacion_horas_evento',

                    'idNotificacion' => (int)$debug['notif']['id'],
                    'idNotificacionAprobacionHoras' => (int)$debug['notif']['id'],

                    'idAprobacionHoras' => (int)$request->idAprobacion,
                    'idAprobacion' => (int)$request->idAprobacion,

                    'tipoNotificacionForzada' => 'APROBACION_HORAS_RECHAZADO',

                    'idUsuarioAprobador' => (int)$idUsuario,
                    'idUsuarioSolicitante' => (int)($aprobacion->idUsuario ?? $aprobacion->id_usuario ?? $aprobacion->idUsuarioSolicitante ?? 0),

                    'horaOriginal' => (string)($aprobacion->fechaHora_original ?? ''),
                    'horaModificada' => (string)$fechaHoraModificadaMySQL,
                    'fecha' => (string)Carbon::parse($aprobacion->fechaHora_original)->format('Y-m-d'),
                    'comentario' => (string)($request->comentario ?? ''),
                    'estado' => 'DENEGADO',
                ];

                $debug['ws']['payload'] = $payload;

                try {
                    WsBridge::emitSolicitudEvento($payload);
                    $debug['ws']['ok'] = true;
                } catch (\Throwable $e) {
                    $debug['ws']['ok'] = false;
                    $debug['ws']['error'] = $e->getMessage();
                }
            } else {
                $debug['ws']['ok'] = false;
                $debug['ws']['error'] = 'No se envió WS porque no existe idNotificacion.';
            }

            return response()->json([
                'success' => true,
                'message' => 'Hora extra rechazada correctamente',
                'debug' => $debug
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $debug['tx']['committed'] = false;

            return response()->json([
                'success' => false,
                'message' => 'Error al rechazar: ' . $e->getMessage(),
                'debug' => $debug
            ], 500);
        }
    }
    public function getUsuarios()
    {
        try {
            $usuarios = DB::table('usuarios')
                ->where('estado', 1)
                ->select(
                    'idUsuario',
                    DB::raw("CONCAT(Nombre, ' ', apellidoPaterno, ' ', apellidoMaterno) as nombre_completo")
                )
                ->orderBy('nombre_completo')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $usuarios
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getContadores()
    {
        try {
            $pendientes = DB::table('aprobacion_horas')
                ->where('estado', 'PENDIENTE')
                ->count();

            $aprobados = DB::table('aprobacion_horas')
                ->where('estado', 'APROBADO')
                ->count();

            $rechazados = DB::table('aprobacion_horas')
                ->where('estado', 'DENEGADO')
                ->count();

            $total = $pendientes + $aprobados + $rechazados;

            return response()->json([
                'success' => true,
                'data' => [
                    'pendientes' => $pendientes,
                    'aprobados' => $aprobados,
                    'rechazados' => $rechazados,
                    'total' => $total
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener contadores: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDetalle($id)
    {
        try {
            $detalle = DB::table('aprobacion_horas as ah')
                ->join('usuarios as u', 'ah.idUsuario', '=', 'u.idUsuario')
                ->leftJoin('usuarios as r', 'ah.revisado_por', '=', 'r.idUsuario')
                ->where('ah.idAprobacion', $id)
                ->select(
                    'ah.idAprobacion',
                    'ah.fechaHora_original',
                    'ah.tipo_marca',
                    'ah.umbral_minutos',
                    'ah.diferencia_minutos',
                    'ah.fechaHora_modificada',
                    'ah.estado',
                    'ah.comentario',
                    'ah.revisado_at',
                    DB::raw("CONCAT(u.Nombre, ' ', u.apellidoPaterno, ' ', u.apellidoMaterno) as nombre_completo"),
                    DB::raw("CONCAT(r.Nombre, ' ', r.apellidoPaterno) as revisado_por_nombre")
                )
                ->first();

            if (!$detalle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registro no encontrado'
                ], 404);
            }

            if ($detalle->fechaHora_original) {
                $detalle->fechaHora_original = Carbon::parse($detalle->fechaHora_original)->toIso8601String();
            }
            if ($detalle->fechaHora_modificada) {
                $detalle->fechaHora_modificada = Carbon::parse($detalle->fechaHora_modificada)->toIso8601String();
            }
            if ($detalle->revisado_at) {
                $detalle->revisado_at = Carbon::parse($detalle->revisado_at)->toIso8601String();
            }

            return response()->json([
                'success' => true,
                'data' => $detalle
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener detalle: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verificarVisitas(Request $request)
    {
        // Log 1: Inicio del método y datos recibidos
        \Log::info('=== INICIO VERIFICACIÓN DE VISITAS ===');
        \Log::info('Request recibido:', [
            'idUsuario' => $request->idUsuario,
            'fecha' => $request->fecha,
            'all_data' => $request->all()
        ]);

        try {
            $request->validate([
                'idUsuario' => 'required|integer',
                'fecha' => 'required|date'
            ]);

            \Log::info('Validación exitosa');

            $fecha = Carbon::parse($request->fecha)->format('Y-m-d');
            \Log::info('Fecha formateada para BD:', ['fecha' => $fecha]);

            // Habilitar log de queries
            \DB::enableQueryLog();

            // PRIMERO: Verificar si hay registros en ticketflujo para esta fecha
            $flujosEnFecha = DB::table('ticketflujo')
                ->whereDate('fecha_creacion', $fecha)
                ->where('idEstadflujo', 7)
                ->count();

            \Log::info('Registros en ticketflujo para la fecha:', [
                'fecha' => $fecha,
                'total_flujos' => $flujosEnFecha
            ]);

            $visitas = DB::table('visitas as v')
                ->join('tickets as t', 'v.idTickets', '=', 't.idTickets')
                ->join('ticketflujo as tf', function ($join) use ($fecha) {
                    $join->on('v.idVisitas', '=', 'tf.idVisitas')
                        ->where('tf.idEstadflujo', '=', 7)
                        ->whereDate('tf.fecha_creacion', '=', $fecha);
                })
                ->where('v.idUsuario', $request->idUsuario)
                ->whereDate('v.fecha_programada', '=', $fecha)
                ->select(
                    'v.idVisitas',
                    'v.nombre as nombre_visita',
                    'v.fecha_programada',
                    'v.fecha_inicio',
                    'v.estado as estado_visita',
                    'v.necesita_apoyo',
                    't.idTickets',
                    't.numero_ticket',
                    't.fallaReportada',
                    't.idTecnico',
                    't.idClienteGeneral',
                    'tf.idTicketFlujo',
                    'tf.fecha_creacion as fecha_finalizacion',
                    'tf.comentarioflujo',
                    'tf.idEstadflujo'
                )
                ->orderBy('tf.fecha_creacion')
                ->get();

            // Log 3: Resultado de la query
            $queryLog = \DB::getQueryLog();
            \Log::info('SQL Query:', [
                'query' => $queryLog[0]['query'] ?? 'No query',
                'bindings' => $queryLog[0]['bindings'] ?? []
            ]);

            \Log::info('Visitas encontradas:', [
                'cantidad' => $visitas->count(),
                'ids_visitas' => $visitas->pluck('idVisitas')->toArray()
            ]);

            if ($visitas->isEmpty()) {
                \Log::warning('No se encontraron visitas finalizadas', [
                    'idUsuario' => $request->idUsuario,
                    'fecha' => $fecha
                ]);
            }

            // Procesar TODAS las visitas con su hora final
            $visitasProcesadas = [];
            $totalMinutosExtra = 0;
            $visitasDespues17 = 0;

            foreach ($visitas as $visita) {
                \Log::info('Procesando visita:', [
                    'idVisita' => $visita->idVisitas,
                    'ticket' => $visita->numero_ticket,
                    'fecha_finalizacion_RAW' => $visita->fecha_finalizacion,
                    'fecha_inicio' => $visita->fecha_inicio
                ]);

                // Formatear fecha de inicio
                $visita->hora_inicio = $visita->fecha_inicio ?
                    Carbon::parse($visita->fecha_inicio)->format('H:i') : '--:--';

                // Verificar si tiene fecha de finalización
                if ($visita->fecha_finalizacion) {
                    $horaFinal = Carbon::parse($visita->fecha_finalizacion);
                    $horaLimite = $horaFinal->copy()->setTime(17, 0, 0);

                    $visita->hora_final = $horaFinal->format('H:i');

                    // Verificar si terminó después de las 17:00
                    if ($horaFinal->gt($horaLimite)) {
                        // Calcular minutos extras y redondear
                        $minutosExtra = round($horaLimite->diffInMinutes($horaFinal));
                        $visita->minutos_extra = (int)$minutosExtra;
                        $visita->tiene_extra = true;
                        $visitasDespues17++;

                        // Formatear tiempo extra
                        if ($visita->minutos_extra >= 60) {
                            $horas = floor($visita->minutos_extra / 60);
                            $minutos = $visita->minutos_extra % 60;
                            $visita->tiempo_extra = $minutos > 0 ? "{$horas}h {$minutos}m" : "{$horas}h";
                        } else {
                            $visita->tiempo_extra = $visita->minutos_extra . 'm';
                        }

                        $totalMinutosExtra += $visita->minutos_extra;

                        \Log::info('Visita CON horas extras:', [
                            'idVisita' => $visita->idVisitas,
                            'hora_final' => $visita->hora_final,
                            'minutos_extra' => $visita->minutos_extra,
                            'tiempo_formateado' => $visita->tiempo_extra
                        ]);
                    } else {
                        // No tiene horas extras
                        $visita->minutos_extra = 0;
                        $visita->tiempo_extra = '0m';
                        $visita->tiene_extra = false;

                        \Log::info('Visita SIN horas extras:', [
                            'idVisita' => $visita->idVisitas,
                            'hora_final' => $visita->hora_final
                        ]);
                    }
                } else {
                    // No tiene fecha de finalización
                    $visita->hora_final = '--:--';
                    $visita->minutos_extra = 0;
                    $visita->tiempo_extra = '0m';
                    $visita->tiene_extra = false;

                    \Log::warning('fecha_finalizacion es NULL para visita:', [
                        'idVisita' => $visita->idVisitas,
                        'ticket' => $visita->numero_ticket
                    ]);
                }

                $visitasProcesadas[] = $visita;
            }

            // Obtener información del técnico
            $tecnico = DB::table('usuarios')
                ->where('idUsuario', $request->idUsuario)
                ->select(
                    'idUsuario',
                    DB::raw("CONCAT(Nombre, ' ', apellidoPaterno, ' ', apellidoMaterno) as nombre_completo")
                )
                ->first();

            // Formatear tiempo total
            $horasTotal = floor($totalMinutosExtra / 60);
            $minutosTotal = $totalMinutosExtra % 60;

            if ($horasTotal > 0) {
                $tiempoTotal = $minutosTotal > 0 ? "{$horasTotal}h {$minutosTotal}m" : "{$horasTotal}h";
            } else {
                $tiempoTotal = "{$minutosTotal}m";
            }

            \Log::info('RESUMEN FINAL (TODAS las visitas):', [
                'total_visitas' => count($visitasProcesadas),
                'visitas_despues_17' => $visitasDespues17,
                'total_minutos_extra' => $totalMinutosExtra,
                'tiempo_extra_total' => $tiempoTotal
            ]);

            \Log::info('=== FIN VERIFICACIÓN DE VISITAS ===');

            return response()->json([
                'success' => true,
                'data' => [
                    'tecnico' => $tecnico,
                    'visitas' => $visitasProcesadas, // ✅ TODAS las visitas
                    'resumen' => [
                        'total_visitas' => count($visitasProcesadas),
                        'visitas_despues_17' => $visitasDespues17,
                        'total_minutos_extra' => $totalMinutosExtra,
                        'tiempo_extra_total' => $tiempoTotal
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al verificar visitas:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al verificar visitas: ' . $e->getMessage()
            ], 500);
        }
    }
}
