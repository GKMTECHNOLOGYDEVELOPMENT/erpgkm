<?php

namespace App\Http\Controllers\administracion\solicitudasistencia;

use App\Http\Controllers\Controller;
use App\Models\ArchivoSolicitudAsistencia;
use App\Models\EvaluarSolicitudAsistencia;
use App\Models\ImagenSolicitudAsistencia;
use App\Models\SolicitudAsistencia;
use App\Models\SolicitudAsistenciaDia;
use App\Models\TipoEducacion;
use App\Models\TipoSolicitudAsistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class SolicitudAsistenciaController extends Controller
{
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
            
            $diaNormalizado = str_replace(
                ['á', 'é', 'í', 'ó', 'ú', 'ü'],
                ['a', 'e', 'i', 'o', 'u', 'u'],
                strtolower($diaNombre)
            );

            $diasEdit[$diaNormalizado] = [
                'todo' => (bool) $d->es_todo_el_dia,
                'entrada' => $d->hora_entrada ? substr($d->hora_entrada, 0, 5) : null,
                'salida' => $d->hora_salida ? substr($d->hora_salida, 0, 5) : null,
                'llegada' => $d->hora_llegada_trabajo ? substr($d->hora_llegada_trabajo, 0, 5) : null,
                'observacion' => $d->observacion,
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
            SolicitudAsistenciaDia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->delete();
            ArchivoSolicitudAsistencia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->delete();
            ImagenSolicitudAsistencia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->delete();
            EvaluarSolicitudAsistencia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->delete();

            $solicitud->delete();
        });

        return redirect()
            ->route('administracion.solicitud-asistencia.index')
            ->with('success', 'Solicitud eliminada correctamente');
    }

    /* =========================
     * CAMBIAR ESTADO (NUEVO MÉTODO)
     * ========================= */
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => ['required', 'in:aprobado,denegado'],
            'comentario' => ['nullable', 'string', 'max:500'],
        ]);

        $solicitud = SolicitudAsistencia::findOrFail($id);

        DB::transaction(function () use ($request, $solicitud) {
            // Actualizar estado de la solicitud
            $solicitud->update([
                'estado' => $request->estado,
            ]);

            // Registrar en el historial de evaluación
            EvaluarSolicitudAsistencia::create([
                'id_solicitud_asistencia' => $solicitud->id_solicitud_asistencia,
                'id_tipo_solicitud' => $solicitud->id_tipo_solicitud,
                'estado' => $request->estado,
                'comentario' => $request->comentario,
                'id_usuario' => Auth::id(),
                'fecha' => now(),
            ]);
        });

        $estadoTexto = $request->estado == 'aprobado' ? 'aprobada' : 'denegada';
        return redirect()
            ->route('administracion.solicitud-asistencia.index')
            ->with('success', "Solicitud {$estadoTexto} correctamente");
    }

    /* =========================
     * VER DETALLES (NUEVO MÉTODO)
     * ========================= */
    public function show($id)
    {
        $solicitud = SolicitudAsistencia::with([
            'tipoSolicitud', 
            'tipoEducacion', 
            'dias',
            'archivos',
            'imagenes',
            'evaluaciones' => function($query) {
                $query->orderBy('fecha', 'desc');
            },
            'evaluaciones.usuario'
        ])->findOrFail($id);

        return view('administracion.solicitudasistencia.show', compact('solicitud'));
    }

    /* =====================================================
     * MÉTODO CENTRAL (STORE + UPDATE)
     * ===================================================== */
    private function saveSolicitud(Request $request, SolicitudAsistencia $solicitud = null)
    {
        $tipo = TipoSolicitudAsistencia::findOrFail($request->id_tipo_solicitud);
        $tipoNombre = mb_strtolower(trim($tipo->nombre_tip));

        $esEducativo = $tipoNombre === 'educativo';
        $esLicenciaMedica = in_array($tipoNombre, ['licencia médico', 'licencia medico'], true);
        $esUpdate = $solicitud !== null;

        /* ========= VALIDACIÓN ========= */
        $rules = [
            'id_tipo_solicitud'   => ['required', Rule::exists('tipo_solicitud_asistencia', 'id_tipo_solicitud')],
            'observacion'         => ['nullable', 'string'],
            'rango_inicio_tiempo' => ['required', 'date'],
            'rango_final_tiempo'  => ['required', 'date', 'after_or_equal:rango_inicio_tiempo'],
        ];

        if ($esLicenciaMedica) {
            $imagenRule = $esUpdate ? 'nullable' : 'required';
            if (!$esUpdate || $request->hasFile('imagen_licencia')) {
                $rules['imagen_licencia'] = [$imagenRule, 'image', 'mimes:jpg,jpeg,png', 'max:4096'];
            }
        }

        if ($esEducativo) {
            $rules += [
                'id_tipo_educacion' => ['required', Rule::exists('tipo_educacion', 'id_tipo_educacion')],
                'dias'              => ['required', 'array', 'min:1'],
                'dias.*.dia'        => ['required', 'string'],
                'dias.*.es_todo_el_dia' => ['nullable', 'boolean'],
                'dias.*.hora_entrada' => ['nullable', 'date_format:H:i'],
                'dias.*.hora_salida'  => ['nullable', 'date_format:H:i'],
                'dias.*.hora_llegada_trabajo' => ['nullable', 'date_format:H:i'],
                'dias.*.observacion' => ['nullable', 'string', 'max:255'],
            ];
            
            if (!$esUpdate) {
                $rules['archivo'] = ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
            } else {
                $rules['archivo'] = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
            }
            
            $rules['imagen_opcional'] = ['nullable', 'image', 'max:4096'];
        }

        $data = $request->validate($rules);

        DB::transaction(function () use ($request, $data, $esEducativo, $esLicenciaMedica, $esUpdate, &$solicitud) {

            if (!$solicitud) {
                $solicitud = SolicitudAsistencia::create([
                    'id_tipo_solicitud'   => $data['id_tipo_solicitud'],
                    'fecha_solicitud'     => now(),
                    'estado'              => 'pendiente',
                    'id_usuario'          => Auth::id(),
                ]);

                EvaluarSolicitudAsistencia::create([
                    'id_solicitud_asistencia' => $solicitud->id_solicitud_asistencia,
                    'id_tipo_solicitud'       => $solicitud->id_tipo_solicitud,
                    'estado'                  => 'pendiente',
                    'id_usuario'              => Auth::id(),
                    'fecha'                   => now(),
                ]);
            }

            $solicitud->update([
                'observacion'         => $data['observacion'] ?? null,
                'rango_inicio_tiempo' => Carbon::parse($data['rango_inicio_tiempo']),
                'rango_final_tiempo'  => Carbon::parse($data['rango_final_tiempo']),
                'id_tipo_educacion'   => $esEducativo ? $data['id_tipo_educacion'] : null,
            ]);

            if ($esEducativo) {
                SolicitudAsistenciaDia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->delete();
            }

            if ($esLicenciaMedica && $request->hasFile('imagen_licencia')) {
                ImagenSolicitudAsistencia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->delete();
                
                ImagenSolicitudAsistencia::create([
                    'id_solicitud_asistencia' => $solicitud->id_solicitud_asistencia,
                    'imagen' => $request->file('imagen_licencia')
                        ->store("solicitudes/{$solicitud->id_solicitud_asistencia}/imagenes", 'public'),
                ]);
            }

            if ($esEducativo) {
                if ($request->hasFile('archivo')) {
                    ArchivoSolicitudAsistencia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->delete();
                    
                    ArchivoSolicitudAsistencia::create([
                        'id_solicitud_asistencia' => $solicitud->id_solicitud_asistencia,
                        'archivo_solicitud' => $request->file('archivo')
                            ->store("solicitudes/{$solicitud->id_solicitud_asistencia}/archivos", 'public'),
                        'tipo_archivo' => $request->file('archivo')->getClientMimeType(),
                        'espacio_archivo' => $request->file('archivo')->getSize(),
                    ]);
                }

                if ($request->hasFile('imagen_opcional')) {
                    ImagenSolicitudAsistencia::where('id_solicitud_asistencia', $solicitud->id_solicitud_asistencia)->delete();
                    
                    ImagenSolicitudAsistencia::create([
                        'id_solicitud_asistencia' => $solicitud->id_solicitud_asistencia,
                        'imagen' => $request->file('imagen_opcional')
                            ->store("solicitudes/{$solicitud->id_solicitud_asistencia}/imagenes", 'public'),
                    ]);
                }

                $inicio = Carbon::parse($data['rango_inicio_tiempo']);
                $fin    = Carbon::parse($data['rango_final_tiempo']);

                foreach ($data['dias'] as $d) {
                    $todo = isset($d['es_todo_el_dia']) && $d['es_todo_el_dia'] == '1';
                    $tieneHoras = !empty($d['hora_entrada']) || !empty($d['hora_salida']);
                    
                    if (!$todo && !$tieneHoras) continue;

                    for ($f = $inicio->copy(); $f->lte($fin); $f->addDay()) {
                        $diaSemana = str_replace(
                            ['á','é','í','ó','ú'],
                            ['a','e','i','o','u'],
                            strtolower($f->locale('es')->dayName)
                        );
                        
                        if ($diaSemana !== strtolower($d['dia'])) continue;

                        SolicitudAsistenciaDia::create([
                            'id_solicitud_asistencia' => $solicitud->id_solicitud_asistencia,
                            'fecha' => $f->toDateString(),
                            'es_todo_el_dia' => $todo ? 1 : 0,
                            'hora_entrada' => $todo ? null : ($d['hora_entrada'] ?? null),
                            'hora_salida'  => $todo ? null : ($d['hora_salida'] ?? null),
                            'hora_llegada_trabajo' => $todo ? null : ($d['hora_llegada_trabajo'] ?? null),
                            'observacion' => $d['observacion'] ?? null,
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->route('administracion.solicitud-asistencia.index')
            ->with('success', $solicitud->wasRecentlyCreated
                ? 'Solicitud creada correctamente'
                : 'Solicitud actualizada correctamente');
    }
}