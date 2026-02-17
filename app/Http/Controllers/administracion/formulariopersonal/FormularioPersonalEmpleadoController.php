<?php

namespace App\Http\Controllers\administracion\formulariopersonal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Tipodocumento;
use App\Models\Sexo;
use App\Models\Usuario;
use App\Models\UsuarioDocumentoCheck;
use App\Models\UsuarioFichaGeneral;
use App\Models\UsuarioEstudio;
use App\Models\UsuarioFamilia;
use App\Models\UsuarioSalud;
use App\Models\UsuarioEmergenciaContacto;
use App\Models\UsuarioDocumentoArchivo;
use App\Models\UsuarioNotificacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Services\WsBridge;

class FormularioPersonalEmpleadoController extends Controller
{

    public function create(Request $request)
    {
        // ✅ viene del middleware ValidateFormLinkToken
        $formLinkId = $request->attributes->get('form_link_id'); // opcional
        $createdBy  = $request->attributes->get('form_link_created_by'); // opcional

        // Cargar datos para los selects
        $tiposDocumento = Tipodocumento::all();
        $sexos = Sexo::all();

        // Cargar datos de ubigeo desde el JSON
        $departamentos = json_decode(
            file_get_contents(public_path('ubigeos/departamentos.json')),
            true
        );

        return view('administracion.formulariopersonal.create', compact(
            'tiposDocumento',
            'sexos',
            'departamentos',
            'formLinkId', // ✅ para enviarlo oculto en el form (recomendado)
            'createdBy'   // opcional
        ));
    }
    /**
     * Guardar todo el formulario completo
     */
    public function store(Request $request)
    {
        // ✅ Debug para que el JSON te diga EXACTAMENTE qué falló
        $debug = [
            'tx'       => ['committed' => false],
            'link'     => ['ok' => false, 'id' => null, 'error' => null],
            'notif'    => ['ok' => false, 'id' => null, 'error' => null],
            'ws'       => ['ok' => false, 'error' => null, 'payload' => null],
        ];

        try {
            DB::beginTransaction();

            // ========== 1. VALIDACIÓN DE DATOS ==========
            $validator = Validator::make($request->all(), [
                // ✅ LINK (obligatorio para invalidar al guardar)
                'form_link_id' => 'required|integer|exists:form_links,id',

                // SECCIÓN 1 - DATOS PERSONALES (OBLIGATORIOS)
                'paterno' => 'required|string|max:255',
                'materno' => 'nullable|string|max:255',
                'nombres' => 'required|string|max:255',
                'dia' => 'required|numeric|min:1|max:31',
                'mes' => 'required|string|min:2|max:2',
                'anio' => 'required|numeric|min:1900|max:' . date('Y'),
                'idTipoDocumento' => 'required|exists:tipodocumento,idTipoDocumento',
                'num_documento' => 'required|string|max:20|unique:usuarios,documento',
                'email' => 'required|email|max:255|unique:usuarios,correo',
                'telefono' => 'required|string|max:20',

                // UBIGEO NACIMIENTO
                'nacimientoDepartamento' => 'nullable|string|max:255',
                'nacimientoProvincia' => 'nullable|string|max:255',
                'nacimientoDistrito' => 'nullable|string|max:255',

                // SEXO, ESTADO CIVIL
                'idSexo' => 'nullable|exists:sexo,idSexo',
                'estado_civil' => 'nullable|in:S,C,V,D',

                // DOMICILIO
                'direccion' => 'nullable|string|max:255',
                'domicilioDepartamento' => 'nullable|string|max:255',
                'domicilioProvincia' => 'nullable|string|max:255',
                'domicilioDistrito' => 'nullable|string|max:255',

                // BANCARIO
                'entidadBancaria' => 'nullable|string|max:255',
                'tipoCuenta' => 'nullable|string|max:100',
                'moneda' => 'nullable|string|max:50|in:PEN,USD',
                'numeroCuenta' => 'nullable|string|max:100',
                'numeroCCI' => 'nullable|string|max:100',

                // SEGURO Y PENSIÓN
                'seguroSalud' => 'nullable|in:SIS,ESSALUD,EPS',
                'sistemaPensiones' => 'nullable|in:ONP,AFP,NA',
                'afpCompania' => 'required_if:sistemaPensiones,AFP|nullable|in:Integra,Horizonte,Profuturo,Prima',

                // SECCIÓN 2 - INFORMACIÓN ACADÉMICA (NAMES SIMPLES)
                'nivel_0' => 'nullable|in:SECUNDARIA,TECNICO,UNIVERSITARIO,POSTGRADO',
                'nivel_1' => 'nullable|in:SECUNDARIA,TECNICO,UNIVERSITARIO,POSTGRADO',
                'nivel_2' => 'nullable|in:SECUNDARIA,TECNICO,UNIVERSITARIO,POSTGRADO',
                'nivel_3' => 'nullable|in:SECUNDARIA,TECNICO,UNIVERSITARIO,POSTGRADO',

                'termino_0' => 'nullable|in:SI,NO',
                'termino_1' => 'nullable|in:SI,NO',
                'termino_2' => 'nullable|in:SI,NO',
                'termino_3' => 'nullable|in:SI,NO',

                'centro_0' => 'nullable|string|max:255',
                'centro_1' => 'nullable|string|max:255',
                'centro_2' => 'nullable|string|max:255',
                'centro_3' => 'nullable|string|max:255',

                'especialidad_0' => 'nullable|string|max:255',
                'especialidad_1' => 'nullable|string|max:255',
                'especialidad_2' => 'nullable|string|max:255',
                'especialidad_3' => 'nullable|string|max:255',

                'grado_0' => 'nullable|string|max:255',
                'grado_1' => 'nullable|string|max:255',
                'grado_2' => 'nullable|string|max:255',
                'grado_3' => 'nullable|string|max:255',

                'inicio_0' => 'nullable|date',
                'inicio_1' => 'nullable|date',
                'inicio_2' => 'nullable|date',
                'inicio_3' => 'nullable|date',

                'fin_0' => 'nullable|date|after_or_equal:inicio_0',
                'fin_1' => 'nullable|date|after_or_equal:inicio_1',
                'fin_2' => 'nullable|date|after_or_equal:inicio_2',
                'fin_3' => 'nullable|date|after_or_equal:inicio_3',

                // SECCIÓN 3 - INFORMACIÓN FAMILIAR (NAMES SIMPLES)
                'parentesco_*' => 'nullable|in:conyuge,concubino,hijo',
                'nombres_*' => 'nullable|string|max:255',
                'documento_*' => 'nullable|string|max:20',
                'ocupacion_*' => 'nullable|string|max:255',
                'sexo_*' => 'nullable|in:M,F',
                'fecha_nacimiento_*' => 'nullable|date',
                'domicilio_*' => 'nullable|string|max:255',

                // SECCIÓN 4 - INFORMACIÓN DE SALUD
                'vacuna_covid' => 'nullable|in:SI,NO',
                'covid_dosis1' => 'nullable|date|required_if:vacuna_covid,SI',
                'covid_dosis2' => 'nullable|date|required_if:vacuna_covid,SI',
                'covid_dosis3' => 'nullable|date',

                'tiene_operacion' => 'nullable|in:SI,NO',
                'operacion_especificar' => 'nullable|string|max:500|required_if:tiene_operacion,SI',

                'dolencia_cronica' => 'nullable|in:SI,NO',
                'dolencia_especificar' => 'nullable|string|max:500|required_if:dolencia_cronica,SI',

                'discapacidad' => 'nullable|in:SI,NO',
                'discapacidad_especificar' => 'nullable|string|max:500|required_if:discapacidad,SI',

                'tipo_sangre' => 'nullable|string|max:10|in:A+,A-,B+,B-,AB+,AB-,O+,O-',

                // CONTACTOS DE EMERGENCIA
                'emergencia1_nombres' => 'required|string|max:255',
                'emergencia1_parentesco' => 'required|string|max:100',
                'emergencia1_direccion' => 'required|string',
                'emergencia2_nombres' => 'required|string|max:255',
                'emergencia2_parentesco' => 'required|string|max:100',
                'emergencia2_direccion' => 'required|string',

                // SECCIÓN 5 - DECLARACIÓN JURADA
                'dia_declaracion' => 'required|numeric|min:1|max:31',
                'mes_declaracion' => 'required|string',
                'anio_declaracion' => 'required|numeric|min:2020|max:2030',
                'dni_declaracion' => 'required|string|size:8',
                'acepto_declaracion' => 'required|accepted',
            ], [
                'form_link_id.required' => 'Link inválido: falta form_link_id.',
                'form_link_id.exists' => 'Link inválido o no encontrado.',
                'num_documento.unique' => 'El número de documento ya está registrado en el sistema.',
                'email.unique' => 'El correo electrónico ya está registrado en el sistema.',
                'covid_dosis1.required_if' => 'La fecha de la primera dosis es obligatoria cuando indica que fue vacunado.',
                'covid_dosis2.required_if' => 'La fecha de la segunda dosis es obligatoria cuando indica que fue vacunado.',
                'operacion_especificar.required_if' => 'Debe especificar la(s) operación(es) realizada(s).',
                'dolencia_especificar.required_if' => 'Debe especificar la(s) dolencia(s) crónica(s).',
                'discapacidad_especificar.required_if' => 'Debe especificar el tipo de discapacidad.',
                'emergencia1_nombres.required' => 'El primer contacto de emergencia es obligatorio.',
                'emergencia2_nombres.required' => 'El segundo contacto de emergencia es obligatorio.',
                'dni_declaracion.size' => 'El DNI debe tener 8 dígitos.',
                'acepto_declaracion.accepted' => 'Debe aceptar la declaración jurada para enviar el formulario.',
                'fin_0.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
                'fin_1.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
                'fin_2.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
                'fin_3.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'debug_notificacion_ws' => $debug,
                ], 422);
            }

            // ========== 1.5 VALIDAR LINK (EXPIRE/USO) + LOCK ==========
            $formLinkId = (int) $request->input('form_link_id');
            $linkRow = DB::table('form_links')
                ->where('id', $formLinkId)
                ->lockForUpdate()
                ->first();

            if (!$linkRow) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Link inválido o no encontrado.',
                    'debug_notificacion_ws' => $debug,
                ], 403);
            }

            $debug['link']['id'] = $formLinkId;

            if (!empty($linkRow->used_at)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Este link ya fue utilizado.',
                    'debug_notificacion_ws' => $debug,
                ], 403);
            }

            if (!empty($linkRow->expires_at) && now()->greaterThan($linkRow->expires_at)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Este link expiró.',
                    'debug_notificacion_ws' => $debug,
                ], 403);
            }

            $debug['link']['ok'] = true;

            // ========== 2. CREAR O ACTUALIZAR USUARIO ==========
            $fechaNacimiento = $request->anio . '-' . $request->mes . '-' . str_pad($request->dia, 2, '0', STR_PAD_LEFT);

            $username = strtolower(substr($request->nombres, 0, 1) . $request->paterno . substr($request->num_documento, -4));
            $defaultPassword = bcrypt($request->num_documento);

            $usuario = Usuario::updateOrCreate(
                ['documento' => $request->num_documento],
                [
                    'apellidoPaterno' => strtoupper($request->paterno),
                    'apellidoMaterno' => strtoupper($request->materno ?? ''),
                    'Nombre' => strtoupper($request->nombres),
                    'fechaNacimiento' => $fechaNacimiento,
                    'telefono' => $request->telefono,
                    'correo' => $request->email,
                    'correo_personal' => $request->email,
                    'usuario' => $username,
                    'clave' => $defaultPassword,
                    'nacionalidad' => $request->nacionalidad ?? 'Peruana',
                    'departamento' => $request->domicilioDepartamento,
                    'provincia' => $request->domicilioProvincia,
                    'distrito' => $request->domicilioDistrito,
                    'direccion' => $request->direccion,
                    'documento' => $request->num_documento,
                    'idTipoDocumento' => $request->idTipoDocumento,
                    'idTipoUsuario' => 2,
                    'idSexo' => $request->idSexo,
                    'idRol' => 2,
                    'estadocivil' => $this->mapEstadoCivil($request->estado_civil),
                    'estado' => 1,
                ]
            );

            // ========== 3. GUARDAR FICHA GENERAL ==========
            UsuarioFichaGeneral::updateOrCreate(
                ['idUsuario' => $usuario->idUsuario],
                [
                    'nacimientoDepartamento' => $request->nacimientoDepartamento,
                    'nacimientoProvincia' => $request->nacimientoProvincia,
                    'nacimientoDistrito' => $request->nacimientoDistrito,
                    'estadoCivil' => $this->mapEstadoCivil($request->estado_civil),
                    'telefonoFijo' => $request->telefono_fijo,
                    'domicilioVia' => $request->direccion,
                    'domicilioMzLt' => $request->domicilio_mz_lt,
                    'domicilioUrb' => $request->domicilio_urb,
                    'domicilioDepartamento' => $request->domicilioDepartamento,
                    'domicilioProvincia' => $request->domicilioProvincia,
                    'domicilioDistrito' => $request->domicilioDistrito,
                    'entidadBancaria' => $request->entidadBancaria,
                    'tipoCuenta' => $request->tipoCuenta,
                    'moneda' => $request->moneda,
                    'numeroCuenta' => $request->numeroCuenta,
                    'numeroCCI' => $request->numeroCCI,
                    'seguroSalud' => $request->seguroSalud,
                    'sistemaPensiones' => $request->sistemaPensiones,
                    'afpCompania' => $request->afpCompania,
                    'createdAt' => now(),
                    'updatedAt' => now(),
                ]
            );

            // ========== 4. GUARDAR ESTUDIOS ==========
            UsuarioEstudio::where('idUsuario', $usuario->idUsuario)->delete();
            for ($nivelId = 0; $nivelId <= 3; $nivelId++) {
                $termino = $request->input("termino_{$nivelId}");
                if ($termino === 'SI') {
                    $centro = $request->input("centro_{$nivelId}");
                    $inicio = $request->input("inicio_{$nivelId}");
                    if (!empty($centro) && !empty($inicio)) {
                        UsuarioEstudio::create([
                            'idUsuario' => $usuario->idUsuario,
                            'nivel' => $request->input("nivel_{$nivelId}"),
                            'termino' => 1,
                            'centroEstudios' => $centro,
                            'especialidad' => $request->input("especialidad_{$nivelId}"),
                            'gradoAcademico' => $request->input("grado_{$nivelId}"),
                            'fechaInicio' => $inicio,
                            'fechaFin' => $request->input("fin_{$nivelId}"),
                        ]);
                    }
                }
            }

            // ========== 5. GUARDAR FAMILIARES ==========
            UsuarioFamilia::where('idUsuario', $usuario->idUsuario)->delete();
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'nombres_') === 0 && !strpos($key, 'mobile')) {
                    $index = str_replace('nombres_', '', $key);
                    if (is_numeric($index)) {
                        $nombres = $request->input("nombres_{$index}");
                        $parentesco = $request->input("parentesco_{$index}");
                        if (!empty($nombres) && !empty($parentesco)) {
                            UsuarioFamilia::create([
                                'idUsuario' => $usuario->idUsuario,
                                'parentesco' => $this->mapParentesco($parentesco),
                                'apellidosNombres' => $nombres,
                                'nroDocumento' => $request->input("documento_{$index}"),
                                'ocupacion' => $request->input("ocupacion_{$index}"),
                                'sexo' => $request->input("sexo_{$index}"),
                                'fechaNacimiento' => $request->input("fecha_nacimiento_{$index}"),
                                'domicilioActual' => $request->input("domicilio_{$index}"),
                            ]);
                        }
                    }
                }
            }

            // ========== 6. GUARDAR SALUD ==========
            UsuarioSalud::updateOrCreate(
                ['idUsuario' => $usuario->idUsuario],
                [
                    'vacunaCovid' => $request->vacuna_covid === 'SI' ? 1 : ($request->vacuna_covid ? 0 : null),
                    'covidDosis1' => $request->covid_dosis1,
                    'covidDosis2' => $request->covid_dosis2,
                    'covidDosis3' => $request->covid_dosis3 ?? null,
                    'dolenciaCronica' => $request->dolencia_cronica === 'SI' ? 1 : ($request->dolencia_cronica ? 0 : null),
                    'dolenciaDetalle' => $request->dolencia_especificar,
                    'discapacidad' => $request->discapacidad === 'SI' ? 1 : ($request->discapacidad ? 0 : null),
                    'discapacidadDetalle' => $request->discapacidad_especificar,
                    'tipoSangre' => $request->tipo_sangre,
                ]
            );

            // ========== 7. GUARDAR CONTACTOS EMERGENCIA ==========
            UsuarioEmergenciaContacto::where('idUsuario', $usuario->idUsuario)->delete();

            if ($request->emergencia1_nombres) {
                UsuarioEmergenciaContacto::create([
                    'idUsuario' => $usuario->idUsuario,
                    'apellidosNombres' => $request->emergencia1_nombres,
                    'parentesco' => $request->emergencia1_parentesco,
                    'direccionTelefono' => $request->emergencia1_direccion,
                ]);
            }

            if ($request->emergencia2_nombres) {
                UsuarioEmergenciaContacto::create([
                    'idUsuario' => $usuario->idUsuario,
                    'apellidosNombres' => $request->emergencia2_nombres,
                    'parentesco' => $request->emergencia2_parentesco,
                    'direccionTelefono' => $request->emergencia2_direccion,
                ]);
            }

            for ($i = 3; $i <= 5; $i++) {
                $nombres = $request->input("emergencia{$i}_nombres");
                if ($nombres) {
                    UsuarioEmergenciaContacto::create([
                        'idUsuario' => $usuario->idUsuario,
                        'apellidosNombres' => $nombres,
                        'parentesco' => $request->input("emergencia{$i}_parentesco"),
                        'direccionTelefono' => $request->input("emergencia{$i}_direccion"),
                    ]);
                }
            }

            // ========== 8. GUARDAR FIRMA ==========
            if ($request->hasFile('firma')) {
                $firma = $request->file('firma');
                $usuario->firma = file_get_contents($firma->getRealPath());
                $usuario->save();

                UsuarioDocumentoArchivo::updateOrCreate(
                    ['idUsuario' => $usuario->idUsuario, 'tipoDocumento' => 'HUELLA'],
                    [
                        'nombreArchivo' => $firma->getClientOriginalName(),
                        'mimeType' => $firma->getMimeType(),
                        'contenido' => file_get_contents($firma->getRealPath()),
                    ]
                );
            }

            // ========== 9. GUARDAR DOCUMENTOS CHECK ==========
            UsuarioDocumentoCheck::updateOrCreate(
                ['idUsuario' => $usuario->idUsuario],
                [
                    'cv' => $request->has('doc_cv') ? 1 : 0,
                    'dniVigente' => 1,
                    'carnetVacunacion' => $request->vacuna_covid === 'SI' ? 1 : 0,
                    'declaracionJuradaDomicilio' => $request->acepto_declaracion ? 1 : 0,
                ]
            );

            // ========== 10. CREAR NOTIFICACIÓN (DENTRO TX) ==========
            $idNotifUserNew = null;
            try {
                $noti = UsuarioNotificacion::create([
                    'idUsuario'   => $usuario->idUsuario,
                    'estado_web'  => '0',
                    'estado_app'  => '0',
                    'fecha'       => now(),
                    'tipo'        => 'REGISTRO_USUARIO_CREADO',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);

                $idNotifUserNew = (int) ($noti->idNotificacionUsuario ?? $noti->getKey() ?? 0);

                if ($idNotifUserNew <= 0) {
                    throw new \Exception('No se pudo obtener idNotificacionUsuario.');
                }

                $debug['notif'] = ['ok' => true, 'id' => $idNotifUserNew, 'error' => null];
            } catch (\Throwable $e) {
                $debug['notif'] = ['ok' => false, 'id' => null, 'error' => $e->getMessage()];
                Log::channel('single')->error('[USU] ❌ error creando notificación: ' . $e->getMessage());
            }

            // ========== 10.1 INVALIDAR LINK (ANTES DEL COMMIT) ==========
            // ✅ marca used_at y rota token_hash para que el token anterior sea inútil
            $deadToken = bin2hex(random_bytes(32));
            $deadHash = hash('sha256', $deadToken);

            DB::table('form_links')
                ->where('id', $formLinkId)
                ->update([
                    'used_at' => now(),
                    'token_hash' => $deadHash,
                ]);

            // ✅ Commit UNA SOLA VEZ
            DB::commit();
            $debug['tx']['committed'] = true;

            // ========== 10.5 WS (DESPUÉS DEL COMMIT) ==========
            if (!empty($debug['notif']['id'])) {
                $payload = [
                    'type' => 'creacion_usuario_evento',
                    'idNotificacion' => (int) $debug['notif']['id'],
                    'idNotificacionUsuario' => (int) $debug['notif']['id'],
                    'idUsuarioCreado' => (int) $usuario->idUsuario,
                    'tipoNotificacionForzada' => 'REGISTRO_USUARIO_CREADO',
                    'nombreUsuarioCreado' => trim($usuario->apellidoPaterno . ' ' . $usuario->apellidoMaterno . ' ' . $usuario->Nombre),
                    'idUsuarioCreador' => (int) (auth()->id() ?? 0),
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
                $debug['ws']['error'] = 'No se envió WS porque no existe idNotificacionUsuario.';
            }

            // ========== 11. RESPUESTA DE ÉXITO ==========
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Formulario guardado exitosamente',
                    'data' => [
                        'idUsuario' => $usuario->idUsuario,
                        'nombre_completo' => $usuario->apellidoPaterno . ' ' . $usuario->apellidoMaterno . ', ' . $usuario->Nombre,
                        'documento' => $usuario->documento,
                        'usuario' => $usuario->usuario,
                        'idNotificacionUsuario' => $debug['notif']['id'],
                        'tipo_notificacion' => 'REGISTRO_USUARIO_CREADO',
                    ],
                    'debug_notificacion_ws' => $debug,
                ]);
            }

            return redirect()->route('admin.formulario-pelicula.exito')
                ->with('success', 'Formulario guardado exitosamente')
                ->with('usuario_id', $usuario->idUsuario);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::channel('single')->error('ERROR EN FORMULARIO: ' . $e->getMessage());
            Log::channel('single')->error('LINE: ' . $e->getLine());
            Log::channel('single')->error('FILE: ' . $e->getFile());

            $debug['link']['ok'] = $debug['link']['ok'] ?? false;
            $debug['link']['error'] = $debug['link']['error'] ?? null;

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar el formulario: ' . $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                    'debug_notificacion_ws' => $debug,
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Error al guardar el formulario: ' . $e->getMessage());
        }
    }



    /**
     * Guardar borrador del formulario
     */
    public function saveDraft(Request $request)
    {
        try {
            DB::beginTransaction();

            $usuario = null;
            if ($request->num_documento) {
                $usuario = Usuario::where('documento', $request->num_documento)->first();
            }

            if (!$usuario && $request->num_documento) {
                $fechaNacimiento = $request->anio && $request->mes && $request->dia
                    ? $request->anio . '-' . $request->mes . '-' . str_pad($request->dia, 2, '0', STR_PAD_LEFT)
                    : null;

                $usuario = Usuario::create([
                    'apellidoPaterno' => $request->paterno ?? 'TEMPORAL',
                    'apellidoMaterno' => $request->materno ?? '',
                    'Nombre' => $request->nombres ?? 'USUARIO',
                    'fechaNacimiento' => $fechaNacimiento,
                    'telefono' => $request->telefono,
                    'correo' => $request->email ?? 'temp_' . uniqid() . '@temp.com',
                    'usuario' => 'temp_' . uniqid(),
                    'clave' => bcrypt(uniqid()),
                    'documento' => $request->num_documento,
                    'idTipoDocumento' => $request->idTipoDocumento ?? 1,
                    'idTipoUsuario' => 2,
                    'estado' => 0,
                ]);
            }

            if (!$usuario) {
                throw new \Exception('No se pudo identificar al usuario para guardar el borrador');
            }

            $fichaData = array_filter([
                'nacimientoDepartamento' => $request->nacimientoDepartamento,
                'nacimientoProvincia' => $request->nacimientoProvincia,
                'nacimientoDistrito' => $request->nacimientoDistrito,
                'estadoCivil' => $this->mapEstadoCivil($request->estado_civil),
                'telefonoFijo' => $request->telefono_fijo,
                'domicilioVia' => $request->direccion,
                'domicilioDepartamento' => $request->domicilioDepartamento,
                'domicilioProvincia' => $request->domicilioProvincia,
                'domicilioDistrito' => $request->domicilioDistrito,
                'entidadBancaria' => $request->entidadBancaria,
                'tipoCuenta' => $request->tipoCuenta,
                'moneda' => $request->moneda,
                'numeroCuenta' => $request->numeroCuenta,
                'numeroCCI' => $request->numeroCCI,
                'seguroSalud' => $request->seguroSalud,
                'sistemaPensiones' => $request->sistemaPensiones,
                'afpCompania' => $request->afpCompania,
            ], function ($value) {
                return !is_null($value) && $value !== '';
            });

            if (!empty($fichaData)) {
                UsuarioFichaGeneral::updateOrCreate(
                    ['idUsuario' => $usuario->idUsuario],
                    $fichaData
                );
            }

            $saludData = array_filter([
                'vacunaCovid' => $request->vacuna_covid === 'SI' ? 1 : ($request->vacuna_covid ? 0 : null),
                'covidDosis1' => $request->covid_dosis1,
                'covidDosis2' => $request->covid_dosis2,
                'covidDosis3' => $request->covid_dosis3,
                'dolenciaCronica' => $request->dolencia_cronica === 'SI' ? 1 : ($request->dolencia_cronica ? 0 : null),
                'dolenciaDetalle' => $request->dolencia_especificar,
                'discapacidad' => $request->discapacidad === 'SI' ? 1 : ($request->discapacidad ? 0 : null),
                'discapacidadDetalle' => $request->discapacidad_especificar,
                'tipoSangre' => $request->tipo_sangre,
            ], function ($value) {
                return !is_null($value) && $value !== '';
            });

            if (!empty($saludData)) {
                UsuarioSalud::updateOrCreate(
                    ['idUsuario' => $usuario->idUsuario],
                    $saludData
                );
            }

            DB::commit();

            // ========== CREAR NOTIFICACIÓN DE BORRADOR ==========
            try {
                if ($usuario->estado == 0) {
                    UsuarioNotificacion::create([
                        'idUsuario' => $usuario->idUsuario,
                        'estado_web' => '0',
                        'estado_app' => '0',
                        'fecha' => now(),
                        'tipo' => 'BORRADOR_GUARDADO',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Log::channel('single')->info('✅ NOTIFICACIÓN DE BORRADOR CREADA');
                }
            } catch (\Exception $e) {
                Log::channel('single')->error('❌ ERROR EN NOTIFICACIÓN DE BORRADOR: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Borrador guardado exitosamente',
                'usuario_id' => $usuario->idUsuario
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al guardar borrador: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener datos de un usuario para edición
     */
    public function show($id)
    {
        $usuario = Usuario::with([
            'fichaGeneral',
            'estudios',
            'familiares',
            'salud',
            'emergenciaContactos',
            'notificaciones'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $usuario
        ]);
    }

    /**
     * Mapear estado civil de letra a número
     */
    private function mapEstadoCivil($estado)
    {
        $map = [
            'S' => 1,
            'C' => 2,
            'V' => 3,
            'D' => 4,
        ];

        return $map[$estado] ?? null;
    }

    /**
     * Mapear parentesco del formulario a ENUM de base de datos
     */
    private function mapParentesco($parentesco)
    {
        $map = [
            'conyuge' => 'CONYUGE',
            'concubino' => 'CONCUBINO',
            'hijo' => 'HIJO',
        ];

        return $map[$parentesco] ?? null;
    }
}
