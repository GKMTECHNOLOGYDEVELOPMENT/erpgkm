<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.boxed-signin');
    }

    public function login(Request $request)
    {
        // Validar las credenciales del usuario
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Intentar obtener el usuario por correo
        $usuario = \App\Models\Usuario::where('correo', $credentials['email'])->first();
    
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
    

    public function checkEmail(Request $request)
{
    $email = $request->input('email');
    
    // Verificar si el correo existe en la base de datos
    $usuario = Usuario::where('correo', $email)->first();

    if ($usuario) {
        return response()->json(['exists' => true]);
    } else {
        return response()->json(['exists' => false]);
    }
}
    

    
public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Eliminar la cookie de la sesión manualmente si persiste
    $cookie = cookie('laravel_session', '', -1); 

    return redirect('/')->withCookie($cookie);
}

}
