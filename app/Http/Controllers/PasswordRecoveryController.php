<?php
// app/Http/Controllers/PasswordRecoveryController.php

namespace App\Http\Controllers;

use App\Mail\PasswordRecoveryMail;
use Illuminate\Http\Request;
use App\Models\Usuario; // Si tu modelo es Usuario
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;


class PasswordRecoveryController extends Controller
{
    public function sendRecoveryLink(Request $request)
    {
        // Validar que el correo sea válido y exista en la base de datos
        $request->validate([
            'correo' => 'required|email|exists:usuarios,correo', // Validación del correo
        ]);
    
        // Registrar que la validación fue exitosa
        Log::info('Correo de recuperación recibido:', ['correo' => $request->correo]);
    
        // Obtener el usuario con el correo ingresado
        $usuario = Usuario::where('correo', $request->correo)->first();
    
        if ($usuario) {
            // Registrar que el usuario fue encontrado
            Log::info('Usuario encontrado:', ['usuario_id' => $usuario->idUsuario, 'correo' => $usuario->correo]);
        } else {
            // Si no se encuentra el usuario, registramos un mensaje de advertencia
            Log::warning('Usuario no encontrado para el correo:', ['correo' => $request->correo]);
        }
    
        // Generar un token de recuperación
        $token = Str::random(60);
        Log::info('Token de recuperación generado:', ['token' => $token]);
    
        // Enviar un correo con el token
        try {
            Mail::to($usuario->correo)->send(new PasswordRecoveryMail($token));
            Log::info('Correo de recuperación enviado a:', ['correo' => $usuario->correo]);
        } catch (\Exception $e) {
            // Registrar cualquier error al enviar el correo
            Log::error('Error al enviar el correo de recuperación:', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Hubo un problema al enviar el correo de recuperación.']);
        }
    
        // Opcional: Guardar el token en la base de datos (si decides hacerlo)
        // Usuario::where('correo', $request->correo)->update(['reset_token' => $token]);
    
        // Devolver respuesta de éxito
        return back()->with('status', '¡Te hemos enviado un enlace para recuperar tu contraseña!');
    }
}
