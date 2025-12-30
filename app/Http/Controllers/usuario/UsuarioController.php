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
use App\Models\Tipodocumento;
use App\Models\Tipousuario;
use App\Models\Usuario;
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
    // Intentamos obtener el usuario por su id
    $usuario = Usuario::findOrFail($id); // Buscar al usuario por id
    Log::info('Usuario encontrado:', ['usuario' => $usuario]);

       // Obtener las cuentas bancarias del usuario
       $cuentasBancarias = CuentasBancarias::where('idUsuario', $id)->get(); // Asumiendo que Cuentasbancarias es el modelo para la tabla cuentasbancarias
       Log::info('Cuentas bancarias del usuario:', ['cuentasBancarias' => $cuentasBancarias]);


        // Convertir la firma y la imagen del usuario a Base64
    $firmaBase64 = null;
    $avatarBase64 = null;

    // Verificar si la firma está en formato binario, si es así convertirla
    if ($usuario->firma) {
        $firmaBase64 = base64_encode($usuario->firma); // Convertir la firma a base64
        $firmaBase64 = "data:image/png;base64," . $firmaBase64; // Prependiendo el encabezado necesario para mostrar como imagen
    }

    // Verificar si el avatar (imagen) está en formato binario, si es así convertirla
    if ($usuario->avatar) {
        $avatarBase64 = base64_encode($usuario->avatar); // Convertir el avatar a base64
        $avatarBase64 = "data:image/png;base64," . $avatarBase64; // Prependiendo el encabezado necesario para mostrar como imagen
    }



    // Obtener los datos para los selectores
    $tiposDocumento = TipoDocumento::all(); // Si es necesario obtener tipos de documento
    $sucursales = Sucursal::all(); // Obtener todas las sucursales
    $tiposUsuario = Tipousuario::all(); // Obtener todos los tipos de usuario
    $sexos = Sexo::all(); // Obtener todos los sexos
    $roles = Rol::all(); // Obtener todos los roles
    $tiposArea = Tipoarea::all(); // Obtener todos los tipos de área
    Log::info('Datos de selección obtenidos:', [
        'tiposDocumento' => $tiposDocumento,
        'sucursales' => $sucursales,
        'tiposUsuario' => $tiposUsuario,
        'sexos' => $sexos,
        'roles' => $roles,
        'tiposArea' => $tiposArea
    ]);


    Log::info('Departamento del usuario:', ['departamento' => $usuario->departamento]);
    Log::info('Provincia del usuario:', ['provincia' => $usuario->provincia]);
    Log::info('Distrito del usuario:', ['distrito' => $usuario->distrito]);


    // Obtener los datos de los archivos JSON
    $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true);
    $provincias = json_decode(file_get_contents(public_path('ubigeos/provincias.json')), true);
    $distritos = json_decode(file_get_contents(public_path('ubigeos/distritos.json')), true);
    Log::info('Datos de archivos JSON cargados:', [
        'departamentos' => count($departamentos),
        'provincias' => count($provincias),
        'distritos' => count($distritos)
    ]);

    Log::info('Departamento cargado del archivo JSON:', ['departamentos' => $departamentos]);


    // Buscar el departamento correspondiente al usuario
    $departamentoSeleccionado = array_filter($departamentos, function ($departamento) use ($usuario) {
        return $departamento['id_ubigeo'] == $usuario->departamento;
    });
    $departamentoSeleccionado = reset($departamentoSeleccionado);  // Obtener el primer valor del array filtrado
    Log::info('Departamento seleccionado:', ['departamento' => $departamentoSeleccionado]);

    // Obtener provincias del departamento seleccionado
    $provinciasDelDepartamento = [];
    foreach ($provincias as $provincia) {
        if (isset($provincia['id_padre_ubigeo']) && $provincia['id_padre_ubigeo'] == $departamentoSeleccionado['id_ubigeo']) {
            $provinciasDelDepartamento[] = $provincia;
        }
    }
    Log::info('Provincias del departamento seleccionado:', ['provincias' => count($provinciasDelDepartamento)]);

    // Buscar la provincia seleccionada
    $provinciaSeleccionada = null;
    foreach ($provinciasDelDepartamento as $provincia) {
        if (isset($provincia['id_ubigeo']) && $provincia['id_ubigeo'] == $usuario->provincia) {
            $provinciaSeleccionada = $provincia;
            break;
        }
    }
    Log::info('Provincia seleccionada:', ['provincia' => $provinciaSeleccionada]);

    // Obtener los distritos correspondientes a la provincia seleccionada
    $distritosDeLaProvincia = [];
    foreach ($distritos as $distrito) {
        if (isset($distrito['id_padre_ubigeo']) && $distrito['id_padre_ubigeo'] == $provinciaSeleccionada['id_ubigeo']) {
            $distritosDeLaProvincia[] = $distrito;
        }
    }
    Log::info('Distritos de la provincia seleccionada:', ['distritos' => count($distritosDeLaProvincia)]);

    // Definir distritoSeleccionado como null si no es necesario
    $distritoSeleccionado = null;

    // Devolver la vista con los datos requeridos
    return view('usuario.edit', compact('usuario', 'tiposDocumento', 'sucursales', 'tiposUsuario', 'sexos', 'roles', 'tiposArea', 'departamentos', 'provinciasDelDepartamento', 'provinciaSeleccionada', 'distritosDeLaProvincia', 'distritoSeleccionado', 'cuentasBancarias'));
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
        
        // 2. Verificar asignaciones del usuario
        $asignaciones = DB::table('asignaciones')
            ->where('idUsuario', $idUsuario)
            ->get();
            
        Log::info('Asignaciones encontradas:', [
            'count' => $asignaciones->count(),
            'ids' => $asignaciones->pluck('id')->toArray()
        ]);
        
        if ($asignaciones->isEmpty()) {
            Log::info('El usuario no tiene asignaciones registradas');
            return response()->json([
                'success' => true,
                'articulos' => [],
                'message' => 'El usuario no tiene asignaciones registradas'
            ]);
        }
        
        // 3. Obtener IDs de asignaciones
        $asignacionIds = $asignaciones->pluck('id')->toArray();
        
        // 4. Verificar detalles de asignaciones
        $detalles = DB::table('detalle_asignaciones')
            ->whereIn('asignacion_id', $asignacionIds)
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
            'count' => $articulos->count(),
            'articulos' => $articulos->toArray()
        ]);
        
        // 7. Combinar toda la información
        $resultado = [];
        
        foreach ($detalles as $detalle) {
            // Buscar el artículo correspondiente
            $articulo = $articulos->firstWhere('idArticulos', $detalle->articulo_id);
            
            // Buscar la asignación correspondiente
            $asignacion = $asignaciones->firstWhere('id', $detalle->asignacion_id);
            
            // Determinar nombre a mostrar
            $nombreMostrar = 'Artículo ID ' . $detalle->articulo_id;
            
            if ($articulo) {
                if ($articulo->idTipoArticulo == 2 && !empty($articulo->codigo_repuesto)) {
                    $nombreMostrar = $articulo->codigo_repuesto;
                } elseif (!empty($articulo->nombre)) {
                    $nombreMostrar = $articulo->nombre;
                }
            }
            
            $resultado[] = [
                'id' => $detalle->id,
                'articulo_id' => $detalle->articulo_id,
                'cantidad' => (int)$detalle->cantidad,
                'numero_serie' => $detalle->numero_serie ?: 'N/A',
                'estado_articulo' => $detalle->estado_articulo,
                'nombre' => $articulo->nombre ?? 'No encontrado',
                'codigo_barras' => $articulo->codigo_barras ?? null,
                'sku' => $articulo->sku ?? null,
                'idTipoArticulo' => $articulo->idTipoArticulo ?? 0,
                'codigo_repuesto' => $articulo->codigo_repuesto ?? null,
                'fecha_asignacion' => $asignacion->fecha_asignacion ?? null,
                'fecha_devolucion' => $asignacion->fecha_devolucion ?? null,
                'observaciones' => $asignacion->observaciones ?? null,
                'estado_asignacion' => $asignacion->estado ?? null,
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
}
