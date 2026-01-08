<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Registrar en los logs cada vez que se accede al formulario de login
        Log::info('Acceso al formulario de login.');

        // También podemos registrar si el usuario ya está autenticado o no
        if (auth()->check()) {
            Log::info('Usuario ya autenticado: ' . auth()->id());
        } else {
            Log::info('Ningún usuario autenticado.');
        }

        return view('auth.login');
    }






    // app/Http\Controllers\Auth\LoginController.php
    public function login(Request $request)
    {
        // Validar las credenciales del usuario
        Log::info('Intentando iniciar sesión con el correo: ' . $request->email);

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Intentar obtener el usuario por correo
        $usuario = Usuario::where('correo', $credentials['email'])->first();

        if (!$usuario) {
            Log::warning('Correo no encontrado: ' . $credentials['email']);

            // Retornar con mensaje para toastr
            return back()->with([
                'toastr_type' => 'error',
                'toastr_message' => 'El correo electrónico no está registrado.',
                'email' => $credentials['email']
            ]);
        }

        // Verificar si la clave es correcta
        if (!Hash::check($credentials['password'], $usuario->clave)) {
            Log::warning('Contraseña incorrecta para el correo: ' . $credentials['email']);

            // Retornar con mensaje para toastr
            return back()->with([
                'toastr_type' => 'error',
                'toastr_message' => 'La contraseña es incorrecta.',
                'email' => $credentials['email']
            ]);
        }

        // Si las credenciales son correctas, intentar autenticar
        Auth::login($usuario);
        $request->session()->regenerate();

        Log::info('Usuario autenticado: ' . $usuario->idUsuario .
            ' - Rol: ' . $usuario->idRol .
            ' - TipoUsuario: ' . $usuario->idTipoUsuario .
            ' - TipoArea: ' . $usuario->idTipoArea);

        // Mensaje de éxito
        $request->session()->flash('toastr_type', 'success');
        $request->session()->flash('toastr_message', '¡Inicio de sesión exitoso!');

        // Redirigir al dashboard correspondiente
        return $this->redirigirSegunDashboard($usuario);
    }


    // app/Http\Controllers\Auth\LoginController.php
    private function redirigirSegunDashboard($usuario)
    {
        // Obtener el dashboard principal del usuario
        $dashboard = $usuario->getDashboardPrincipal();

        if (!$dashboard) {
            Log::warning('Usuario ' . $usuario->idUsuario . ' no tiene acceso a ningún dashboard');

            // Si no tiene dashboards, mostrar error y logout
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'acceso' => 'No tiene permisos para acceder al sistema. Contacte al administrador.'
            ]);
        }

        Log::info('Redirigiendo usuario ' . $usuario->idUsuario .
            ' a dashboard: ' . $dashboard['nombre']);

        // Redirigir según lo disponible
        if (isset($dashboard['route']) && $dashboard['route'] == 'index') {
            // Dashboard principal/administración
            return redirect()->route('index');
        } elseif (isset($dashboard['url'])) {
            // Usar URL directa
            return redirect($dashboard['url']);
        } elseif (isset($dashboard['route'])) {
            // Usar ruta con nombre
            return redirect()->route($dashboard['route']);
        } else {
            // Fallback al dashboard principal
            return redirect()->route('index');
        }
    }

    // O una versión más simple:
    private function redirigirSegunDashboardSimple($usuario)
    {
        $dashboard = $usuario->getDashboardPrincipal();

        if (!$dashboard) {
            Log::warning('Usuario ' . $usuario->idUsuario . ' no tiene acceso a ningún dashboard');
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'acceso' => 'No tiene permisos para acceder al sistema.'
            ]);
        }

        Log::info('Redirigiendo a: ' . $dashboard['nombre']);

        // Priorizar URL directa, luego ruta con nombre
        if (isset($dashboard['url'])) {
            return redirect($dashboard['url']);
        } elseif (isset($dashboard['route'])) {
            return redirect()->route($dashboard['route']);
        }

        // Default
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
