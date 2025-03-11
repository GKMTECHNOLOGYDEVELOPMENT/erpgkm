<?php

namespace App\Http\Controllers\usuario;

use App\Http\Controllers\Controller;
use App\Mail\UsuarioCreado;
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
            $request->validate([
                'Nombre' => 'required|string|max:255',
                'apellidoPaterno' => 'required|string|max:255',
                'apellidoMaterno' => 'required|string|max:255',
                'idTipoDocumento' => 'required|integer',
                'documento' => 'required|string|max:255',
                'telefono' => 'required|string|max:255',
                'correo' => 'required|email|max:255',
                'profile-image' => 'nullable|image|max:1024',
            ]);
    
            $imageData = $request->hasFile('profile-image') ? file_get_contents($request->file('profile-image')) : null;
    
            $usuario = strtolower(substr($request->Nombre, 0, 6)) . strtolower(substr($request->apellidoPaterno, 0, 6)) . rand(1, 9);
            $usuario = str_replace(' ', '', $usuario);
            $clave = Str::random(8);
            $claveEncriptada = bcrypt($clave);
    
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
    
            Mail::to($request->correo)->send(new UsuarioCreado($usuario, $clave));
    
            return response()->json([
                'success' => true,
                'message' => 'Usuario creado y datos enviados al correo.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al crear el usuario.'], 500);
        }
    }
    
    





    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id); // Buscar al usuario por id
        $tiposDocumento = TipoDocumento::all(); // Si es necesario obtener tipos de documento
        $sucursales = Sucursal::all(); // Obtener todas las sucursales
        $tiposUsuario = Tipousuario::all(); // Obtener todos los tipos de usuario
        $sexos = Sexo::all(); // Obtener todos los sexos
        $roles = Rol::all(); // Obtener todos los roles
        $tiposArea = Tipoarea::all(); // Obtener todos los tipos de área
        // Obtener los datos de los archivos JSON
        $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true);
        $provincias = json_decode(file_get_contents(public_path('ubigeos/provincias.json')), true);
        $distritos = json_decode(file_get_contents(public_path('ubigeos/distritos.json')), true);

        // Buscar el departamento correspondiente a la usuario
        $departamentoSeleccionado = array_filter($departamentos, function ($departamento) use ($usuario) {
            return $departamento['id_ubigeo'] == $usuario->departamento;
        });
        $departamentoSeleccionado = reset($departamentoSeleccionado);  // Obtener el primer valor del array filtrado

        // Obtener provincias del departamento seleccionado
        $provinciasDelDepartamento = [];
        foreach ($provincias as $provincia) {
            if (isset($provincia['id_padre_ubigeo']) && $provincia['id_padre_ubigeo'] == $departamentoSeleccionado['id_ubigeo']) {
                $provinciasDelDepartamento[] = $provincia;
            }
        }

        // Buscar la provincia seleccionada en el array de provinciasDelDepartamento
        $provinciaSeleccionada = null;
        foreach ($provinciasDelDepartamento as $provincia) {
            if (isset($provincia['id_ubigeo']) && $provincia['id_ubigeo'] == $usuario->provincia) {
                $provinciaSeleccionada = $provincia;
                break;
            }
        }

        // Obtener los distritos correspondientes a la provincia seleccionada
        $distritosDeLaProvincia = [];
        foreach ($distritos as $distrito) {
            if (isset($distrito['id_padre_ubigeo']) && $distrito['id_padre_ubigeo'] == $provinciaSeleccionada['id_ubigeo']) {
                $distritosDeLaProvincia[] = $distrito;
            }
        }

        // Definir distritoSeleccionado como null si no es necesario
        $distritoSeleccionado = null;  // Si no es necesario, puedes omitir esta línea también

        return view('usuario.edit', compact('usuario', 'tiposDocumento', 'sucursales', 'tiposUsuario', 'sexos', 'roles', 'tiposArea', 'departamentos', 'provinciasDelDepartamento', 'provinciaSeleccionada', 'distritosDeLaProvincia', 'distritoSeleccionado'));
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










    public function config(Request $request, $id)
    {
        // Validación de los campos, asegurando que no se repitan en la base de datos
        $request->validate([
            'sueldoPorHora' => 'required|numeric',
            'idSucursal' => 'required|integer|exists:sucursal,idSucursal',  // Verifica si la sucursal existe
            'idTipoUsuario' => 'required|integer|exists:tipousuario,idTipoUsuario',  // Verifica si el tipo de usuario existe
            'idSexo' => 'required|integer|exists:sexo,idSexo',  // Verifica si el sexo existe
            'idRol' => 'required|integer|exists:rol,idRol',  // Verifica si el rol existe
            'idTipoArea' => 'required|integer|exists:tipoarea,idTipoArea',  // Verifica si el tipo de área existe
        ]);

        // Obtener el usuario
        $usuario = Usuario::findOrFail($id);

        // Actualizar los campos
        $usuario->sueldoPorHora = $request->sueldoPorHora;
        $usuario->idSucursal = $request->idSucursal;
        $usuario->idTipoUsuario = $request->idTipoUsuario;
        $usuario->idSexo = $request->idSexo;
        $usuario->idRol = $request->idRol;
        $usuario->idTipoArea = $request->idTipoArea;

        // Guardar los cambios
        $usuario->save();

        // Respuesta exitosa en formato JSON
        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado correctamente',
            'usuario' => $usuario
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


    public function getUsuarios()
    {
        $usuarios = Usuario::with(['tipoDocumento', 'tipoUsuario', 'rol', 'tipoArea'])->get();
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
