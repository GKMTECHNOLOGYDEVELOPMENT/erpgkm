<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
      public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validar las credenciales del usuario
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Intentar obtener el usuario por correo
        $usuario = Usuario::where('correo', $credentials['email'])->first();
    
        if (!$usuario) {
            return back()->withErrors([
                'email' => 'El correo electrónico no está registrado.',
            ]);
        }
    
        // Verificar si la clave es correcta
        if (!Hash::check($credentials['password'], $usuario->clave)) {
            return back()->withErrors([
                'password' => 'La contraseña es incorrecta.',
            ]);
        }
    
        // Si las credenciales son correctas, intentar autenticar
        Auth::login($usuario);
        $request->session()->regenerate();
    
        return redirect()->route('index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        // Eliminar la cookie de la sesión manualmente si persiste
        $cookie = cookie('laravel_session', '', -1); 
    
        return redirect('/login')->withCookie($cookie);
    }

    public function someFunction()
{
    // Obtener el usuario autenticado
    $usuario = Auth::user();

    // Pasar el usuario a la vista
    return view('components.common.header', compact('usuario'));
}


public function show($id)
{
    $usuario = Auth::user();  // O lo que necesites para obtener el usuario
    return view('components.common.header', compact('usuario'));
}


}
