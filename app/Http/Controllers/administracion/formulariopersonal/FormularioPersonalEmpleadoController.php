<?php

namespace App\Http\Controllers\administracion\formulariopersonal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\TipoDocumento;
use App\Models\Sexo;
use App\Models\Usuario;
use App\Models\UsuarioDocumentoCheck;
use App\Models\UsuarioFichaGeneral;
use App\Models\UsuarioEstudio;
use App\Models\UsuarioFamilia;
use App\Models\UsuarioSalud;
use App\Models\UsuarioEmergenciaContacto;
use App\Models\UsuarioDocumentoArchivo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FormularioPersonalEmpleadoController extends Controller
{
    public function create()
    {
        // Cargar datos para los selects
        $tiposDocumento = TipoDocumento::all();
        $sexos = Sexo::all();
        
        // Cargar datos de ubigeo desde el JSON
        $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true);
        
        return view('administracion.formulariopersonal.create', compact(
            'tiposDocumento',
            'sexos',
            'departamentos'
        ));
    }
    
    /**
     * Guardar todo el formulario completo
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            // ========== 1. VALIDACIÓN DE DATOS ==========
            $validator = Validator::make($request->all(), [
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
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // ========== 2. CREAR O ACTUALIZAR USUARIO ==========
            $fechaNacimiento = $request->anio . '-' . $request->mes . '-' . str_pad($request->dia, 2, '0', STR_PAD_LEFT);
            
            // Generar usuario y clave por defecto
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

            // ========== 4. GUARDAR ESTUDIOS (NAMES SIMPLES) ==========
            Log::channel('single')->info('========== DEBUG ESTUDIOS ==========');
            Log::channel('single')->info('Usuario ID: ' . $usuario->idUsuario);
            
            // Eliminar estudios anteriores
            UsuarioEstudio::where('idUsuario', $usuario->idUsuario)->delete();
            Log::channel('single')->info('Estudios anteriores eliminados');
            
            $estudiosGuardados = 0;
            $niveles = [0, 1, 2, 3];
            $nombresNiveles = [
                0 => 'SECUNDARIA',
                1 => 'TECNICO',
                2 => 'UNIVERSITARIO',
                3 => 'POSTGRADO'
            ];
            
            foreach ($niveles as $nivelId) {
                $termino = $request->input("termino_{$nivelId}");
                $nivel = $request->input("nivel_{$nivelId}", $nombresNiveles[$nivelId]);
                
                Log::channel('single')->info("--- Procesando nivel {$nivelId}: {$nivel} ---");
                Log::channel('single')->info("Termino: " . ($termino ?? 'NO DEFINIDO'));
                
                if ($termino === 'SI') {
                    $centro = $request->input("centro_{$nivelId}");
                    $inicio = $request->input("inicio_{$nivelId}");
                    
                    if (!empty($centro) && !empty($inicio)) {
                        $dataToInsert = [
                            'idUsuario' => $usuario->idUsuario,
                            'nivel' => $nivel,
                            'termino' => 1,
                            'centroEstudios' => $centro,
                            'especialidad' => $request->input("especialidad_{$nivelId}"),
                            'gradoAcademico' => $request->input("grado_{$nivelId}"),
                            'fechaInicio' => $inicio,
                            'fechaFin' => $request->input("fin_{$nivelId}"),
                        ];
                        
                        try {
                            UsuarioEstudio::create($dataToInsert);
                            $estudiosGuardados++;
                            Log::channel('single')->info('✅ ESTUDIO GUARDADO');
                        } catch (\Exception $e) {
                            Log::channel('single')->error('❌ ERROR: ' . $e->getMessage());
                        }
                    }
                }
            }
            
            Log::channel('single')->info("📊 TOTAL: {$estudiosGuardados} estudios guardados");
            Log::channel('single')->info('========== FIN DEBUG ESTUDIOS ==========');

            // ========== 5. GUARDAR FAMILIARES (NAMES SIMPLES) ==========
            Log::channel('single')->info('========== DEBUG FAMILIARES ==========');
            Log::channel('single')->info('Usuario ID: ' . $usuario->idUsuario);
            
            // Eliminar familiares anteriores
            UsuarioFamilia::where('idUsuario', $usuario->idUsuario)->delete();
            Log::channel('single')->info('Familiares anteriores eliminados');
            
            $familiaresGuardados = 0;
            $indicesEncontrados = [];
            
            // Buscar todos los índices de nombres (solo desktop, no mobile)
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'nombres_') === 0 && !strpos($key, 'mobile')) {
                    $index = str_replace('nombres_', '', $key);
                    
                    // Verificar que sea numérico y no esté duplicado
                    if (is_numeric($index) && !in_array($index, $indicesEncontrados)) {
                        $indicesEncontrados[] = $index;
                        
                        // Solo procesar si tiene nombre y parentesco
                        $nombres = $request->input("nombres_{$index}");
                        $parentesco = $request->input("parentesco_{$index}");
                        
                        Log::channel('single')->info("--- Procesando familiar índice {$index} ---");
                        Log::channel('single')->info("Nombres: " . ($nombres ?? 'VACÍO'));
                        Log::channel('single')->info("Parentesco: " . ($parentesco ?? 'VACÍO'));
                        
                        if (!empty($nombres) && !empty($parentesco)) {
                            
                            $dataToInsert = [
                                'idUsuario' => $usuario->idUsuario,
                                'parentesco' => $this->mapParentesco($parentesco),
                                'apellidosNombres' => $nombres,
                                'nroDocumento' => $request->input("documento_{$index}"),
                                'ocupacion' => $request->input("ocupacion_{$index}"),
                                'sexo' => $request->input("sexo_{$index}"),
                                'fechaNacimiento' => $request->input("fecha_nacimiento_{$index}"),
                                'domicilioActual' => $request->input("domicilio_{$index}"),
                            ];
                            
                            Log::channel('single')->info('Datos a insertar:', $dataToInsert);
                            
                            try {
                                UsuarioFamilia::create($dataToInsert);
                                $familiaresGuardados++;
                                Log::channel('single')->info('✅ FAMILIAR GUARDADO');
                            } catch (\Exception $e) {
                                Log::channel('single')->error('❌ ERROR guardando familiar: ' . $e->getMessage());
                            }
                        } else {
                            Log::channel('single')->info('⏭️ Familiar incompleto (faltan nombres o parentesco)');
                        }
                    }
                }
            }
            
            Log::channel('single')->info("📊 TOTAL: {$familiaresGuardados} familiares guardados");
            Log::channel('single')->info('========== FIN DEBUG FAMILIARES ==========');

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

            // ========== 7. GUARDAR CONTACTOS DE EMERGENCIA ==========
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
                    [
                        'idUsuario' => $usuario->idUsuario,
                        'tipoDocumento' => 'HUELLA'
                    ],
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

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Formulario guardado exitosamente',
                    'data' => [
                        'idUsuario' => $usuario->idUsuario,
                        'nombre_completo' => $usuario->apellidoPaterno . ' ' . $usuario->apellidoMaterno . ', ' . $usuario->Nombre,
                        'documento' => $usuario->documento,
                        'usuario' => $usuario->usuario,
                    ]
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
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar el formulario: ' . $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
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
            ], function($value) {
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
            ], function($value) {
                return !is_null($value) && $value !== '';
            });
            
            if (!empty($saludData)) {
                UsuarioSalud::updateOrCreate(
                    ['idUsuario' => $usuario->idUsuario],
                    $saludData
                );
            }
            
            DB::commit();
            
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
            'emergenciaContactos'
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