<?php

namespace App\Http\Controllers\administracion\solicitudasistencia;

use App\Http\Controllers\Controller;
use App\Models\ArchivoSolicitudAsistencia;
use App\Models\EvaluarSolicitudAsistencia;
use App\Models\ImagenSolicitudAsistencia;
use App\Models\NotificacionSolicitudAsistencia;
use App\Models\SolicitudAsistencia;
use App\Models\SolicitudAsistenciaDia;
use App\Models\TipoEducacion;
use App\Models\TipoSolicitudAsistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use App\Services\WsBridge;

class SolicitudAsistenciaController extends Controller
{
    // Constantes para tipos de notificaciones
    const TIPO_CREADA = 'SOLICITUD_ASISTENCIA_CREADA';
    const TIPO_ACTUALIZADA = 'SOLICITUD_ASISTENCIA_ACTUALIZADA';
    const TIPO_APROBADA = 'SOLICITUD_ASISTENCIA_APROBADA';
    const TIPO_DENEGADA = 'SOLICITUD_ASISTENCIA_DENEGADA';
    const TIPO_ELIMINADA = 'SOLICITUD_ASISTENCIA_ELIMINADA';

    // Rutas base para archivos
    private $rutaBaseImagenes = 'assets/images/solicitudes/imagenes/';
    private $rutaBaseArchivos = 'assets/images/solicitudes/archivos/';

    /* =========================
     * INDEX
     * ========================= */
    public function index(Request $request)
    {
        $tipos = TipoSolicitudAsistencia::orderBy('nombre_tip')->get();

        $query = SolicitudAsistencia::with(['tipoSolicitud', 'tipoEducacion'])
            ->orderByDesc('id_solicitud_asistencia');

        if ($request->filled('tipo')) {
            $query->where('id_tipo_solicitud', $request->tipo);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $solicitudes = $query->paginate(12)->withQueryString();

        return view('administracion.solicitudasistencia.index', compact('solicitudes', 'tipos'));
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create()
    {
        return view('administracion.solicitudasistencia.create', [
            'tipos' => TipoSolicitudAsistencia::orderBy('nombre_tip')->get(),
            'tiposEducacion' => TipoEducacion::orderBy('nombre')->get(),
        ]);
    }

    /* =========================
     * STORE
     * ========================= */
    public function store(Request $request)
    {
        return $this->saveSolicitud($request);
    }

    /* =========================
     * EDIT
     * ========================= */
    public function edit($id)
    {
        $solicitud = SolicitudAsistencia::with(['tipoSolicitud', 'tipoEducacion', 'dias'])
            ->findOrFail($id);

        $diasEdit = [];

        foreach ($solicitud->dias as $d) {
            $fecha = Carbon::parse($d->fecha);
            $diaNombre = $fecha->locale('es')->dayName;
            $diaNormalizado = $this->normalizarNombreDia($diaNombre);

            $diasEdit[$diaNormalizado] = [
                'id_solicitud_dia' => $d->id_solicitud_dia,
                'es_todo_el_dia' => $d->es_todo_el_dia,
                'hora_entrada' => $d->hora_entrada,
                'hora_salida' => $d->hora_salida,
                'hora_llegada_trabajo' => $d->hora_llegada_trabajo,
                'observacion' => $d->observacion,
                'fecha' => $d->fecha,
            ];
        }

        return view('administracion.solicitudasistencia.edit', [
            'solicitud' => $solicitud,
            'tipos' => TipoSolicitudAsistencia::orderBy('nombre_tip')->get(),
            'tiposEducacion' => TipoEducacion::orderBy('nombre')->get(),
            'diasEdit' => $diasEdit,
        ]);
    }

    /* =========================
     * UPDATE
     * ========================= */
    public function update(Request $request, $id)
    {
        $solicitud = SolicitudAsistencia::findOrFail($id);

        if (!$request->filled('id_tipo_solicitud')) {
            $request->merge(['id_tipo_solicitud' => $solicitud->id_tipo_solicitud]);
        }

        return $this->saveSolicitud($request, $solicitud);
    }

    /* =========================
     * DESTROY
     * ========================= */
    public function destroy($id)
    {
        $solicitud = SolicitudAsistencia::findOrFail($id);

        DB::transaction(function () use ($solicitud) {
            // Eliminar archivos físicos
            $this->eliminarArchivosFisicos($solicitud);

            // Eliminar días relacionados
            SolicitudAsistenciaDia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->delete();

            // Eliminar archivos relacionados
            ArchivoSolicitudAsistencia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->delete();

            // Eliminar imágenes relacionadas
            ImagenSolicitudAsistencia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->delete();

            // Eliminar evaluaciones relacionadas
            EvaluarSolicitudAsistencia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->delete();

            // Crear notificación de eliminación
            $this->crearNotificacion($solicitud->id_solicitud_asistencia, self::TIPO_ELIMINADA);

            // Eliminar la solicitud
            $solicitud->delete();
        });

        return redirect()
            ->route('administracion.solicitud-asistencia.index')
            ->with('success', 'Solicitud eliminada correctamente');
    }

    /* =========================
     * CAMBIAR ESTADO
     * ========================= */
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => ['required', 'in:aprobado,denegado'],
            'comentario' => ['nullable', 'string', 'max:500'],
        ]);

        $solicitud = SolicitudAsistencia::findOrFail($id);

        // (opcional) para mejorar UX / fallback
        $nombreUsuario = trim((string)($solicitud->usuario->nombreCompleto ?? '')); // ajusta relación
        $tipoPermiso   = trim((string)($solicitud->tipoSolicitud->nombre_tip ?? '')); // ajusta relación

        $idNotifNew = 0;
        $tipoNotificacion = '';

        DB::transaction(function () use ($request, $solicitud, &$idNotifNew, &$tipoNotificacion) {
            // 1) Actualizar estado
            $solicitud->update([
                'estado' => $request->estado,
            ]);

            // 2) Historial
            EvaluarSolicitudAsistencia::create([
                'id_solicitud_asistencia' => $solicitud->id_solicitud_asistencia,
                'id_tipo_solicitud' => $solicitud->id_tipo_solicitud,
                'estado' => $request->estado,
                'comentario' => $request->comentario,
                'id_usuario' => Auth::id(),
                'fecha' => now(),
            ]);

            // 3) Notificación
            $tipoNotificacion = $request->estado === 'aprobado'
                ? self::TIPO_APROBADA
                : self::TIPO_DENEGADA;

            $notif = $this->crearNotificacion($solicitud->id_solicitud_asistencia, $tipoNotificacion);

            // ✅ tu log muestra que el campo se llama id_notificacion_solicitud
            $idNotifNew = (int) ($notif->id_notificacion_solicitud ?? 0);


            // ✅ SOLO SI COMMIT OK
            DB::afterCommit(function () use ($solicitud, $idNotifNew, $tipoNotificacion) {
                try {
                    WsBridge::emitSolicitudEvento([
                        'type' => 'solicitud_asistencia_evento',
                        'source' => 'laravel',
                        'idUsuario' => Auth::id(), // quien aprobó/denegó (admin)
                        'id_solicitud_asistencia' => (int)$solicitud->id_solicitud_asistencia,
                        'idNotificacionSolicitud' => $idNotifNew > 0 ? $idNotifNew : null,

                        'tipoNotificacionForzada' => $tipoNotificacion,
                        'ts' => now()->toIso8601String(),
                    ]);
                } catch (\Throwable $e) {
                    \Log::warning("⚠️ No se pudo enviar WS solicitud_asistencia_evento idNotif={$idNotifNew}: " . $e->getMessage());
                }
            });
        });

        $estadoTexto = $request->estado == 'aprobado' ? 'aprobada' : 'denegada';

        return redirect()
            ->route('administracion.solicitud-asistencia.index')
            ->with('success', "Solicitud {$estadoTexto} correctamente");
    }

    /* =========================
     * VER DETALLES
     * ========================= */
    public function show($id)
    {
        $solicitud = SolicitudAsistencia::with([
            'tipoSolicitud',
            'tipoEducacion',
            'dias',
            'archivos',
            'imagenes',
            'usuario',
            'evaluaciones' => fn($q) => $q->orderBy('fecha', 'desc'),
            'evaluaciones.usuario',
            'notificaciones' => fn($q) => $q->orderBy('fecha', 'desc'),
        ])->findOrFail($id);

        $diasDuracion = 0;

        if ($solicitud->rango_inicio_tiempo && $solicitud->rango_final_tiempo) {
            $diasDuracion = $solicitud->rango_inicio_tiempo
                ->startOfDay()
                ->diffInDays($solicitud->rango_final_tiempo->startOfDay()) + 1;
        }

        return view(
            'administracion.solicitudasistencia.show',
            compact('solicitud', 'diasDuracion')
        );
    }

    /* =====================================================
     * MÉTODO PRINCIPAL - CORREGIDO PARA EL ERROR DE getSize()
     * ===================================================== */
    private function saveSolicitud(Request $request, SolicitudAsistencia $solicitud = null)
    {
        // 1. Obtener el tipo de solicitud
        $tipo = TipoSolicitudAsistencia::findOrFail($request->id_tipo_solicitud);
        $tipoNombre = mb_strtolower(trim($tipo->nombre_tip));

        // 2. Determinar si es educativo (ID 6 según tu app)
        $esEducativo = ($request->id_tipo_solicitud == 6); // ID fijo para educativo
        $esLicenciaMedica = in_array($tipoNombre, ['licencia médico', 'licencia medico'], true);
        $esUpdate = $solicitud !== null;

        // Log para debugging
        Log::info('Guardando solicitud', [
            'tipo_id' => $request->id_tipo_solicitud,
            'esEducativo' => $esEducativo,
            'esLicenciaMedica' => $esLicenciaMedica,
            'esUpdate' => $esUpdate
        ]);

        /* ========= VALIDACIÓN BASE ========= */
        $rules = [
            'id_tipo_solicitud'   => ['required', Rule::exists('tipo_solicitud_asistencia', 'id_tipo_solicitud')],
            'observacion'         => ['nullable', 'string'],
            'rango_inicio_tiempo' => ['required', 'date_format:Y-m-d H:i'],
            'rango_final_tiempo'  => ['required', 'date_format:Y-m-d H:i', 'after:rango_inicio_tiempo'],

        ];

        // Reglas específicas para licencia médica
        if ($esLicenciaMedica) {
            $imagenRule = $esUpdate ? 'nullable' : 'required';
            if (!$esUpdate || $request->hasFile('imagen_licencia')) {
                $rules['imagen_licencia'] = [$imagenRule, 'image', 'mimes:jpg,jpeg,png', 'max:4096'];
            }
        }

        // Reglas específicas para educativo
        if ($esEducativo) {
            $rules += [
                'id_tipo_educacion' => ['required', Rule::exists('tipo_educacion', 'id_tipo_educacion')],
                'dias'              => ['required', 'array', 'min:1'],
                'dias.*.dia'        => ['required', 'string', 'in:lunes,martes,miercoles,jueves,viernes,sabado'],
                'dias.*.es_todo_el_dia' => ['nullable', 'boolean'],
                'dias.*.hora_entrada' => ['nullable', 'regex:/^(\d{1,2}:\d{2})(\s?(AM|PM))?$/i'],
                'dias.*.hora_salida'  => ['nullable', 'regex:/^(\d{1,2}:\d{2})(\s?(AM|PM))?$/i'],
                'dias.*.hora_llegada_trabajo' => ['nullable', 'regex:/^(\d{1,2}:\d{2})(\s?(AM|PM))?$/i'],
                'dias.*.observacion' => ['nullable', 'string', 'max:255'],
            ];

            // Validaciones personalizadas para horas
            $validator = Validator::make($request->all(), $rules);

            $validator->after(function ($validator) use ($request) {
                if (isset($request->dias) && is_array($request->dias)) {
                    foreach ($request->dias as $index => $dia) {
                        $todoDia = isset($dia['es_todo_el_dia']) && $dia['es_todo_el_dia'] == '1';
                        $horaEntrada = trim($dia['hora_entrada'] ?? '');
                        $horaSalida = trim($dia['hora_salida'] ?? '');
                        $horaLlegada = trim($dia['hora_llegada_trabajo'] ?? '');
                        $diaNombre = $dia['dia'] ?? 'día';

                        // Si está marcado como "todo el día"
                        if ($todoDia) {
                            // Verificar que NO tenga horas completadas
                            if (!empty($horaEntrada) || !empty($horaSalida) || !empty($horaLlegada)) {
                                $validator->errors()->add(
                                    "dias.{$index}.es_todo_el_dia",
                                    "Si es 'todo el día' para el " . ucfirst($diaNombre) . ", no se deben especificar horas"
                                );
                            }
                        }
                        // Si NO es "todo el día" pero tiene AL MENOS UNA hora completada
                        elseif (!empty($horaEntrada) || !empty($horaSalida) || !empty($horaLlegada)) {
                            // Entonces TODAS las horas son requeridas
                            if (empty($horaEntrada)) {
                                $validator->errors()->add(
                                    "dias.{$index}.hora_entrada",
                                    "La hora de entrada es requerida para el " . ucfirst($diaNombre)
                                );
                            }

                            if (empty($horaSalida)) {
                                $validator->errors()->add(
                                    "dias.{$index}.hora_salida",
                                    "La hora de salida es requerida para el " . ucfirst($diaNombre)
                                );
                            }

                            if (empty($horaLlegada)) {
                                $validator->errors()->add(
                                    "dias.{$index}.hora_llegada_trabajo",
                                    "La hora de llegada al trabajo es requerida para el " . ucfirst($diaNombre)
                                );
                            }

                            // Solo validar el rango si ambas horas están presentes
                            if (!empty($horaEntrada) && !empty($horaSalida)) {
                                $entrada = $this->parseHora($horaEntrada);
                                $salida  = $this->parseHora($horaSalida);

                                if ($salida->lessThanOrEqualTo($entrada)) {
                                    $validator->errors()->add(
                                        "dias.{$index}.hora_salida",
                                        "La hora de salida debe ser mayor que la hora de entrada para el " . ucfirst($diaNombre)
                                    );
                                }
                            }
                        }
                    }
                }
            });

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            // Regla de archivo para educativo
            if (!$esUpdate) {
                $rules['archivo'] = ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
            } else {
                $rules['archivo'] = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
            }

            $rules['imagen_opcional'] = ['nullable', 'image', 'max:4096'];
        }

        // Validar los datos
        if (!$esEducativo) {
            $data = $request->validate($rules);
        } else {
            $data = $validator->validated();
        }

        try {
            DB::beginTransaction();

            /* ========= CREAR O ACTUALIZAR SOLICITUD ========= */
            if (!$solicitud) {
                // Crear nueva solicitud
                $solicitud = SolicitudAsistencia::create([
                    'id_tipo_solicitud'   => $data['id_tipo_solicitud'],
                    'observacion'         => $data['observacion'] ?? null,
                    'fecha_solicitud'     => now(),
                    'rango_inicio_tiempo' => Carbon::parse($data['rango_inicio_tiempo']),
                    'rango_final_tiempo'  => Carbon::parse($data['rango_final_tiempo']),
                    'id_tipo_educacion'   => $esEducativo ? $data['id_tipo_educacion'] : null,
                    'estado'              => 'pendiente',
                    'id_usuario'          => Auth::id(),
                ]);

                // Crear registro en evaluar_solicitud_asistencia
                EvaluarSolicitudAsistencia::create([
                    'id_solicitud_asistencia' => $solicitud->id_solicitud_asistencia,
                    'id_tipo_solicitud'       => $solicitud->id_tipo_solicitud,
                    'estado'                  => 'pendiente',
                    'id_usuario'              => Auth::id(),
                    'fecha'                   => now(),
                ]);

                // Crear notificación de creación
                $this->crearNotificacion($solicitud->id_solicitud_asistencia, self::TIPO_CREADA);
            } else {
                // Actualizar solicitud existente
                $solicitud->update([
                    'observacion'         => $data['observacion'] ?? $solicitud->observacion,
                    'rango_inicio_tiempo' => Carbon::parse($data['rango_inicio_tiempo']),
                    'rango_final_tiempo'  => Carbon::parse($data['rango_final_tiempo']),
                    'id_tipo_educacion'   => $esEducativo ? $data['id_tipo_educacion'] : null,
                ]);

                // Crear notificación de actualización
                $this->crearNotificacion($solicitud->id_solicitud_asistencia, self::TIPO_ACTUALIZADA);
            }

            /* ========= MANEJO DE LICENCIA MÉDICA ========= */
            if ($esLicenciaMedica && $request->hasFile('imagen_licencia')) {
                // Eliminar imágenes existentes de la BD y archivos físicos
                $imagenesExistentes = ImagenSolicitudAsistencia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->get();

                // Eliminar archivos físicos
                foreach ($imagenesExistentes as $img) {
                    if ($img->imagen && file_exists(public_path($img->imagen))) {
                        unlink(public_path($img->imagen));
                    }
                }

                // Eliminar registros de BD
                ImagenSolicitudAsistencia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->delete();

                // OBTENER DATOS ANTES DE MOVER EL ARCHIVO
                $file = $request->file('imagen_licencia');
                $tipoArchivo = $file->getClientMimeType();
                $tamanoArchivo = $file->getSize();
                $nombreArchivo = $this->generarNombreArchivo($file, 'licencia_' . $solicitud->id_solicitud_asistencia);
                $rutaRelativa = $this->rutaBaseImagenes . $nombreArchivo;

                // Mover el archivo
                $file->move(public_path($this->rutaBaseImagenes), $nombreArchivo);

                // Guardar en BD
                ImagenSolicitudAsistencia::create([
                    'id_solicitud_asistencia' => $solicitud->id_solicitud_asistencia,
                    'imagen' => $rutaRelativa,
                ]);
            }

            /* ========= MANEJO DE EDUCATIVO ========= */
            if ($esEducativo) {
                // 1. Archivo educativo
                if ($request->hasFile('archivo')) {
                    // Eliminar archivos existentes
                    $archivosExistentes = ArchivoSolicitudAsistencia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->get();

                    // Eliminar archivos físicos
                    foreach ($archivosExistentes as $archivo) {
                        if ($archivo->archivo_solicitud && file_exists(public_path($archivo->archivo_solicitud))) {
                            unlink(public_path($archivo->archivo_solicitud));
                        }
                    }

                    // Eliminar registros de BD
                    ArchivoSolicitudAsistencia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->delete();

                    // OBTENER DATOS ANTES DE MOVER EL ARCHIVO
                    $file = $request->file('archivo');
                    $tipoArchivo = $file->getClientMimeType();
                    $tamanoArchivo = $file->getSize();
                    $nombreArchivo = $this->generarNombreArchivo($file, 'educativo_' . $solicitud->id_solicitud_asistencia);
                    $rutaRelativa = $this->rutaBaseArchivos . $nombreArchivo;

                    // Mover el archivo
                    $file->move(public_path($this->rutaBaseArchivos), $nombreArchivo);

                    // Guardar en BD con los datos obtenidos ANTES de mover
                    ArchivoSolicitudAsistencia::create([
                        'id_solicitud_asistencia' => $solicitud->id_solicitud_asistencia,
                        'archivo_solicitud' => $rutaRelativa,
                        'tipo_archivo' => $tipoArchivo,
                        'espacio_archivo' => $tamanoArchivo,
                    ]);
                }

                // 2. Imagen opcional
                if ($request->hasFile('imagen_opcional')) {
                    // Eliminar imágenes existentes (solo las opcionales)
                    $imagenesExistentes = ImagenSolicitudAsistencia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->get();

                    foreach ($imagenesExistentes as $img) {
                        if ($img->imagen && file_exists(public_path($img->imagen))) {
                            unlink(public_path($img->imagen));
                        }
                    }

                    ImagenSolicitudAsistencia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->delete();

                    // OBTENER DATOS ANTES DE MOVER EL ARCHIVO
                    $file = $request->file('imagen_opcional');
                    $nombreArchivo = $this->generarNombreArchivo($file, 'opcional_' . $solicitud->id_solicitud_asistencia);
                    $rutaRelativa = $this->rutaBaseImagenes . $nombreArchivo;

                    // Mover el archivo
                    $file->move(public_path($this->rutaBaseImagenes), $nombreArchivo);

                    // Guardar en BD
                    ImagenSolicitudAsistencia::create([
                        'id_solicitud_asistencia' => $solicitud->id_solicitud_asistencia,
                        'imagen' => $rutaRelativa,
                    ]);
                }

                // 3. Días educativos - Eliminar existentes primero
                SolicitudAsistenciaDia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->delete();

                $inicio = Carbon::parse($data['rango_inicio_tiempo']);
                $fin    = Carbon::parse($data['rango_final_tiempo']);

                // Validar rango temporal
                if ($fin->lessThanOrEqualTo($inicio)) {
                    throw new Exception('El rango final debe ser mayor que el inicio.');
                }

                // Contador de días válidos
                $diasValidos = 0;

                foreach ($data['dias'] as $diaData) {
                    $todo = isset($diaData['es_todo_el_dia']) && $diaData['es_todo_el_dia'] == '1';

                    $horaEntrada = $this->normalizarHora($diaData['hora_entrada'] ?? null);
                    $horaSalida  = $this->normalizarHora($diaData['hora_salida'] ?? null);
                    $horaLlegada = $this->normalizarHora($diaData['hora_llegada_trabajo'] ?? null);

                    // 🔥 REGLA CLAVE
                    $tieneHoras = $horaEntrada && $horaSalida && $horaLlegada;

                    // ❌ SI NO ES TODO EL DÍA Y NO TIENE HORAS → NO INSERTAR
                    if (!$todo && !$tieneHoras) {
                        continue;
                    }

                    $diasValidos++;

                    $diasMap = [
                        'lunes' => 1,
                        'martes' => 2,
                        'miercoles' => 3,
                        'jueves' => 4,
                        'viernes' => 5,
                        'sabado' => 6,
                    ];

                    $diaISO = $diasMap[strtolower($diaData['dia'])] ?? null;
                    if (!$diaISO) continue;

                    $fechaActual = $inicio->copy();
                    while ((int)$fechaActual->format('N') !== $diaISO) {
                        $fechaActual->addDay();
                        if ($fechaActual->greaterThan($fin)) break;
                    }

                    while ($fechaActual->lte($fin)) {
                        SolicitudAsistenciaDia::create([
                            'id_solicitud_asistencia' => $solicitud->id_solicitud_asistencia,
                            'fecha' => $fechaActual->toDateString(),
                            'es_todo_el_dia' => $todo ? 1 : 0,
                            'hora_entrada' => $todo ? null : $horaEntrada,
                            'hora_salida' => $todo ? null : $horaSalida,
                            'hora_llegada_trabajo' => $todo ? null : $horaLlegada,
                            'observacion' => $diaData['observacion'] ?? null,
                        ]);

                        $fechaActual->addWeek();
                    }
                }

                // Validar que al menos haya un día válido
                if ($diasValidos === 0) {
                    throw new Exception('Debe especificar al menos un día de estudio (todo el día o con horario).');
                }
            }

            DB::commit();

            $mensaje = $solicitud->wasRecentlyCreated
                ? 'Solicitud creada correctamente'
                : 'Solicitud actualizada correctamente';

            return redirect()
                ->route('administracion.solicitud-asistencia.index')
                ->with('success', $mensaje);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar solicitud: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());

            return back()
                ->with('error', 'Error al procesar la solicitud: ' . $e->getMessage())
                ->withInput();
        }
    }

    /* =====================================================
     * FUNCIONES AUXILIARES
     * ===================================================== */

    /**
     * Normaliza el nombre del día (elimina acentos y convierte a minúscula)
     */
    private function normalizarNombreDia($dia)
    {
        return str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ü'],
            ['a', 'e', 'i', 'o', 'u', 'u'],
            strtolower($dia)
        );
    }

    /**
     * Parsear hora
     */
    private function parseHora(string $hora): Carbon
    {
        $hora = trim($hora);

        // 24h: 19:30
        if (preg_match('/^\d{1,2}:\d{2}$/', $hora)) {
            return Carbon::createFromFormat('H:i', $hora);
        }

        // 12h: 07:30 PM
        if (preg_match('/^\d{1,2}:\d{2}\s?(AM|PM)$/i', $hora)) {
            return Carbon::createFromFormat('h:i A', strtoupper(str_replace('  ', ' ', $hora)));
        }

        throw new \InvalidArgumentException("Hora inválida: {$hora}");
    }

    /**
     * Normaliza hora a formato HH:mm:ss
     */
    private function normalizarHora($hora)
    {
        if (empty($hora)) {
            return null;
        }

        // Si ya está en formato HH:mm:ss
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $hora)) {
            return $hora;
        }

        // Si está en formato HH:mm
        if (preg_match('/^\d{2}:\d{2}$/', $hora)) {
            return $hora . ':00';
        }

        return null;
    }

    /**
     * Crea una notificación para una solicitud
     */
    private function crearNotificacion($idSolicitudAsistencia, $tipo)
    {
        return NotificacionSolicitudAsistencia::create([
            'id_solicitud_asistencia' => $idSolicitudAsistencia,
            'estado_web' => 0, // 0 = no leído, 1 = leído
            'estado_app' => 0, // 0 = no leído, 1 = leído
            'fecha' => now(),
            'tipo' => $tipo,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Genera nombre único para archivo
     */
    private function generarNombreArchivo($file, $prefijo)
    {
        $extension = $file->getClientOriginalExtension();
        $nombreBase = Str::slug($prefijo) . '_' . time() . '_' . Str::random(8);
        $nombre = $nombreBase . '.' . $extension;

        // Verificar si ya existe
        $contador = 1;
        while (
            file_exists(public_path($this->rutaBaseArchivos . $nombre)) ||
            file_exists(public_path($this->rutaBaseImagenes . $nombre))
        ) {
            $nombre = $nombreBase . '_' . $contador . '.' . $extension;
            $contador++;
        }

        return $nombre;
    }

    /**
     * Eliminar archivos físicos de una solicitud
     */
    private function eliminarArchivosFisicos($solicitud)
    {
        // Eliminar archivos educativos
        $archivos = ArchivoSolicitudAsistencia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->get();
        foreach ($archivos as $archivo) {
            if ($archivo->archivo_solicitud && file_exists(public_path($archivo->archivo_solicitud))) {
                @unlink(public_path($archivo->archivo_solicitud));
            }
        }

        // Eliminar imágenes
        $imagenes = ImagenSolicitudAsistencia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->get();
        foreach ($imagenes as $imagen) {
            if ($imagen->imagen && file_exists(public_path($imagen->imagen))) {
                @unlink(public_path($imagen->imagen));
            }
        }
    }

    /**
     * Métodos para manejar notificaciones
     */

    /**
     * Marcar notificación como leída en web
     */
    public function marcarNotificacionLeidaWeb($idNotificacion)
    {
        $notificacion = NotificacionSolicitudAsistencia::findOrFail($idNotificacion);
        $notificacion->update(['estado_web' => 1]);

        return response()->json(['success' => true]);
    }

    /**
     * Marcar notificación como leída en app
     */
    public function marcarNotificacionLeidaApp($idNotificacion)
    {
        $notificacion = NotificacionSolicitudAsistencia::findOrFail($idNotificacion);
        $notificacion->update(['estado_app' => 1]);

        return response()->json(['success' => true]);
    }

    /**
     * Obtener notificaciones no leídas para web
     */
    public function obtenerNotificacionesNoLeidasWeb()
    {
        $notificaciones = NotificacionSolicitudAsistencia::with('solicitudAsistencia')
            ->where('estado_web', 0)
            ->orderBy('fecha', 'desc')
            ->limit(20)
            ->get();

        return response()->json($notificaciones);
    }

    /**
     * Obtener notificaciones no leídas para app
     */
    public function obtenerNotificacionesNoLeidasApp()
    {
        $notificaciones = NotificacionSolicitudAsistencia::with('solicitudAsistencia')
            ->where('estado_app', 0)
            ->orderBy('fecha', 'desc')
            ->limit(20)
            ->get();

        return response()->json($notificaciones);
    }
}
