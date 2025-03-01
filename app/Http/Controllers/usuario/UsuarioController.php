<?php

namespace App\Http\Controllers\usuario;

use App\Http\Controllers\Controller;
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
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{

    public function index(){

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
        return view('usuario.create', compact( 'tiposDocumento','sucursales', 'tiposUsuario', 'sexos', 'roles', 'tiposArea','departamentos'));

    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'apellidoPaterno' => 'required|string|max:255',
            'apellidoMaterno' => 'required|string|max:255',
            'idTipoDocumento' => 'required|integer',
            'documento' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'profile-image' => 'nullable|image|max:1024', // Validar si se sube una imagen (máximo 1MB)
        ]);

        // Si hay una imagen, la procesamos
        if ($request->hasFile('profile-image')) {
            $image = $request->file('profile-image');
            $imageData = file_get_contents($image); // Obtener el contenido binario de la imagen
        } else {
            $imageData = null; // Si no se seleccionó una imagen, no se guarda nada
        }

        // Crear un nuevo usuario y guardar los datos
        $usuario = new Usuario();
        $usuario->Nombre = $request->Nombre;
        $usuario->apellidoPaterno = $request->apellidoPaterno;
        $usuario->apellidoMaterno = $request->apellidoMaterno;
        $usuario->idTipoDocumento = $request->idTipoDocumento;
        $usuario->documento = $request->documento;
        $usuario->telefono = $request->telefono;
        $usuario->correo = $request->correo;
        $usuario->avatar = $imageData; // Guardar la imagen binaria
        $usuario->save();

        // Redirigir al usuario o mostrar un mensaje de éxito
        return redirect()->route('usuario.edit', ['usuario' => $usuario->idUsuario])
        ->with('success', 'Usuario creado correctamente');
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
      $departamentoSeleccionado = array_filter($departamentos, function($departamento) use ($usuario) {
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

    return view('usuario.edit', compact('usuario', 'tiposDocumento','sucursales', 'tiposUsuario', 'sexos', 'roles', 'tiposArea','departamentos','provinciasDelDepartamento','provinciaSeleccionada','distritosDeLaProvincia','distritoSeleccionado'));
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








public function direccion(Request $request, $id)
{
    // Validar los datos del formulario
    $request->validate([
        'nacionalidad' => 'required|string|max:255',
        'departamento' => 'required|integer',
        'provincia' => 'required|integer',
        'distrito' => 'required|integer',
        'direccion' => 'required|string|max:255',
    ]);

    // Obtener el usuario con el ID proporcionado
    $usuario = Usuario::findOrFail($id);

    // Actualizar los datos del usuario
    $usuario->nacionalidad = $request->nacionalidad;
    $usuario->departamento = $request->departamento;
    $usuario->provincia = $request->provincia;
    $usuario->distrito = $request->distrito;
    $usuario->direccion = $request->direccion;

    // Guardar los cambios en la base de datos
    $usuario->save();

    // Redirigir al usuario con un mensaje de éxito
    return redirect()->route('usuario.index')->with('success', 'Usuario actualizado correctamente');
}


 // Método para actualizar la firma
 public function actualizarFirma(Request $request, $idUsuario)
 {
     // Validar la firma
     $request->validate([
         'signature' => 'required|string', // Asegurarse de que la firma no esté vacía
     ]);

     // Obtener la firma base64 del formulario
     $signatureBase64 = $request->input('signature');

     // Convertir la firma base64 a binario
     $signatureBinaria = base64_decode($signatureBase64);

     // Aquí puedes elegir si guardar la firma en una base de datos como un archivo binario
     // o guardarla como un archivo en el sistema de almacenamiento de Laravel.

     // Si decides guardar la firma como un archivo en el sistema de archivos:
     $path = Storage::put('public/firmas/' . $idUsuario . '.png', $signatureBinaria);

     // Si decides guardarla en la base de datos (como un campo tipo blob o binario):
     // Suponiendo que tienes un campo 'firma' en tu modelo Usuario que almacena los datos binarios
     $usuario = Usuario::findOrFail($idUsuario);
     $usuario->firma = $signatureBinaria;
     $usuario->save();

     // Retornar una respuesta, por ejemplo:
     return redirect()->back()->with('success', 'Firma actualizada correctamente');
 }



}
