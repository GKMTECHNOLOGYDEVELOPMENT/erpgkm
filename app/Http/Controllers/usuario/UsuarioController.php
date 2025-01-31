<?php

namespace App\Http\Controllers\usuario;

use App\Http\Controllers\Controller;
use App\Models\Tipodocumento;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Create 
        return view('usuario.create', compact('departamentos', 'tiposDocumento'));

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
    return view('usuario.edit', compact('usuario', 'tiposDocumento'));
}

public function update(Request $request, $id)
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
        'profile-image' => 'nullable|image|max:1024',
    ]);

    // Buscar al usuario y actualizar sus datos
    $usuario = Usuario::findOrFail($id);

    if ($request->hasFile('profile-image')) {
        $image = $request->file('profile-image');
        $imageData = file_get_contents($image); // Obtener el contenido binario de la imagen
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
    $usuario->save();

    return redirect()->route('usuario.edit', ['usuario' => $usuario->idUsuario])
        ->with('success', 'Usuario actualizado correctamente');
}


}
