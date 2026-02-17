<?php

namespace App\Http\Controllers\usuario;

use App\Http\Controllers\Controller;
use App\Mail\UsuarioCreado;
use App\Models\Articulo;
use App\Models\Asignacion;
use App\Models\CuentasBancarias;
use App\Models\DetalleAsignacion;
use App\Models\DocumentoUsuario;
use App\Models\Rol;
use App\Models\Sexo;
use App\Models\Sucursal;
use App\Models\Tipoarea;
use App\Models\TipoContrato;
use App\Models\Tipodocumento;
use App\Models\Tipousuario;
use App\Models\Usuario;
use App\Models\UsuarioEmergenciaContacto;
use App\Models\UsuarioFamilia;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Exports\UsuarioFichaExport;
use Maatwebsite\Excel\Facades\Excel;

class UsuarioController extends Controller
{

    public function index()
    {

        $usuario = Usuario::all();



        return view('usuario.index', compact('usuario'));
    }




    public function perfil()
    {
        // Obtener el usuario autenticado
        $usuario = Auth::user();


        return view('usuario.perfil')->with('usuario', $usuario);
    }

    public function create()
    {
        $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true);
        $tiposDocumento = Tipodocumento::all();
        $tiposDocumento = TipoDocumento::all(); // Si es necesario obtener tipos de documento
        $sucursales = Sucursal::all(); // Obtener todas las sucursales
        $tiposUsuario = Tipousuario::all(); // Obtener todos los tipos de usuario
        $sexos = Sexo::all(); // Obtener todos los sexos
        $roles = Rol::all(); // Obtener todos los roles
        $tiposArea = Tipoarea::all(); // Obtener todos los tipos de área

        // Create 
        return view('usuario.create', compact('tiposDocumento', 'sucursales', 'tiposUsuario', 'sexos', 'roles', 'tiposArea', 'departamentos'));
    }

    //     public function store(Request $request)
    // {
    //     try {
    //         Log::info('Inicio del proceso de creación de usuario.');

    //         // Validación
    //         $request->validate([
    //             'Nombre' => 'required|string|max:255',
    //             'apellidoPaterno' => 'required|string|max:255',
    //             'apellidoMaterno' => 'required|string|max:255',
    //             'idTipoDocumento' => 'required|integer',
    //             'documento' => 'required|string|max:255|unique:usuarios,documento',
    //             'telefono' => 'required|string|max:255|unique:usuarios,telefono',
    //             'correo' => 'required|email|max:255|unique:usuarios,correo',
    //             'profile-image' => 'nullable|image|max:1024',
    //         ]);
    //         Log::info('Formulario validado con éxito.');

    //         // Procesamiento de la imagen
    //         $imageData = $request->hasFile('profile-image') ? file_get_contents($request->file('profile-image')) : null;
    //         Log::info('Imagen procesada. ¿Imagen subida? ', ['has_image' => $request->hasFile('profile-image')]);

    //         // Generación de usuario y clave
    //         $usuario = strtolower(substr($request->Nombre, 0, 6)) . strtolower(substr($request->apellidoPaterno, 0, 6)) . rand(1, 9);
    //         $usuario = str_replace(' ', '', $usuario);
    //         $clave = Str::random(8);
    //         $claveEncriptada = bcrypt($clave);

    //         Log::info('Datos generados para el nuevo usuario:', [
    //             'usuario' => $usuario,
    //             'clave' => $clave
    //         ]);

    //         // Creación del usuario
    //         $usuarioNuevo = new Usuario();
    //         $usuarioNuevo->Nombre = $request->Nombre;
    //         $usuarioNuevo->apellidoPaterno = $request->apellidoPaterno;
    //         $usuarioNuevo->apellidoMaterno = $request->apellidoMaterno;
    //         $usuarioNuevo->idTipoDocumento = $request->idTipoDocumento;
    //         $usuarioNuevo->documento = $request->documento;
    //         $usuarioNuevo->telefono = $request->telefono;
    //         $usuarioNuevo->correo = $request->correo;
    //         $usuarioNuevo->avatar = $imageData;
    //         $usuarioNuevo->usuario = $usuario;
    //         $usuarioNuevo->clave = $claveEncriptada;
    //         $usuarioNuevo->estado = 1;
    //         $usuarioNuevo->save();
    //         Log::info('Usuario creado exitosamente:', ['usuario' => $usuarioNuevo]);

    //         // Enviar correo
    //         Mail::to($request->correo)->send(new UsuarioCreado($usuario, $clave));
    //         Log::info('Correo enviado al usuario.', ['correo' => $request->correo]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Usuario creado y datos enviados al correo.',
    //             'usuarioId' => $usuarioNuevo->idUsuario  // Asegúrate de devolver el ID del nuevo usuario

    //         ]);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         Log::error('Error en la validación de los datos:', ['errors' => $e->errors()]);
    //         return response()->json(['success' => false, 'errors' => $e->errors()], 422);
    //     } catch (\Exception $e) {
    //         Log::error('Error inesperado al crear el usuario:', ['message' => $e->getMessage()]);
    //         return response()->json(['success' => false, 'message' => 'Error al crear el usuario.'], 500);
    //     }
    // }


    public function getUsuariostecnico()
    {
        Log::debug('Iniciando la obtención de usuarios con relaciones');

        // Filtramos los usuarios por tipoUsuario, tipoArea y estado
        $usuarios = Usuario::with(['tipoDocumento', 'tipoUsuario', 'rol', 'tipoArea'])
            ->where('estado', 1) // Estado activo
            ->whereIn('idTipoUsuario', [1, 4]) // Tipo de usuario igual a 1
            ->where('idTipoArea', 4) // Tipo de área igual a 4
            ->get()
            ->map(function ($usuario) {
                return [
                    'idUsuario' => $usuario->idUsuario,
                    'Nombre' => $usuario->Nombre,
                    'apellidoPaterno' => $usuario->apellidoPaterno,
                    'telefono' => $usuario->telefono ?? 'N/A',
                    'correo' => $usuario->correo ?? 'N/A',
                    'documento' => $usuario->documento ?? 'N/A',
                    'estado' => $usuario->estado,
                    'tipoDocumento' => $usuario->tipoDocumento ? $usuario->tipoDocumento->nombre : 'N/A',
                    'tipoUsuario' => $usuario->tipoUsuario ? $usuario->tipoUsuario->nombre : 'N/A',
                    'rol' => $usuario->rol ? $usuario->rol->nombre : 'N/A',
                    'tipoArea' => $usuario->tipoArea ? $usuario->tipoArea->nombre : 'N/A',
                    'avatar' => !empty($usuario->avatar) ? 'data:image/png;base64,' . base64_encode($usuario->avatar) : null,
                    'tieneFirma' => !empty($usuario->firma), // 🔥 Solo enviamos `true` o `false`
                ];
            });

        Log::debug('Usuarios obtenidos con relaciones:', ['usuarios' => $usuarios]);

        return response()->json($usuarios);
    }




    public function getUsuariostecnicohelp()
    {
        Log::debug('Iniciando la obtención de usuarios con relaciones');

        // Filtramos los usuarios por tipoUsuario, tipoArea y estado
        $usuarios = Usuario::with(['tipoDocumento', 'tipoUsuario', 'rol', 'tipoArea'])
            ->where('estado', 1) // Estado activo
            ->where('idTipoUsuario', 1) // Tipo de usuario igual a 1
            ->where('idTipoArea', 6) // Tipo de área igual a 4
            ->get()
            ->map(function ($usuario) {
                return [
                    'idUsuario' => $usuario->idUsuario,
                    'Nombre' => $usuario->Nombre,
                    'apellidoPaterno' => $usuario->apellidoPaterno,
                    'telefono' => $usuario->telefono ?? 'N/A',
                    'correo' => $usuario->correo ?? 'N/A',
                    'documento' => $usuario->documento ?? 'N/A',
                    'estado' => $usuario->estado,
                    'tipoDocumento' => $usuario->tipoDocumento ? $usuario->tipoDocumento->nombre : 'N/A',
                    'tipoUsuario' => $usuario->tipoUsuario ? $usuario->tipoUsuario->nombre : 'N/A',
                    'rol' => $usuario->rol ? $usuario->rol->nombre : 'N/A',
                    'tipoArea' => $usuario->tipoArea ? $usuario->tipoArea->nombre : 'N/A',
                    'avatar' => !empty($usuario->avatar) ? 'data:image/png;base64,' . base64_encode($usuario->avatar) : null,
                    'tieneFirma' => !empty($usuario->firma), // 🔥 Solo enviamos `true` o `false`
                ];
            });

        Log::debug('Usuarios obtenidos con relaciones:', ['usuarios' => $usuarios]);

        return response()->json($usuarios);
    }




    public function store(Request $request)
    {
        try {
            Log::info('Inicio del proceso de creación de usuario.');

            // Validación personalizada para el documento según el tipo
            $documentoReglas = [
                'DNI' => 'required|digits:8', // 8 dígitos para DNI
                'RUC' => 'required|digits:11', // 11 dígitos para RUC
                'PASAPORTE' => 'required|digits:12', // 12 dígitos para PASAPORTE
                'CPP' => 'required|digits:12', // 12 dígitos para CPP
                'CARNET DE EXTRANJERIA' => 'required|digits:20', // 20 dígitos para CARNET DE EXTRANJERIA
            ];

            // Recuperamos el tipo de documento
            $tipoDocumentoId = $request->idTipoDocumento;

            // Aquí asumo que tienes un modelo `TipoDocumento` para obtener el nombre del tipo
            $tipoDocumento = \App\Models\Tipodocumento::findOrFail($tipoDocumentoId);
            $tipoDocumentoNombre = $tipoDocumento->nombre;

            // Validamos según el tipo de documento seleccionado
            $request->validate([
                'Nombre' => 'required|string|max:255',
                'apellidoPaterno' => 'required|string|max:255',
                'apellidoMaterno' => 'required|string|max:255',
                'idTipoDocumento' => 'required|integer',
                'documento' => $documentoReglas[$tipoDocumentoNombre] ?? 'required|string|max:255|unique:usuarios,documento', // Valida según el tipo
                'telefono' => 'required|string|digits:9|unique:usuarios,telefono',
                'correo' => 'required|email|max:255|unique:usuarios,correo',
                'correo_personal' => 'required|email|max:255|unique:usuarios,correo_personal',

                'estadocivil' => 'required|integer|in:1,2,3,4', // Validación para el estado civil
                'profile-image' => 'nullable|image|max:1024',
            ]);

            Log::info('Formulario validado con éxito.');

            // Procesamiento de la imagen
            $imageData = $request->hasFile('profile-image') ? file_get_contents($request->file('profile-image')) : null;
            Log::info('Imagen procesada. ¿Imagen subida? ', ['has_image' => $request->hasFile('profile-image')]);

            // Generación de usuario y clave
            $usuario = strtolower(substr($request->Nombre, 0, 6)) . strtolower(substr($request->apellidoPaterno, 0, 6)) . rand(1, 9);
            $usuario = str_replace(' ', '', $usuario);
            $clave = Str::random(8);
            $claveEncriptada = bcrypt($clave);

            Log::info('Datos generados para el nuevo usuario:', [
                'usuario' => $usuario,
                'clave' => $clave
            ]);

            // Creación del usuario
            $usuarioNuevo = new Usuario();
            $usuarioNuevo->Nombre = $request->Nombre;
            $usuarioNuevo->apellidoPaterno = $request->apellidoPaterno;
            $usuarioNuevo->apellidoMaterno = $request->apellidoMaterno;
            $usuarioNuevo->idTipoDocumento = $request->idTipoDocumento;
            $usuarioNuevo->documento = $request->documento;
            $usuarioNuevo->telefono = $request->telefono;
            $usuarioNuevo->correo = $request->correo;
            $usuarioNuevo->correo_personal = $request->correo_personal;
            $usuarioNuevo->avatar = $imageData;
            $usuarioNuevo->usuario = $usuario;
            $usuarioNuevo->estadocivil = $request->estadocivil; // Aquí asignamos el estado civil
            $usuarioNuevo->clave = $claveEncriptada;
            $usuarioNuevo->estado = 1;
            $usuarioNuevo->save();
            Log::info('Usuario creado exitosamente:', ['usuario' => $usuarioNuevo]);

            // Enviar correo
            // Enviar correo con todos los datos
            Mail::to($request->correo)->send(new UsuarioCreado(
                $request->Nombre,
                $request->apellidoPaterno,
                $request->apellidoMaterno,
                $usuario, // El nombre de usuario generado automáticamente
                $clave // La clave generada aleatoriamente
            ));
            Log::info('Correo enviado al usuario.', ['correo' => $request->correo]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado y datos enviados al correo.',
                'usuarioId' => $usuarioNuevo->idUsuario  // Asegúrate de devolver el ID del nuevo usuario

            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error en la validación de los datos:', ['errors' => $e->errors()]);
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error inesperado al crear el usuario:', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error al crear el usuario.'], 500);
        }
    }






    public function edit($id)
    {
        $usuario = Usuario::with(['salud', 'fichaGeneral', 'estudios', 'laboral'])->findOrFail($id);

        // Datos para las pestañas
        $tiposDocumento = Tipodocumento::all();
        $sexos = Sexo::all();
        $sucursales = Sucursal::all();
        $tiposUsuario = Tipousuario::all();
        $roles = Rol::all();
        $tiposArea = Tipoarea::all();
        $tiposContrato = TipoContrato::activos()->get();

        // Datos bancarios
        $bancos = [
            '1' => 'Banco de Crédito del Perú',
            '2' => 'BBVA Perú',
            '3' => 'Scotiabank Perú',
            '4' => 'Interbank',
            '5' => 'Banco de la Nación',
            '6' => 'Banco de Comercio',
            '7' => 'BanBif',
            '8' => 'Banco Pichincha',
            '9' => 'Citibank Perú',
            '10' => 'MiBanco',
            '11' => 'Banco GNB Perú',
            '12' => 'Banco Falabella',
            '13' => 'Banco Ripley',
            '14' => 'Banco Santander Perú',
            '15' => 'Alfin Banco',
            '16' => 'Bank of China',
            '17' => 'Bci Perú',
            '18' => 'ICBC Perú Bank',
        ];

        $tiposCuenta = [
            '1' => 'Cuenta de Ahorros',
            '2' => 'Cuenta Corriente',
            '3' => 'Cuenta a Plazo Fijo',
        ];

        $monedas = [
            'PEN' => 'Soles',
            'USD' => 'Dólares',
            'EUR' => 'Euros',
        ];

        // Datos de ficha general
        $fichaGeneral = $usuario->fichaGeneral;
        if (!$fichaGeneral) {
            $fichaGeneral = new \App\Models\UsuarioFichaGeneral();
            $fichaGeneral->idUsuario = $usuario->idUsuario;
        }

        // Datos laborales
        $laboral = $usuario->laboral;
        if (!$laboral) {
            $laboral = new \App\Models\UsuarioLaboral();
            $laboral->idUsuario = $usuario->idUsuario;
        }

        // Datos de salud
        $salud = $usuario->salud;
        if (!$salud) {
            $salud = new \App\Models\UsuarioSalud();
            $salud->idUsuario = $usuario->idUsuario;
        }

        // Procesar fechas de COVID
        $covidDosis1 = $salud->covidDosis1 ? date('Y-m-d', strtotime($salud->covidDosis1)) : '';
        $covidDosis2 = $salud->covidDosis2 ? date('Y-m-d', strtotime($salud->covidDosis2)) : '';
        $covidDosis3 = $salud->covidDosis3 ? date('Y-m-d', strtotime($salud->covidDosis3)) : '';

        return view('usuario.edit', compact(
            'usuario',
            'tiposDocumento',
            'sexos',
            'sucursales',
            'tiposUsuario',
            'roles',
            'tiposArea',
            'tiposContrato',
            'bancos',
            'tiposCuenta',
            'monedas',
            'fichaGeneral',
            'laboral',
            'salud',
            'covidDosis1',
            'covidDosis2',
            'covidDosis3'
        ));
    }
    public function loadTab($id, $tab)
    {
        // Cargar usuario con todas las relaciones necesarias
        $usuario = Usuario::with(['salud', 'fichaGeneral', 'estudios'])->findOrFail($id);

        switch ($tab) {
            case 'perfil':
                $tiposDocumento = Tipodocumento::all();
                $sexos = Sexo::all();

                // ============================================
                // CARGAR ARCHIVOS JSON
                // ============================================
                try {
                    // Departamentos es un array simple
                    $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true) ?? [];

                    // Provincias viene como objeto con keys por departamento
                    $provinciasRaw = json_decode(file_get_contents(public_path('ubigeos/provincias.json')), true) ?? [];

                    // Distritos viene como objeto con keys por provincia
                    $distritosRaw = json_decode(file_get_contents(public_path('ubigeos/distritos.json')), true) ?? [];

                    // Reestructurar provincias a un array plano
                    $provincias = [];
                    foreach ($provinciasRaw as $deptoId => $provs) {
                        foreach ($provs as $prov) {
                            $prov['id_padre_ubigeo'] = $deptoId;
                            $provincias[] = $prov;
                        }
                    }

                    // Reestructurar distritos a un array plano
                    $distritos = [];
                    foreach ($distritosRaw as $provId => $dists) {
                        foreach ($dists as $dist) {
                            $dist['id_padre_ubigeo'] = $provId;
                            $distritos[] = $dist;
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('Error cargando archivos ubigeo:', ['error' => $e->getMessage()]);
                    $departamentos = [];
                    $provincias = [];
                    $distritos = [];
                }

                // ============================================
                // PROCESAR DIRECCIÓN ACTUAL
                // ============================================

                // DEPARTAMENTO SELECCIONADO
                $departamentoSeleccionado = null;
                $nombreDepartamento = '';
                if ($usuario->departamento) {
                    $departamentoSeleccionado = collect($departamentos)
                        ->firstWhere('id_ubigeo', $usuario->departamento);
                    $nombreDepartamento = $departamentoSeleccionado['nombre_ubigeo'] ?? '';
                }

                // PROVINCIAS DEL DEPARTAMENTO SELECCIONADO
                $provinciasDelDepartamento = [];
                if ($usuario->departamento) {
                    $provinciasDelDepartamento = array_filter($provincias, function ($prov) use ($usuario) {
                        return isset($prov['id_padre_ubigeo']) &&
                            $prov['id_padre_ubigeo'] == $usuario->departamento;
                    });
                    $provinciasDelDepartamento = array_values($provinciasDelDepartamento);
                }

                // PROVINCIA SELECCIONADA
                $provinciaSeleccionada = null;
                $nombreProvincia = '';
                if ($usuario->provincia) {
                    $provinciaSeleccionada = collect($provincias)
                        ->firstWhere('id_ubigeo', $usuario->provincia);
                    $nombreProvincia = $provinciaSeleccionada['nombre_ubigeo'] ?? '';
                }

                // DISTRITOS DE LA PROVINCIA SELECCIONADA
                $distritosDeLaProvincia = [];
                if ($usuario->provincia) {
                    $distritosDeLaProvincia = array_filter($distritos, function ($dist) use ($usuario) {
                        return isset($dist['id_padre_ubigeo']) &&
                            $dist['id_padre_ubigeo'] == $usuario->provincia;
                    });
                    $distritosDeLaProvincia = array_values($distritosDeLaProvincia);
                }

                // ============================================
                // PROCESAR LUGAR DE NACIMIENTO
                // ============================================
                $fichaGeneral = $usuario->fichaGeneral;

                // Arrays para lugar de nacimiento
                $provinciasNacimiento = [];
                $distritosNacimiento = [];

                // Nombres para mostrar
                $nombreNacimientoDepartamento = '';
                $nombreNacimientoProvincia = '';
                $nombreNacimientoDistrito = '';

                if ($fichaGeneral) {
                    // Departamento de nacimiento
                    if ($fichaGeneral->nacimientoDepartamento) {
                        $deptoNac = collect($departamentos)
                            ->firstWhere('id_ubigeo', $fichaGeneral->nacimientoDepartamento);
                        $nombreNacimientoDepartamento = $deptoNac['nombre_ubigeo'] ?? '';

                        // Provincias del departamento de nacimiento
                        $provinciasNacimiento = array_filter($provincias, function ($prov) use ($fichaGeneral) {
                            return isset($prov['id_padre_ubigeo']) &&
                                $prov['id_padre_ubigeo'] == $fichaGeneral->nacimientoDepartamento;
                        });
                        $provinciasNacimiento = array_values($provinciasNacimiento);
                    }

                    // Provincia de nacimiento
                    if ($fichaGeneral->nacimientoProvincia) {
                        $provNac = collect($provincias)
                            ->firstWhere('id_ubigeo', $fichaGeneral->nacimientoProvincia);
                        $nombreNacimientoProvincia = $provNac['nombre_ubigeo'] ?? '';

                        // Distritos de la provincia de nacimiento
                        $distritosNacimiento = array_filter($distritos, function ($dist) use ($fichaGeneral) {
                            return isset($dist['id_padre_ubigeo']) &&
                                $dist['id_padre_ubigeo'] == $fichaGeneral->nacimientoProvincia;
                        });
                        $distritosNacimiento = array_values($distritosNacimiento);
                    }

                    // Distrito de nacimiento
                    if ($fichaGeneral->nacimientoDistrito) {
                        $distNac = collect($distritos)
                            ->firstWhere('id_ubigeo', $fichaGeneral->nacimientoDistrito);
                        $nombreNacimientoDistrito = $distNac['nombre_ubigeo'] ?? '';
                    }
                }

                // DEBUG
                \Log::info('=== DATOS DE NACIMIENTO ===');
                \Log::info('Departamento nacimiento:', [
                    'id' => $fichaGeneral->nacimientoDepartamento ?? 'N/A',
                    'nombre' => $nombreNacimientoDepartamento
                ]);
                \Log::info('Provincias disponibles:', ['count' => count($provinciasNacimiento)]);
                \Log::info('Provincia nacimiento:', [
                    'id' => $fichaGeneral->nacimientoProvincia ?? 'N/A',
                    'nombre' => $nombreNacimientoProvincia
                ]);
                \Log::info('Distritos disponibles:', ['count' => count($distritosNacimiento)]);

                // ============================================
                // PROCESAR FECHA DE NACIMIENTO
                // ============================================
                $fechaNacimiento = $usuario->fechaNacimiento;
                $dia = $fechaNacimiento ? date('d', strtotime($fechaNacimiento)) : '';
                $mes = $fechaNacimiento ? date('m', strtotime($fechaNacimiento)) : '';
                $anio = $fechaNacimiento ? date('Y', strtotime($fechaNacimiento)) : '';
                $edad = $fechaNacimiento ? \Carbon\Carbon::parse($fechaNacimiento)->age : '';

                // ============================================
                // PROCESAR ESTUDIOS
                // ============================================
                $estudios = $usuario->estudios ?? collect([]);
                $estudioSecundaria = $estudios->where('nivel', 'SECUNDARIA')->first();
                $estudioTecnico = $estudios->where('nivel', 'TECNICO')->first();
                $estudioUniversitario = $estudios->where('nivel', 'UNIVERSITARIO')->first();
                $estudioPostgrado = $estudios->whereIn('nivel', ['POSTGRADO', 'MAESTRIA'])->first();

                return view('usuario.tabs.perfil.index', compact(
                    'usuario',
                    'tiposDocumento',
                    'sexos',
                    'departamentos',
                    'provincias',
                    'distritos',
                    'provinciasDelDepartamento',
                    'distritosDeLaProvincia',
                    'fichaGeneral',
                    'provinciasNacimiento',
                    'distritosNacimiento',
                    'fechaNacimiento',
                    'dia',
                    'mes',
                    'anio',
                    'edad',
                    'estudioSecundaria',
                    'estudioTecnico',
                    'estudioUniversitario',
                    'estudioPostgrado',
                    'nombreDepartamento',
                    'nombreProvincia',
                    'nombreNacimientoDepartamento',
                    'nombreNacimientoProvincia',
                    'nombreNacimientoDistrito'
                ));
            case 'info-salud':
                // Cargar datos de salud, familiares y contactos de emergencia
                $salud = $usuario->salud;
                $familiares = $usuario->familiares ?? collect([]);
                $contactosEmergencia = $usuario->contactosEmergencia ?? collect([]);

                // Si no existe registro de salud, crear uno vacío
                if (!$salud) {
                    $salud = new \App\Models\UsuarioSalud();
                    $salud->idUsuario = $usuario->idUsuario;
                }

                // Procesar fechas de COVID para el formato flatpickr
                $covidDosis1 = $salud->covidDosis1 ? date('Y-m-d', strtotime($salud->covidDosis1)) : '';
                $covidDosis2 = $salud->covidDosis2 ? date('Y-m-d', strtotime($salud->covidDosis2)) : '';
                $covidDosis3 = $salud->covidDosis3 ? date('Y-m-d', strtotime($salud->covidDosis3)) : '';

                // Debug
                \Log::info('=== DATOS DE SALUD ===');
                \Log::info('Usuario ID:', ['id' => $usuario->idUsuario]);
                \Log::info('Salud:', ['data' => $salud]);
                \Log::info('Familiares:', ['count' => $familiares->count()]);
                \Log::info('Contactos Emergencia:', ['count' => $contactosEmergencia->count()]);

                return view('usuario.tabs.info-salud.index', compact(
                    'usuario',
                    'salud',
                    'familiares',
                    'contactosEmergencia',
                    'covidDosis1',
                    'covidDosis2',
                    'covidDosis3'
                ));

            case 'payment-details':
                // Obtener la ficha general del usuario
                $fichaGeneral = $usuario->fichaGeneral;

                // Si no existe, crear una instancia vacía
                if (!$fichaGeneral) {
                    $fichaGeneral = new \App\Models\UsuarioFichaGeneral();
                    $fichaGeneral->idUsuario = $usuario->idUsuario;
                }

                // Mapeo de bancos (puedes tener una tabla de bancos o hacerlo así)
                $bancos = [
                    '1' => 'Banco de Crédito del Perú',
                    '2' => 'BBVA Perú',
                    '3' => 'Scotiabank Perú',
                    '4' => 'Interbank',
                    '5' => 'Banco de la Nación',
                    '6' => 'Banco de Comercio',
                    '7' => 'BanBif',
                    '8' => 'Banco Pichincha',
                    '9' => 'Citibank Perú',
                    '10' => 'MiBanco',
                    '11' => 'Banco GNB Perú',
                    '12' => 'Banco Falabella',
                    '13' => 'Banco Ripley',
                    '14' => 'Banco Santander Perú',
                    '15' => 'Alfin Banco',
                    '16' => 'Bank of China',
                    '17' => 'Bci Perú',
                    '18' => 'ICBC Perú Bank',
                ];

                $tiposCuenta = [
                    '1' => 'Cuenta de Ahorros',
                    '2' => 'Cuenta Corriente',
                    '3' => 'Cuenta a Plazo Fijo',
                ];

                $monedas = [
                    'PEN' => 'Soles',
                    'USD' => 'Dólares',
                    'EUR' => 'Euros',
                ];

                \Log::info('=== DATOS BANCARIOS ===');
                \Log::info('Usuario ID:', ['id' => $usuario->idUsuario]);
                \Log::info('Ficha General:', ['data' => $fichaGeneral]);

                return view('usuario.tabs.detalles-pago.index', compact(
                    'usuario',
                    'fichaGeneral',
                    'bancos',
                    'tiposCuenta',
                    'monedas'
                ));

            case 'informacion':
                $sucursales = Sucursal::all();
                $tiposUsuario = Tipousuario::all();
                $sexos = Sexo::all();
                $roles = Rol::all();
                $tiposArea = Tipoarea::all();
                $tiposContrato = TipoContrato::activos()->get();

                // Cargar datos laborales
                $laboral = $usuario->laboral;

                // Si no existe, crear uno vacío
                if (!$laboral) {
                    $laboral = new \App\Models\UsuarioLaboral();
                    $laboral->idUsuario = $usuario->idUsuario;
                }

                return view('usuario.tabs.informacion.index', compact(
                    'usuario',
                    'sucursales',
                    'tiposUsuario',
                    'sexos',
                    'roles',
                    'tiposArea',
                    'tiposContrato',
                    'laboral'
                ));

            case 'asignado':
                return view('usuario.tabs.asignado.index', compact('usuario'));

            case 'preferences':
                $roles = Rol::all();
                $tiposArea = Tipoarea::all();
                $tiposUsuario = Tipousuario::all();
                $sexos = Sexo::all();
                return view('usuario.tabs.configuracion.index', compact(
                    'usuario',
                    'roles',
                    'tiposArea',
                    'tiposUsuario',
                    'sexos'
                ));

            case 'danger-zone':
                return view('usuario.tabs.detalles.index', compact('usuario'));

            default:
                abort(404);
        }
    }




    public function guardarCuenta(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'tipoCuenta' => 'required|integer|in:1,2', // 1 = Numero interbancario, 2 = Numero de cuenta
            'banco' => 'required|int',
            'numeroCuenta' => 'required|int',
            'usuarioId' => 'required|integer|exists:usuarios,idUsuario', // Asegurarse de que el usuario existe
        ]);

        // Crear la cuenta bancaria
        $cuentaBancaria = new CuentasBancarias();
        $cuentaBancaria->tipodecuenta = $request->tipoCuenta;
        $cuentaBancaria->banco = $request->banco;
        $cuentaBancaria->numerocuenta = $request->numeroCuenta;
        $cuentaBancaria->idUsuario = $request->usuarioId;

        // Guardar en la base de datos
        $cuentaBancaria->save();

        // Devolver una respuesta JSON indicando que todo salió bien
        return response()->json(['success' => true, 'message' => 'Cuenta bancaria guardada con éxito']);
    }




    public function update(Request $request, $id)
    {
        // Validación de los datos
        $validated = $request->validate([
            'Nombre' => 'required|string|max:255',
            'apellidoPaterno' => 'required|string|max:255',
            'apellidoMaterno' => 'required|string|max:255',
            'idTipoDocumento' => 'required|integer',
            'documento' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'correo' => 'required|email|max:255|unique:usuarios,correo,' . $id . ',idUsuario', // Excluye al usuario actual
            'correo_personal' => 'nullable|email|max:255|unique:usuarios,correo_personal,' . $id . ',idUsuario', // Validación para correo personal
            'estadocivil' => 'required|integer|in:1,2,3,4',
            'profile-image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Obtener el usuario por su id
        $usuario = Usuario::findOrFail($id);

        // Actualizar los datos del usuario
        $usuario->Nombre = $request->input('Nombre');
        $usuario->apellidoPaterno = $request->input('apellidoPaterno');
        $usuario->apellidoMaterno = $request->input('apellidoMaterno');
        $usuario->idTipoDocumento = $request->input('idTipoDocumento');
        $usuario->documento = $request->input('documento');
        $usuario->telefono = $request->input('telefono');
        $usuario->correo = $request->input('correo');
        $usuario->correo_personal = $request->input('correo_personal'); // Nuevo campo
        $usuario->estadocivil = $request->input('estadocivil');

        // Verificar si se subió una nueva imagen
        if ($request->hasFile('profile-image')) {
            $image = $request->file('profile-image');
            $imageData = file_get_contents($image->getRealPath());
            $usuario->avatar = $imageData;
        }

        // Guardar los cambios
        $usuario->save();

        return response()->json(['success' => 'Datos actualizados correctamente']);
    }

    public function config(Request $request, $id)
    {
        // Validación de los campos
        $request->validate([
            'sueldoPorHora' => 'required|numeric|min:0', // Asegura que el sueldoPorHora sea >= 0        'idSucursal' => 'integer|exists:sucursal,idSucursal',
            'idTipoUsuario' => 'required|integer|exists:tipousuario,idTipoUsuario',
            'idSexo' => 'required|integer|exists:sexo,idSexo',
            'idRol' => 'required|integer|exists:rol,idRol',
            'idTipoArea' => 'required|integer|exists:tipoarea,idTipoArea',
        ]);

        Log::info('Validación completada', ['request_data' => $request->all()]);

        // Obtener el usuario
        $usuario = Usuario::findOrFail($id);
        Log::info('Usuario encontrado', ['usuario' => $usuario]);

        // Actualizar los campos básicos
        $usuario->sueldoPorHora = $request->sueldoPorHora;
        $usuario->idSucursal = $request->idSucursal;
        $usuario->idTipoUsuario = $request->idTipoUsuario;
        $usuario->idSexo = $request->idSexo;
        $usuario->idRol = $request->idRol;
        $usuario->idTipoArea = $request->idTipoArea;

        Log::info('Campos del usuario actualizados', ['usuario_data' => $usuario->toArray()]);



        // Guardar los cambios
        $usuario->save();
        Log::info('Usuario guardado exitosamente', ['usuario_id' => $usuario->idUsuario]);

        // Respuesta exitosa en formato JSON con Base64 para firma y avatar
        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado correctamente',
            'usuario' => $usuario->only(['idUsuario', 'sueldoPorHora', 'idSucursal', 'idTipoUsuario', 'idSexo', 'idRol', 'idTipoArea']) // Excluyendo avatar y firma
        ], 200);
    }









    // use Illuminate\Support\Facades\Log;

    public function direccion(Request $request, $id)
    {
        // Validar los datos del formulario
        Log::info("Validación de los datos de actualización para el usuario con ID: {$id}", $request->all());

        try {
            $request->validate([
                'nacionalidad' => 'required|string|max:255',
                'departamento' => 'required|integer',
                'provincia' => 'integer',
                'distrito' => 'integer',
                'direccion' => 'required|string|max:255',
            ]);
            Log::info("Datos validados correctamente para el usuario con ID: {$id}");

            // Obtener el usuario con el ID proporcionado
            $usuario = Usuario::findOrFail($id);
            Log::info("Usuario encontrado para el ID: {$id}", ['usuario' => $usuario]);

            // Actualizar los datos del usuario
            $usuario->nacionalidad = $request->nacionalidad;
            $usuario->departamento = $request->departamento;
            $usuario->provincia = $request->provincia;
            $usuario->distrito = $request->distrito;
            $usuario->direccion = $request->direccion;

            // Guardar los cambios en la base de datos
            $usuario->save();
            Log::info("Usuario con ID {$id} actualizado correctamente.", ['usuario' => $usuario]);

            // Respuesta JSON
            return response()->json(['success' => true, 'message' => 'Usuario actualizado correctamente']);
        } catch (\Exception $e) {
            Log::error("Error al intentar actualizar el usuario con ID {$id}: " . $e->getMessage(), [
                'error' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json(['success' => false, 'message' => 'Ocurrió un error al actualizar el usuario'], 500);
        }
    }






    public function guardarFirma(Request $request, $idUsuario)
    {
        // Validar la firma
        $request->validate([
            'firma' => 'required|string',
        ]);

        // Obtener la firma del request
        $firmaBase64 = $request->input('firma');

        // Log para verificar que se recibió la firma
        Log::info("Firma recibida del usuario {$idUsuario}: " . substr($firmaBase64, 0, 50) . '...'); // Solo muestra los primeros 50 caracteres para evitar logs largos

        // Eliminar el encabezado de la cadena base64 (por ejemplo: data:image/png;base64,)
        $firmaBase64 = preg_replace('/^data:image\/\w+;base64,/', '', $firmaBase64);

        // Decodificar la cadena base64 a binario
        $firmaBinaria = base64_decode($firmaBase64);

        // Log para verificar que la firma fue decodificada correctamente
        if ($firmaBinaria === false) {
            Log::error("Error al decodificar la firma base64.");
        } else {
            Log::info("Firma decodificada correctamente.");
        }

        // Obtener el usuario y actualizar su firma
        $usuario = Usuario::find($idUsuario);

        // Log para verificar si se encontró al usuario
        if ($usuario) {
            Log::info("Usuario encontrado con ID {$idUsuario}. Actualizando firma.");
            $usuario->firma = $firmaBinaria;
            $usuario->save();

            Log::info("Firma guardada para el usuario {$idUsuario}.");

            return response()->json(['message' => 'Firma guardada correctamente.'], 200);
        } else {
            Log::warning("Usuario con ID {$idUsuario} no encontrado.");
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }
    }

    // use Illuminate\Support\Facades\Log;

    public function getUsuarios(Request $request)
    {
        Log::debug('Iniciando obtención paginada de usuarios');

        $query = Usuario::with(['tipoDocumento', 'tipoUsuario', 'rol', 'tipoArea']);

        $total = $query->count();

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('Nombre', 'like', "%$search%")
                    ->orWhere('apellidoPaterno', 'like', "%$search%")
                    ->orWhere('documento', 'like', "%$search%")
                    ->orWhere('telefono', 'like', "%$search%")
                    ->orWhere('correo', 'like', "%$search%")
                    ->orWhere('usuario', 'like', "%$search%") // AGREGADO: búsqueda por usuario
                    ->orWhereHas('tipoUsuario', function ($q2) use ($search) {
                        $q2->where('nombre', 'like', "%$search%");
                    })
                    ->orWhereHas('rol', function ($q3) use ($search) {
                        $q3->where('nombre', 'like', "%$search%");
                    })
                    ->orWhereHas('tipoArea', function ($q4) use ($search) {
                        $q4->where('nombre', 'like', "%$search%");
                    });
            });
        }

        $filtered = $query->count();

        $usuarios = $query
            ->skip($request->start)
            ->take($request->length)
            ->get();

        $data = $usuarios->map(function ($u) {
            return [
                'idUsuario' => $u->idUsuario,
                'Nombre' => $u->Nombre,
                'apellidoPaterno' => $u->apellidoPaterno,
                'telefono' => $u->telefono ?? 'N/A',
                'correo' => $u->correo ?? 'N/A',
                'documento' => $u->documento ?? 'N/A',
                'usuario' => $u->usuario ?? 'N/A', // AGREGADO: campo usuario
                'estado' => $u->estado,
                'tipoDocumento' => $u->tipoDocumento->nombre ?? 'N/A',
                'tipoUsuario' => $u->tipoUsuario->nombre ?? 'N/A',
                'rol' => $u->rol->nombre ?? 'N/A',
                'tipoArea' => $u->tipoArea->nombre ?? 'N/A',
                'avatar' => $u->avatar ? 'data:image/png;base64,' . base64_encode($u->avatar) : null,
                'tieneFirma' => !empty($u->firma),
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }










    public function obtenerFirma($idUsuario)
    {
        $usuario = Usuario::find($idUsuario);

        if ($usuario && $usuario->firma) {
            // Si la firma está en binario, la convertimos a base64
            $firmaBase64 = base64_encode($usuario->firma);

            return response()->json(['firma' => 'data:image/png;base64,' . $firmaBase64], 200);
        }

        return response()->json(['message' => 'Firma no encontrada.'], 404);
    }


    public function cambiarEstado($id, Request $request)
    {
        try {
            $usuario = Usuario::findOrFail($id); // Verifica si el usuario existe

            // Cambia el estado (1 = activo, 0 = inactivo)
            $nuevoEstado = $request->input('estado');
            $usuario->estado = $nuevoEstado;
            $usuario->save();

            return response()->json(['success' => true, 'message' => 'Estado actualizado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al cambiar el estado.'], 500);
        }
    }




    public function getDocumentos($idUsuario)
    {
        try {
            $documentos = DocumentoUsuario::where('idUsuario', $idUsuario)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'documentos' => $documentos
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener documentos:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error al obtener documentos'], 500);
        }
    }

    /**
     * Subir documento para usuario
     */
    public function uploadDocumento(Request $request, $idUsuario)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tipo_documento' => 'required|in:CV,DNI,PENALES,JUDICIALES,OTROS',
                'archivo' => 'required|file|max:5120', // 5MB máximo
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verificar que el usuario existe
            $usuario = Usuario::find($idUsuario);
            if (!$usuario) {
                return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
            }

            $archivo = $request->file('archivo');
            $tipoDocumento = $request->tipo_documento;

            // Definir extensiones permitidas según tipo
            $extensionesPermitidas = [
                'CV' => ['pdf', 'doc', 'docx'],
                'DNI' => ['jpg', 'jpeg', 'png', 'pdf'],
                'PENALES' => ['pdf'],
                'JUDICIALES' => ['pdf'],
                'OTROS' => ['pdf', 'jpg', 'jpeg', 'png']
            ];

            $extension = $archivo->getClientOriginalExtension();
            if (!in_array(strtolower($extension), $extensionesPermitidas[$tipoDocumento])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tipo de archivo no permitido para este documento'
                ], 422);
            }

            // Generar nombre único para el archivo
            $nombreArchivo = 'doc_' . $idUsuario . '_' . $tipoDocumento . '_' . time() . '.' . $extension;

            // Guardar en storage (público)
            $ruta = $archivo->storeAs('documentos_usuarios', $nombreArchivo, 'public');

            // Crear registro en la base de datos
            $documento = DocumentoUsuario::create([
                'idUsuario' => $idUsuario,
                'tipo_documento' => $tipoDocumento,
                'nombre_archivo' => $archivo->getClientOriginalName(),
                'ruta_archivo' => $ruta,
                'mime_type' => $archivo->getMimeType(),
                'tamano' => $archivo->getSize()
            ]);

            Log::info('Documento subido exitosamente:', [
                'usuario' => $idUsuario,
                'tipo' => $tipoDocumento,
                'documento_id' => $documento->idDocumento
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Documento subido exitosamente',
                'documento' => $documento
            ]);
        } catch (\Exception $e) {
            Log::error('Error al subir documento:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error al subir documento'], 500);
        }
    }

    /**
     * Descargar documento
     */
    public function downloadDocumento($idDocumento)
    {
        try {
            $documento = DocumentoUsuario::findOrFail($idDocumento);

            $rutaCompleta = storage_path('app/public/' . $documento->ruta_archivo);

            if (!file_exists($rutaCompleta)) {
                return response()->json(['success' => false, 'message' => 'Archivo no encontrado'], 404);
            }

            return response()->download($rutaCompleta, $documento->nombre_archivo);
        } catch (\Exception $e) {
            Log::error('Error al descargar documento:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error al descargar documento'], 500);
        }
    }

    /**
     * Eliminar documento
     */
    public function deleteDocumento($idDocumento)
    {
        try {
            $documento = DocumentoUsuario::findOrFail($idDocumento);
            $rutaCompleta = storage_path('app/public/' . $documento->ruta_archivo);

            // Eliminar archivo físico
            if (file_exists($rutaCompleta)) {
                unlink($rutaCompleta);
            }

            // Eliminar registro
            $documento->delete();

            Log::info('Documento eliminado:', ['documento_id' => $idDocumento]);

            return response()->json([
                'success' => true,
                'message' => 'Documento eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar documento:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error al eliminar documento'], 500);
        }
    }



    /**
     * Cambiar contraseña del usuario usando bcrypt
     */
    public function cambiarPassword(Request $request, $id)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
                'new_password_confirmation' => 'required'
            ]);

            $usuario = Usuario::findOrFail($id);

            // Verificar contraseña actual usando bcrypt
            if (!password_verify($request->current_password, $usuario->clave)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La contraseña actual es incorrecta'
                ], 422);
            }

            // Verificar que la nueva contraseña no sea igual a la actual
            if (password_verify($request->new_password, $usuario->clave)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La nueva contraseña no puede ser igual a la actual'
                ], 422);
            }

            // Actualizar contraseña con bcrypt (igual que en el store)
            $usuario->clave = bcrypt($request->new_password);
            $usuario->save();

            Log::info('Contraseña cambiada exitosamente para usuario:', [
                'usuario_id' => $id,
                'email' => $usuario->correo
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contraseña cambiada exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al cambiar contraseña:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar contraseña'
            ], 500);
        }
    }

    /**
     * Desactivar cuenta de usuario
     */
    public function desactivarCuenta($id)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            $usuario->estado = 0; // 0 = inactivo
            $usuario->save();

            Log::info('Cuenta desactivada:', ['usuario_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Cuenta desactivada exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al desactivar cuenta:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al desactivar cuenta'
            ], 500);
        }
    }

    /**
     * Activar cuenta de usuario
     */
    public function activarCuenta($id)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            $usuario->estado = 1; // 1 = activo
            $usuario->save();

            Log::info('Cuenta activada:', ['usuario_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Cuenta activada exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al activar cuenta:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al activar cuenta'
            ], 500);
        }
    }

    /**
     * Enviar enlace de recuperación de contraseña
     */
    public function enviarRecuperacion($id)
    {
        try {
            $usuario = Usuario::findOrFail($id);

            // Generar token de recuperación
            $token = Str::random(60);
            $usuario->token = $token;
            $usuario->save();

            // Crear URL de recuperación
            $resetUrl = url('/reset-password/' . $token);

            // Enviar correo
            Mail::to($usuario->correo)->send(new \App\Mail\PasswordResetLinkrecuperar($resetUrl, $usuario));

            Log::info('Enlace de recuperación enviado:', [
                'usuario_id' => $id,
                'email' => $usuario->correo
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Enlace de recuperación enviado a tu correo'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al enviar enlace de recuperación:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar enlace de recuperación'
            ], 500);
        }
    }

    /**
     * Generar PDF con información del usuario
     */
    public function generarPDF($id)
    {
        try {
            $usuario = Usuario::with([
                'tipoDocumento',
                'tipoUsuario',
                'sexo',
                'rol',
                'tipoArea',
                'sucursal'
            ])->findOrFail($id);

            // Obtener documentos del usuario
            $documentos = DocumentoUsuario::where('idUsuario', $id)->get();

            // Obtener cuentas bancarias
            $cuentasBancarias = CuentasBancarias::where('idUsuario', $id)->get();

            $data = [
                'usuario' => $usuario,
                'documentos' => $documentos,
                'cuentasBancarias' => $cuentasBancarias,
                'fecha' => now()->format('d/m/Y H:i:s')
            ];

            $pdf = Pdf::loadView('pdf.usuario-info', $data);

            $nombreArchivo = 'informacion_usuario_' . $usuario->documento . '_' . date('Ymd_His') . '.pdf';

            return $pdf->download($nombreArchivo);
        } catch (\Exception $e) {
            Log::error('Error al generar PDF:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el PDF'
            ], 500);
        }
    }

    /**
     * Descargar todos los documentos del usuario en ZIP
     */
    public function descargarDocumentos($id)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            $documentos = DocumentoUsuario::where('idUsuario', $id)->get();

            if ($documentos->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay documentos para descargar'
                ], 404);
            }

            // Crear archivo ZIP
            $zip = new \ZipArchive();
            $zipFileName = 'documentos_usuario_' . $usuario->documento . '_' . time() . '.zip';
            $zipPath = storage_path('app/public/temp/' . $zipFileName);

            if (!file_exists(dirname($zipPath))) {
                mkdir(dirname($zipPath), 0777, true);
            }

            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                foreach ($documentos as $documento) {
                    $filePath = storage_path('app/public/' . $documento->ruta_archivo);
                    if (file_exists($filePath)) {
                        $zip->addFile($filePath, $documento->tipo_documento . '/' . $documento->nombre_archivo);
                    }
                }
                $zip->close();
            }

            return response()->download($zipPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error al descargar documentos:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al descargar documentos'
            ], 500);
        }
    }
    /**
     * Obtener artículos activos asignados al usuario
     */
    /**
     * Obtener TODOS los artículos asignados al usuario (sin filtrar por estado)
     */
    public function getArticulosAsignados($idUsuario)
    {
        try {
            Log::info('=== INICIANDO CONSULTA DE ARTÍCULOS ASIGNADOS ===');
            Log::info('Usuario ID:', ['id' => $idUsuario]);

            // 1. Verificar si el usuario existe
            $usuarioExiste = DB::table('usuarios')
                ->where('idUsuario', $idUsuario)
                ->exists();

            if (!$usuarioExiste) {
                Log::warning('Usuario no existe en la base de datos');
                return response()->json([
                    'success' => true,
                    'articulos' => [],
                    'message' => 'Usuario no encontrado'
                ]);
            }

            Log::info('Usuario existe en la base de datos');

            // 2. Obtener asignaciones del usuario (filtrando por estado si es necesario)
            $asignaciones = DB::table('asignaciones')
                ->where('idUsuario', $idUsuario)
                ->whereIn('estado', ['pendiente', 'activo', 'vencido']) // Solo asignaciones vigentes
                ->get();

            Log::info('Asignaciones encontradas:', [
                'count' => $asignaciones->count(),
                'ids' => $asignaciones->pluck('id')->toArray()
            ]);

            if ($asignaciones->isEmpty()) {
                Log::info('El usuario no tiene asignaciones vigentes');
                return response()->json([
                    'success' => true,
                    'articulos' => [],
                    'message' => 'El usuario no tiene asignaciones vigentes'
                ]);
            }

            // 3. Obtener IDs de asignaciones
            $asignacionIds = $asignaciones->pluck('id')->toArray();

            // 4. Obtener detalles de asignaciones (con las nuevas columnas)
            $detalles = DB::table('detalle_asignaciones')
                ->whereIn('asignacion_id', $asignacionIds)
                ->whereIn('estado_articulo', ['pendiente', 'activo', 'entregado']) // Estados que muestran artículos asignados
                ->get();

            Log::info('Detalles de asignaciones encontrados:', [
                'count' => $detalles->count(),
                'detalles_ids' => $detalles->pluck('id')->toArray()
            ]);

            if ($detalles->isEmpty()) {
                Log::info('Las asignaciones no tienen detalles/artículos');
                return response()->json([
                    'success' => true,
                    'articulos' => [],
                    'message' => 'Las asignaciones no tienen artículos detallados'
                ]);
            }

            // 5. Obtener IDs de artículos
            $articuloIds = $detalles->pluck('articulo_id')->filter()->unique()->values()->toArray();

            Log::info('IDs de artículos a buscar:', ['articulo_ids' => $articuloIds]);

            // 6. Buscar artículos
            $articulos = DB::table('articulos')
                ->whereIn('idArticulos', $articuloIds)
                ->get();

            Log::info('Artículos encontrados en BD:', [
                'count' => $articulos->count()
            ]);

            // 7. Combinar toda la información con las NUEVAS columnas
            $resultado = [];

            foreach ($detalles as $detalle) {
                // Buscar el artículo correspondiente
                $articulo = $articulos->firstWhere('idArticulos', $detalle->articulo_id);

                // Buscar la asignación correspondiente
                $asignacion = $asignaciones->firstWhere('id', $detalle->asignacion_id);

                // Determinar nombre a mostrar - USAR NUEVAS COLUMNAS
                $nombreMostrar = $detalle->nombre_articulo ?? 'Artículo ID ' . $detalle->articulo_id;
                if (empty($nombreMostrar) || $nombreMostrar == 'Artículo ID ' . $detalle->articulo_id) {
                    // Fallback a nombre del artículo si no hay en detalle
                    if ($articulo) {
                        if ($articulo->idTipoArticulo == 2 && !empty($articulo->codigo_repuesto)) {
                            $nombreMostrar = $articulo->codigo_repuesto;
                        } elseif (!empty($articulo->nombre)) {
                            $nombreMostrar = $articulo->nombre;
                        }
                    }
                }

                // Determinar tipo de asignación (uso diario o préstamo)
                $tipoAsignacion = $detalle->tipo ?? ($asignacion->tipo_asignacion ?? 'prestamo');
                $requiereDevolucion = $detalle->requiere_devolucion ?? 0;

                // Determinar fechas importantes
                $fechaAsignacion = $asignacion->fecha_asignacion ?? null;
                $fechaDevolucion = $detalle->fecha_devolucion_real ?? $detalle->fecha_devolucion_esperada ?? $asignacion->fecha_devolucion ?? null;
                $fechaEntregaReal = $detalle->fecha_entrega_real ?? $asignacion->fecha_entrega_real ?? null;

                $resultado[] = [
                    'id' => $detalle->id,
                    'articulo_id' => $detalle->articulo_id,
                    'cantidad' => (int)$detalle->cantidad,
                    'numero_serie' => $detalle->numero_serie ?: null,
                    'estado_articulo' => $detalle->estado_articulo ?? 'pendiente',
                    'nombre' => $nombreMostrar,
                    'codigo_articulo' => $detalle->codigo_articulo ?? ($articulo->codigo ?? null),
                    'nombre_articulo' => $detalle->nombre_articulo ?? ($articulo->nombre ?? null),
                    'codigo_barras' => $articulo->codigo_barras ?? null,
                    'sku' => $articulo->sku ?? null,
                    'idTipoArticulo' => $articulo->idTipoArticulo ?? 0,
                    'codigo_repuesto' => $articulo->codigo_repuesto ?? null,
                    'fecha_asignacion' => $fechaAsignacion,
                    'fecha_devolucion' => $fechaDevolucion,
                    'fecha_entrega_real' => $fechaEntregaReal,
                    'fecha_entrega_esperada' => $detalle->fecha_entrega_esperada ?? null,
                    'fecha_devolucion_esperada' => $detalle->fecha_devolucion_esperada ?? null,
                    'observaciones' => $detalle->observaciones ?? ($asignacion->observaciones ?? null),
                    'estado_asignacion' => $asignacion->estado ?? null,
                    'tipo_asignacion' => $tipoAsignacion,
                    'requiere_devolucion' => $requiereDevolucion,
                    'codigo_asignacion' => $asignacion->codigo_asignacion ?? null,
                    'codigo_solicitud' => $asignacion->codigo_solicitud ?? null,
                    'nombre_mostrar' => $nombreMostrar
                ];
            }

            Log::info('=== RESULTADO FINAL ===');
            Log::info('Total artículos procesados:', ['count' => count($resultado)]);
            Log::info('Estados encontrados:', array_count_values(array_column($resultado, 'estado_articulo')));

            return response()->json([
                'success' => true,
                'articulos' => $resultado,
                'total' => count($resultado),
                // En el array de estadísticas, línea ~80 del controlador:
                'estadisticas' => [
                    'activos' => count(array_filter($resultado, fn($a) => $a['estado_articulo'] === 'activo')),
                    'pendientes' => count(array_filter($resultado, fn($a) => $a['estado_articulo'] === 'pendiente')),
                    'entregados' => count(array_filter($resultado, fn($a) => $a['estado_articulo'] === 'entregado')),
                    'dañados' => count(array_filter($resultado, fn($a) => $a['estado_articulo'] === 'dañado')),
                    'perdidos' => count(array_filter($resultado, fn($a) => $a['estado_articulo'] === 'perdido')),
                    'devueltos' => count(array_filter($resultado, fn($a) => $a['estado_articulo'] === 'devuelto')),
                    'uso_diario' => count(array_filter($resultado, fn($a) => $a['tipo_asignacion'] === 'uso_diario')),
                    'prestamo' => count(array_filter($resultado, fn($a) => $a['tipo_asignacion'] === 'prestamo')),
                    'reposicion' => count(array_filter($resultado, fn($a) => $a['tipo_asignacion'] === 'reposicion')),
                    'trabajo_a_realizar' => count(array_filter($resultado, fn($a) => $a['tipo_asignacion'] === 'trabajo_a_realizar')), // NUEVO
                    'con_devolucion' => count(array_filter($resultado, fn($a) => $a['requiere_devolucion'] == 1))
                ],
                'debug_info' => [
                    'usuario_existe' => $usuarioExiste,
                    'asignaciones_count' => $asignaciones->count(),
                    'detalles_count' => $detalles->count(),
                    'articulos_count' => $articulos->count()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('ERROR EN CONSULTA:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Limpiar string de caracteres no UTF-8
     */
    private function cleanString($string)
    {
        if (is_null($string)) {
            return '';
        }

        // Convertir a string si no lo es
        $string = (string) $string;

        // Primero intentar con mb_convert_encoding
        $cleaned = mb_convert_encoding($string, 'UTF-8', 'UTF-8');

        // Si aún hay problemas, usar iconv
        $cleaned = @iconv('UTF-8', 'UTF-8//IGNORE', $cleaned);

        if ($cleaned === false) {
            // Si falla, usar una aproximación más agresiva
            $cleaned = preg_replace('/[^\x{0000}-\x{007F}]/u', '', $string);
        }

        return $cleaned ?: '';
    }





    /**
     * Mostrar formulario para restablecer contraseña
     */
    public function showResetForm($token)
    {
        $usuario = Usuario::where('token', $token)->first();

        if (!$usuario) {
            return redirect('/')->with('error', 'El enlace de recuperación es inválido o ha expirado');
        }

        return view('auth.reset-password', compact('token'));
    }

    /**
     * Procesar restablecimiento de contraseña usando bcrypt
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        $usuario = Usuario::where('token', $request->token)->first();

        if (!$usuario) {
            return back()->with('error', 'El enlace de recuperación es inválido o ha expirado');
        }

        // Actualizar contraseña con bcrypt
        $usuario->clave = bcrypt($request->password);
        $usuario->token = null; // Limpiar token
        $usuario->save();

        Log::info('Contraseña restablecida exitosamente:', [
            'usuario_id' => $usuario->idUsuario,
            'email' => $usuario->correo
        ]);

        return redirect('/login')->with('success', 'Contraseña restablecida exitosamente');
    }








///NUEVOS DATOS DEL CONTROLADOR


    /**
     * Actualizar información general del usuario
     */
    public function updateInformacionGeneral(Request $request, $id)
    {
        try {
            $usuario = Usuario::findOrFail($id);

            $validated = $request->validate([
                'Nombre' => 'required|string|max:255',
                'apellidoPaterno' => 'required|string|max:255',
                'apellidoMaterno' => 'required|string|max:255',
                'idSexo' => 'nullable|integer',
                'idTipoDocumento' => 'required|integer',
                'documento' => 'required|string|max:255|unique:usuarios,documento,' . $id . ',idUsuario',
                'telefono' => 'required|string|max:255|unique:usuarios,telefono,' . $id . ',idUsuario',
                'estadocivil' => 'nullable|integer|in:1,2,3,4',
                'correo' => 'required|email|max:255|unique:usuarios,correo,' . $id . ',idUsuario',
                'correo_personal' => 'nullable|email|max:255|unique:usuarios,correo_personal,' . $id . ',idUsuario',
                'profile-image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Actualizar datos básicos
            $usuario->Nombre = $request->Nombre;
            $usuario->apellidoPaterno = $request->apellidoPaterno;
            $usuario->apellidoMaterno = $request->apellidoMaterno;
            $usuario->idSexo = $request->idSexo;
            $usuario->idTipoDocumento = $request->idTipoDocumento;
            $usuario->documento = $request->documento;
            $usuario->telefono = $request->telefono;
            $usuario->estadocivil = $request->estadocivil;
            $usuario->correo = $request->correo;
            $usuario->correo_personal = $request->correo_personal;

            // Procesar imagen si se subió
            if ($request->hasFile('profile-image')) {
                $image = $request->file('profile-image');
                $imageData = file_get_contents($image->getRealPath());
                $usuario->avatar = $imageData;
            }

            $usuario->save();

            return response()->json([
                'success' => true,
                'message' => 'Información general actualizada correctamente',
                'data' => [
                    'avatar' => $usuario->avatar ? 'data:image/jpeg;base64,' . base64_encode($usuario->avatar) : null,
                    'Nombre' => $usuario->Nombre,
                    'apellidoPaterno' => $usuario->apellidoPaterno,
                    'apellidoMaterno' => $usuario->apellidoMaterno,
                    'correo' => $usuario->correo,
                    'correo_personal' => $usuario->correo_personal
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar información general:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la información general'
            ], 500);
        }
    }

    /**
     * Actualizar fecha de nacimiento
     */
    public function updateFechaNacimiento(Request $request, $id)
    {
        try {
            $request->validate([
                'nacimiento_dia' => 'required|numeric|min:1|max:31',
                'nacimiento_mes' => 'required|numeric|min:1|max:12',
                'nacimiento_anio' => 'required|numeric|min:1900|max:' . date('Y'),
            ]);

            $usuario = Usuario::findOrFail($id);

            $fechaNacimiento = sprintf(
                '%04d-%02d-%02d',
                $request->nacimiento_anio,
                $request->nacimiento_mes,
                $request->nacimiento_dia
            );

            $usuario->fechaNacimiento = $fechaNacimiento;
            $usuario->save();

            $edad = \Carbon\Carbon::parse($fechaNacimiento)->age;

            return response()->json([
                'success' => true,
                'message' => 'Fecha de nacimiento actualizada correctamente',
                'data' => [
                    'fechaNacimiento' => $fechaNacimiento,
                    'edad' => $edad
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar fecha de nacimiento:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la fecha de nacimiento'
            ], 500);
        }
    }

    /**
     * Actualizar lugar de nacimiento
     */
    public function updateLugarNacimiento(Request $request, $id)
    {
        try {
            $request->validate([
                'nacimiento_departamento' => 'required|string',
                'nacimiento_provincia' => 'required|string',
                'nacimiento_distrito' => 'required|string',
            ]);

            $usuario = Usuario::findOrFail($id);

            $fichaGeneral = $usuario->fichaGeneral ?? new \App\Models\UsuarioFichaGeneral();
            $fichaGeneral->idUsuario = $id;
            $fichaGeneral->nacimientoDepartamento = $request->nacimiento_departamento;
            $fichaGeneral->nacimientoProvincia = $request->nacimiento_provincia;
            $fichaGeneral->nacimientoDistrito = $request->nacimiento_distrito;
            $fichaGeneral->save();

            return response()->json([
                'success' => true,
                'message' => 'Lugar de nacimiento actualizado correctamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar lugar de nacimiento:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el lugar de nacimiento'
            ], 500);
        }
    }

    /**
     * Actualizar información académica
     */
    public function updateEstudios(Request $request, $id)
    {
        try {
            $usuario = Usuario::findOrFail($id);

            // Array de niveles a procesar
            $niveles = [
                'secundaria' => 'SECUNDARIA',
                'tecnico' => 'TECNICO',
                'universitario' => 'UNIVERSITARIO',
                'postgrado' => 'POSTGRADO'
            ];

            $estudiosGuardados = [];

            foreach ($niveles as $key => $nivel) {
                // Verificar si se enviaron datos para este nivel
                $termino = $request->input($key . '_termino');
                $centro = $request->input($key . '_centro');
                $especialidad = $request->input($key . '_especialidad');
                $grado = $request->input($key . '_grado');
                $inicio = $request->input($key . '_inicio');
                $fin = $request->input($key . '_fin');

                // Solo guardar si el usuario marcó que terminó (SI) o si hay datos
                if ($termino == '1' || ($centro && $termino !== '0')) {
                    $estudioData = [
                        'termino' => $termino,
                        'centroEstudios' => $centro,
                        'especialidad' => $especialidad,
                        'gradoAcademico' => $grado,
                        'fechaInicio' => $inicio ? $inicio . '-01-01' : null,
                        'fechaFin' => $fin ? $fin . '-12-31' : null
                    ];

                    $estudio = \App\Models\UsuarioEstudio::updateOrCreate(
                        [
                            'idUsuario' => $id,
                            'nivel' => $nivel
                        ],
                        $estudioData
                    );

                    $estudiosGuardados[$key] = $estudio;
                } else {
                    // Si marcó NO, eliminar el registro si existe
                    \App\Models\UsuarioEstudio::where('idUsuario', $id)
                        ->where('nivel', $nivel)
                        ->delete();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Información académica actualizada correctamente',
                'data' => $estudiosGuardados
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar estudios:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la información académica'
            ], 500);
        }
    }


    /**
     * Actualizar seguro y pensión
     */
    public function updateSeguroPension(Request $request, $id)
    {
        try {
            $usuario = Usuario::findOrFail($id);

            $fichaGeneral = $usuario->fichaGeneral ?? new \App\Models\UsuarioFichaGeneral();
            $fichaGeneral->idUsuario = $id;
            $fichaGeneral->seguroSalud = $request->seguroSalud;
            $fichaGeneral->sistemaPensiones = $request->sistemaPensiones;
            $fichaGeneral->afpCompania = $request->sistemaPensiones == 'AFP' ? $request->afpCompania : null;
            $fichaGeneral->save();

            return response()->json([
                'success' => true,
                'message' => 'Seguro y pensión actualizados correctamente'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar seguro y pensión:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar seguro y pensión'
            ], 500);
        }
    }







    /**
     * Guardar información de salud
     */
    public function guardarSalud(Request $request, $id)
    {
        try {
            $usuario = Usuario::findOrFail($id);

            $request->validate([
                'vacuna_covid' => 'nullable|in:0,1',
                'covid_dosis1' => 'nullable|date',
                'covid_dosis2' => 'nullable|date',
                'covid_dosis3' => 'nullable|date',
                'dolencia_cronica' => 'nullable|in:0,1',
                'dolencia_detalle' => 'nullable|string|max:500',
                'discapacidad' => 'nullable|in:0,1',
                'discapacidad_detalle' => 'nullable|string|max:500',
                'tipo_sangre' => 'nullable|string|max:10'
            ]);

            $salud = $usuario->salud ?? new \App\Models\UsuarioSalud();
            $salud->idUsuario = $id;
            $salud->vacunaCovid = $request->vacuna_covid !== null ? (bool)$request->vacuna_covid : null;
            $salud->covidDosis1 = $request->covid_dosis1;
            $salud->covidDosis2 = $request->covid_dosis2;
            $salud->covidDosis3 = $request->covid_dosis3;
            $salud->dolenciaCronica = $request->dolencia_cronica !== null ? (bool)$request->dolencia_cronica : null;
            $salud->dolenciaDetalle = $request->dolencia_detalle;
            $salud->discapacidad = $request->discapacidad !== null ? (bool)$request->discapacidad : null;
            $salud->discapacidadDetalle = $request->discapacidad_detalle;
            $salud->tipoSangre = $request->tipo_sangre;
            $salud->save();

            return response()->json([
                'success' => true,
                'message' => 'Información de salud guardada correctamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al guardar salud:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la información de salud'
            ], 500);
        }
    }

    /**
     * Guardar familiar
     */
    public function guardarFamiliar(Request $request)
    {
        try {
            $request->validate([
                'idUsuario' => 'required|integer|exists:usuarios,idUsuario',
                'parentesco' => 'required|string|in:CONYUGE,CONCUBINO,HIJO',
                'apellidosNombres' => 'required|string|max:255',
                'nroDocumento' => 'nullable|string|max:50',
                'ocupacion' => 'nullable|string|max:255',
                'sexo' => 'nullable|string|max:20',
                'fechaNacimiento' => 'nullable|date',
                'domicilioActual' => 'nullable|string|max:255'
            ]);

            $familiar = UsuarioFamilia::create([
                'idUsuario' => $request->idUsuario,
                'parentesco' => $request->parentesco,
                'apellidosNombres' => $request->apellidosNombres,
                'nroDocumento' => $request->nroDocumento,
                'ocupacion' => $request->ocupacion,
                'sexo' => $request->sexo,
                'fechaNacimiento' => $request->fechaNacimiento,
                'domicilioActual' => $request->domicilioActual
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Familiar agregado correctamente',
                'familiar' => $familiar
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al guardar familiar:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el familiar'
            ], 500);
        }
    }

    /**
     * Obtener familiar por ID
     */
    public function getFamiliar($id)
    {
        try {
            $familiar = UsuarioFamilia::findOrFail($id);

            return response()->json([
                'success' => true,
                'familiar' => $familiar
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Familiar no encontrado'
            ], 404);
        }
    }

    /**
     * Actualizar familiar
     */
    public function updateFamiliar(Request $request, $id)
    {
        try {
            $familiar = UsuarioFamilia::findOrFail($id);

            $request->validate([
                'parentesco' => 'required|string|in:CONYUGE,CONCUBINO,HIJO',
                'apellidosNombres' => 'required|string|max:255',
                'nroDocumento' => 'nullable|string|max:50',
                'ocupacion' => 'nullable|string|max:255',
                'sexo' => 'nullable|string|max:20',
                'fechaNacimiento' => 'nullable|date',
                'domicilioActual' => 'nullable|string|max:255'
            ]);

            $familiar->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Familiar actualizado correctamente',
                'familiar' => $familiar
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar familiar:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el familiar'
            ], 500);
        }
    }

    /**
     * Eliminar familiar
     */
    public function deleteFamiliar($id)
    {
        try {
            $familiar = UsuarioFamilia::findOrFail($id);
            $familiar->delete();

            return response()->json([
                'success' => true,
                'message' => 'Familiar eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar familiar:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el familiar'
            ], 500);
        }
    }

    /**
     * Guardar contacto de emergencia
     */
    public function guardarContactoEmergencia(Request $request)
    {
        try {
            $request->validate([
                'idUsuario' => 'required|integer|exists:usuarios,idUsuario',
                'apellidosNombres' => 'required|string|max:255',
                'parentesco' => 'required|string|max:100',
                'direccionTelefono' => 'required|string|max:255'
            ]);

            $contacto = UsuarioEmergenciaContacto::create([
                'idUsuario' => $request->idUsuario,
                'apellidosNombres' => $request->apellidosNombres,
                'parentesco' => $request->parentesco,
                'direccionTelefono' => $request->direccionTelefono
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contacto de emergencia agregado correctamente',
                'contacto' => $contacto
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al guardar contacto:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el contacto'
            ], 500);
        }
    }

    /**
     * Actualizar contacto de emergencia
     */
    public function updateContactoEmergencia(Request $request, $id)
    {
        try {
            $contacto = UsuarioEmergenciaContacto::findOrFail($id);

            $request->validate([
                'apellidosNombres' => 'required|string|max:255',
                'parentesco' => 'required|string|max:100',
                'direccionTelefono' => 'required|string|max:255'
            ]);

            $contacto->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Contacto actualizado correctamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar contacto:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el contacto'
            ], 500);
        }
    }

    /**
     * Eliminar contacto de emergencia
     */
    public function deleteContactoEmergencia($id)
    {
        try {
            $contacto = \App\Models\UsuarioEmergenciaContacto::findOrFail($id);
            $contacto->delete();

            return response()->json([
                'success' => true,
                'message' => 'Contacto eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar contacto:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el contacto'
            ], 500);
        }
    }

    /**
     * Actualizar información laboral y de configuración
     */
    public function updateInformacion(Request $request, $id)
    {
        try {
            $usuario = Usuario::findOrFail($id);

            // Validar datos - CORREGIDO: sucursal (sin s) y sueldoMensual
            $request->validate([
                // Datos laborales
                'idTipoContrato' => 'nullable|integer|exists:tipos_contrato,idTipoContrato',
                'fechaInicio' => 'nullable|date',
                'fechaTermino' => 'nullable|date|after_or_equal:fechaInicio',
                'horaInicioJornada' => 'nullable|string',
                'horaFinJornada' => 'nullable|string',
                'areaTexto' => 'nullable|string|max:255',
                'cargoTexto' => 'nullable|string|max:255',

                // Datos de configuración - CORREGIDO: sucursal (sin s)
                'idSucursal' => 'nullable|integer|exists:sucursal,idSucursal',
                'idTipoArea' => 'nullable|integer|exists:tipoarea,idTipoArea',
                'idTipoUsuario' => 'nullable|integer|exists:tipousuario,idTipoUsuario',
                'idRol' => 'nullable|integer|exists:rol,idRol',
                'idSexo' => 'nullable|integer|exists:sexo,idSexo',
                'sueldoMensual' => 'nullable|numeric|min:0' // CAMBIADO: sueldoPorHora -> sueldoMensual
            ]);

            // Actualizar datos del usuario - CORREGIDO: sueldoMensual
            $usuario->idSucursal = $request->idSucursal;
            $usuario->idTipoArea = $request->idTipoArea;
            $usuario->idTipoUsuario = $request->idTipoUsuario;
            $usuario->idRol = $request->idRol;
            $usuario->idSexo = $request->idSexo;
            $usuario->sueldoMensual = $request->sueldoMensual; // CAMBIADO: sueldoPorHora -> sueldoMensual
            $usuario->save();

            // Actualizar datos laborales
            $laboral = $usuario->laboral ?? new \App\Models\UsuarioLaboral();
            $laboral->idUsuario = $id;
            $laboral->idTipoContrato = $request->idTipoContrato;
            $laboral->fechaInicio = $request->fechaInicio;
            $laboral->fechaTermino = $request->fechaTermino;
            $laboral->horaInicioJornada = $request->horaInicioJornada;
            $laboral->horaFinJornada = $request->horaFinJornada;
            $laboral->areaTexto = $request->areaTexto;
            $laboral->cargoTexto = $request->cargoTexto;
            $laboral->save();

            return response()->json([
                'success' => true,
                'message' => 'Información actualizada correctamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar información:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la información'
            ], 500);
        }
    }

    public function exportarFichaUsuario($id)
    {
        try {
            $usuario = Usuario::with([
                'tipoDocumento',
                'sexo',
                'fichaGeneral',
                'estudios',
                'cursos',
                'familiares',
                'salud',
                'contactosEmergencia',
                'laboral',  // <--- Agrega esta línea
                'tipoArea',  // <--- Agrega esta línea
                'tipoUsuario' // <--- Agrega esta línea
            ])->findOrFail($id);

            $export = new UsuarioFichaExport($usuario);
            $nombreArchivo = 'Ficha_' . $usuario->Nombre . '_' . $usuario->apellidoPaterno . '.xlsx';

            return Excel::download($export, $nombreArchivo);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al exportar: ' . $e->getMessage()
            ], 500);
        }
    }



    /**
     * Visualizar documento en el navegador
     */
    public function view($idDocumento)
    {
        try {
            $documento = DocumentoUsuario::findOrFail($idDocumento);

            $rutaCompleta = storage_path('app/public/' . $documento->ruta_archivo);

            if (!file_exists($rutaCompleta)) {
                return response()->json(['success' => false, 'message' => 'Archivo no encontrado'], 404);
            }

            // Determinar el tipo MIME del archivo
            $extension = strtolower(pathinfo($rutaCompleta, PATHINFO_EXTENSION));
            $mimeTypes = [
                'pdf' => 'application/pdf',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'txt' => 'text/plain',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls' => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ];

            $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

            return response()->file($rutaCompleta, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $documento->nombre_archivo . '"'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al visualizar documento:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error al visualizar documento'], 500);
        }
    }
    /**
     * Guardar cuenta bancaria
     */
    public function guardarCuentaBancaria(Request $request, $id)
    {
        try {
            $request->validate([
                'entidadBancaria' => 'required|string',
                'moneda' => 'required|string',
                'tipoCuenta' => 'required|string',
                'numeroCuenta' => 'required|string',
                'numeroCCI' => 'required|string'
            ]);

            $usuario = Usuario::findOrFail($id);

            // Actualizar o crear ficha general
            $fichaGeneral = $usuario->fichaGeneral ?? new \App\Models\UsuarioFichaGeneral();
            $fichaGeneral->idUsuario = $id;
            $fichaGeneral->entidadBancaria = $request->entidadBancaria;
            $fichaGeneral->moneda = $request->moneda;
            $fichaGeneral->tipoCuenta = $request->tipoCuenta;
            $fichaGeneral->numeroCuenta = $request->numeroCuenta;
            $fichaGeneral->numeroCCI = $request->numeroCCI;
            $fichaGeneral->save();

            return response()->json([
                'success' => true,
                'message' => 'Cuenta bancaria guardada correctamente',
                'data' => [
                    'entidadBancaria' => $fichaGeneral->entidadBancaria,
                    'moneda' => $fichaGeneral->moneda,
                    'tipoCuenta' => $fichaGeneral->tipoCuenta,
                    'numeroCuenta' => $fichaGeneral->numeroCuenta,
                    'numeroCCI' => $fichaGeneral->numeroCCI
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al guardar cuenta bancaria:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la cuenta bancaria'
            ], 500);
        }
    }
}
