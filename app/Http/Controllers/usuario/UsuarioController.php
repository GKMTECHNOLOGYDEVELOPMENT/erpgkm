<?php

namespace App\Http\Controllers\usuario;

use App\Http\Controllers\Controller;
use App\Mail\UsuarioCreado;
use App\Models\CuentasBancarias;
use App\Models\Rol;
use App\Models\Sexo;
use App\Models\Sucursal;
use App\Models\Tipoarea;
use App\Models\Tipodocumento;
use App\Models\Tipousuario;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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

    public function store(Request $request)
{
    try {
        Log::info('Inicio del proceso de creación de usuario.');

        // Validación
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'apellidoPaterno' => 'required|string|max:255',
            'apellidoMaterno' => 'required|string|max:255',
            'idTipoDocumento' => 'required|integer',
            'documento' => 'required|string|max:255|unique:usuarios,documento',
            'telefono' => 'required|string|max:255|unique:usuarios,telefono',
            'correo' => 'required|email|max:255|unique:usuarios,correo',
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
        $usuarioNuevo->avatar = $imageData;
        $usuarioNuevo->usuario = $usuario;
        $usuarioNuevo->clave = $claveEncriptada;
        $usuarioNuevo->estado = 1;
        $usuarioNuevo->save();
        Log::info('Usuario creado exitosamente:', ['usuario' => $usuarioNuevo]);

        // Enviar correo
        Mail::to($request->correo)->send(new UsuarioCreado($usuario, $clave));
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
        'numeroCuenta' => 'required|string|max:255',
        'usuarioId' => 'required|integer|exists:usuarios,idUsuario', // Asegurarse de que el usuario existe
    ]);

    // Crear la cuenta bancaria
    $cuentaBancaria = new CuentasBancarias();
    $cuentaBancaria->tipodecuenta = $request->tipoCuenta;
    $cuentaBancaria->numerocuenta = $request->numeroCuenta;
    $cuentaBancaria->idUsuario = $request->usuarioId;

    // Guardar en la base de datos
    $cuentaBancaria->save();

    // Devolver una respuesta JSON indicando que todo salió bien
    return response()->json(['success' => true, 'message' => 'Cuenta bancaria guardada con éxito']);
}




    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'apellidoPaterno' => 'required|string|max:255',
            'apellidoMaterno' => 'required|string|max:255',
            'idTipoDocumento' => 'required|integer',
            'documento' => 'required|string|max:255|unique:usuarios,documento,' . $id . ',idUsuario', // Asegúrate de que no se repita el documento
            'telefono' => 'required|string|max:255|unique:usuarios,telefono,' . $id . ',idUsuario', // Asegúrate de que no se repita el teléfono
            'correo' => 'required|email|max:255|unique:usuarios,correo,' . $id . ',idUsuario', // Asegúrate de que no se repita el correo
            'profile-image' => 'nullable|image|max:1024',
        ]);

        Log::info('Inicio de la actualización de usuario', ['user_id' => $id]);

        // Buscar al usuario y actualizar sus datos
        $usuario = Usuario::findOrFail($id);

        Log::info('Usuario encontrado', ['usuario' => $usuario]);

        // Si se ha subido una imagen de perfil, actualizarla
        if ($request->hasFile('profile-image')) {
            $image = $request->file('profile-image');
            // Asegurarse de que la imagen esté correctamente codificada en base64
            $imageData = base64_encode(file_get_contents($image)); // Convertir la imagen a base64

            Log::info('Imagen de perfil cargada', ['image_name' => $image->getClientOriginalName(), 'image_size' => $image->getSize()]);

            $usuario->avatar = $imageData;
        }

        // Actualizar los demás campos
        $usuario->Nombre = $request->Nombre;
        $usuario->apellidoPaterno = $request->apellidoPaterno;
        $usuario->apellidoMaterno = $request->apellidoMaterno;
        $usuario->idTipoDocumento = $request->idTipoDocumento;
        $usuario->documento = $request->documento;
        $usuario->telefono = $request->telefono;
        $usuario->correo = $request->correo;

        Log::info('Datos de usuario actualizados', [
            'Nombre' => $usuario->Nombre,
            'apellidoPaterno' => $usuario->apellidoPaterno,
            'apellidoMaterno' => $usuario->apellidoMaterno,
            'telefono' => $usuario->telefono,
            'correo' => $usuario->correo,
        ]);

        $usuario->save();

        Log::info('Usuario guardado exitosamente', ['usuario_id' => $usuario->idUsuario]);

        // Devolver la respuesta JSON con los datos actualizados
        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado correctamente',
            'usuario' => $usuario,
            'avatar' => $usuario->avatar // Si deseas enviar el avatar actualizado
        ], 200); // Asegúrate de enviar el código de estado 200 (OK)
    }










    // public function config(Request $request, $id)
    // {
    //     // Validación de los campos, asegurando que no se repitan en la base de datos
    //     $request->validate([
    //         'sueldoPorHora' => 'required|numeric',
    //         'idSucursal' => 'required|integer|exists:sucursal,idSucursal',  // Verifica si la sucursal existe
    //         'idTipoUsuario' => 'required|integer|exists:tipousuario,idTipoUsuario',  // Verifica si el tipo de usuario existe
    //         'idSexo' => 'required|integer|exists:sexo,idSexo',  // Verifica si el sexo existe
    //         'idRol' => 'required|integer|exists:rol,idRol',  // Verifica si el rol existe
    //         'idTipoArea' => 'required|integer|exists:tipoarea,idTipoArea',  // Verifica si el tipo de área existe
    //     ]);

    //     // Obtener el usuario
    //     $usuario = Usuario::findOrFail($id);

    //     // Actualizar los campos
    //     $usuario->sueldoPorHora = $request->sueldoPorHora;
    //     $usuario->idSucursal = $request->idSucursal;
    //     $usuario->idTipoUsuario = $request->idTipoUsuario;
    //     $usuario->idSexo = $request->idSexo;
    //     $usuario->idRol = $request->idRol;
    //     $usuario->idTipoArea = $request->idTipoArea;

    //     // Guardar los cambios
    //     $usuario->save();

    //     // Respuesta exitosa en formato JSON
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Usuario actualizado correctamente',
    //         'usuario' => $usuario
    //     ], 200);
    // }


    public function config(Request $request, $id)
    {
        // Validación de los campos
        $request->validate([
            'sueldoPorHora' => 'required|numeric',
            'idSucursal' => 'required|integer|exists:sucursal,idSucursal',
            'idTipoUsuario' => 'required|integer|exists:tipousuario,idTipoUsuario',
            'idSexo' => 'required|integer|exists:sexo,idSexo',
            'idRol' => 'required|integer|exists:rol,idRol',
            'idTipoArea' => 'required|integer|exists:tipoarea,idTipoArea',
        ]);
    
        Log::info('Validación completada', ['request_data' => $request->all()]);
    
        // Obtener el usuario
        $usuario = Usuario::findOrFail($id);
        Log::info('Usuario encontrado', ['usuario' => $usuario]);
    
 // Convertir la firma a Base64 si existe
 $firmaBase64 = null;
 if ($usuario->firma) {
     $firmaBase64 = base64_encode($usuario->firma);
     // Validar si la firma base64 es correcta
     if (base64_decode($firmaBase64, true) === false) {
         Log::error('Firma no válida en base64', ['usuario_id' => $usuario->idUsuario]);
         $firmaBase64 = null;
     } else {
         $firmaBase64 = "data:image/png;base64," . $firmaBase64;
     }
 } else {
     Log::info('No se encontró firma para el usuario');
 }

 // Convertir el avatar a Base64 si existe
 $avatarBase64 = null;
 if ($usuario->avatar) {
     $avatarBase64 = base64_encode($usuario->avatar);
     // Validar si el avatar base64 es correcto
     if (base64_decode($avatarBase64, true) === false) {
         Log::error('Avatar no válido en base64', ['usuario_id' => $usuario->idUsuario]);
         $avatarBase64 = null;
     } else {
         $avatarBase64 = "data:image/png;base64," . $avatarBase64;
     }
 } else {
     Log::info('No se encontró avatar para el usuario');
 }
    
        // Actualizar los campos
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
            'usuario' => $usuario,
            'firmaBase64' => $firmaBase64,
            'avatarBase64' => $avatarBase64
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

    public function getUsuarios()
    {
        // Log: Iniciando la obtención de usuarios
        Log::debug('Iniciando la obtención de usuarios con relaciones');
    
        // Obtener usuarios con sus relaciones
        $usuarios = Usuario::with(['tipoDocumento', 'tipoUsuario', 'rol', 'tipoArea'])->get();
    
        // Log: Usuarios obtenidos
        Log::debug('Usuarios obtenidos: ', ['usuarios' => $usuarios]);
    
        // Convertir los campos 'avatar' y 'firma' a base64
        foreach ($usuarios as $usuario) {
            if ($usuario->avatar) {
                // Convertir el avatar a base64
                $usuario->avatar = base64_encode($usuario->avatar);
                Log::debug('Avatar convertido a base64 para el usuario', ['id' => $usuario->idUsuario, 'avatar' => $usuario->avatar]);
            }
    
            if ($usuario->firma) {
                // Convertir la firma a base64
                $usuario->firma = base64_encode($usuario->firma);
                Log::debug('Firma convertida a base64 para el usuario', ['id' => $usuario->idUsuario, 'firma' => $usuario->firma]);
            }
        }
    
        // Log: Finalizando la función y enviando la respuesta
        Log::debug('Finalizando la función getUsuarios, enviando respuesta');
    
        // Devolver los datos como respuesta JSON
        return response()->json($usuarios);
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
}
