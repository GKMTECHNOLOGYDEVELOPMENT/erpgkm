<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMail;

class PasswordResetController extends Controller
{
    public function showPasswordResetForm()
    {
        Log::info('===== PASSWORD RESET - Acceso al formulario =====');
        return view('auth.cover-password-reset');
    }

   public function sendResetLink(Request $request)
{
    Log::info('===== PASSWORD RESET - Inicio sendResetLink =====');
    Log::info('Datos recibidos en request:', $request->all());
    Log::info('Método: ' . $request->method());
    Log::info('URL: ' . $request->fullUrl());
    
    try {
        Log::info('Validando datos...');
        
        // Validar solo que sea email requerido y válido
        $request->validate([
            'email' => 'required|email'  // Quita el exists temporalmente
        ], [
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.'
        ]);
        
        Log::info('Validación exitosa. Email: ' . $request->email);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Error en validación:', $e->errors());
        Log::error('Trace completo:', ['trace' => $e->getTraceAsString()]);
        throw $e;
    } catch (\Exception $e) {
        Log::error('Error general en validación: ' . $e->getMessage());
        Log::error('Trace: ' . $e->getTraceAsString());
        throw $e;
    }

    // Buscar manualmente en la BD por 'correo'
    Log::info('Buscando usuario en BD con correo: ' . $request->email);
    
    try {
        $usuario = Usuario::where('correo', $request->email)->first();
        
        if ($usuario) {
            Log::info('Usuario encontrado. ID: ' . $usuario->idUsuario);
            Log::info('Datos usuario:', [
                'id' => $usuario->idUsuario,
                'correo' => $usuario->correo,
                'nombre' => $usuario->nombre,
                'token_actual' => $usuario->token
            ]);
        } else {
            Log::warning('Usuario NO encontrado con correo: ' . $request->email);
            Log::info('Query ejecutada: SELECT * FROM usuarios WHERE correo = ?', [$request->email]);
        }
        
    } catch (\Exception $e) {
        Log::error('Error buscando usuario: ' . $e->getMessage());
        Log::error('Query error: ' . $e->getTraceAsString());
        return back()->withErrors([
            'email' => 'Error al buscar el usuario en la base de datos.'
        ])->withInput();
    }

    if (!$usuario) {
        Log::info('Retornando error: usuario no encontrado');
        return back()->withErrors([
            'email' => 'No encontramos una cuenta con ese correo electrónico.'
        ])->withInput();
    }

    // Generar token
    $token = Str::random(64);
    Log::info('Token generado: ' . $token);
    
    try {
        // DEBUG: Verificar datos antes de actualizar
        Log::info('Antes de update - Usuario:', [
            'id' => $usuario->idUsuario,
            'correo' => $usuario->correo,
            'token_actual' => $usuario->token // Ver token actual
        ]);
        
        // INTENTA CON DIFERENTES MÉTODOS PARA VER CUÁL FUNCIONA
        
        // Método 1: Usar save() en lugar de update()
        $usuario->token = $token;
        $usuario->save();
        
        Log::info('Token guardado con save() - Usuario ID: ' . $usuario->idUsuario);
        
        // Refrescar para verificar
        $usuario->refresh();
        Log::info('Después de refresh - token: ' . $usuario->token);
        
        // Método alternativo: Verificar con nueva consulta
        $usuarioVerificado = Usuario::find($usuario->idUsuario);
        Log::info('Verificación con nueva consulta - token: ' . $usuarioVerificado->token);
        
    } catch (\Exception $e) {
        Log::error('Error actualizando token: ' . $e->getMessage());
        Log::error('SQL Error: ' . $e->getTraceAsString());
        
        // Intentar con DB facade
        try {
            \Illuminate\Support\Facades\DB::table('usuarios')
                ->where('idUsuario', $usuario->idUsuario)
                ->update(['token' => $token]);
                
            Log::info('Token actualizado con DB facade');
            
            // Verificar
            $tokenDB = \Illuminate\Support\Facades\DB::table('usuarios')
                ->where('idUsuario', $usuario->idUsuario)
                ->value('token');
                
            Log::info('Token verificado en DB: ' . $tokenDB);
            
        } catch (\Exception $dbError) {
            Log::error('Error con DB facade: ' . $dbError->getMessage());
            return back()->withErrors([
                'email' => 'Error al generar el token de recuperación.'
            ]);
        }
    }

    // ENVIAR CORREO - ESTA PARTE FALTABA
    try {
        Log::info('Enviando correo a: ' . $usuario->correo);
        
        // DEBUG: Verificar qué token se va a enviar
        $usuarioParaCorreo = Usuario::find($usuario->idUsuario);
        Log::info('Token que se enviará en el correo: ' . ($usuarioParaCorreo->token ?? 'NULL'));
        
        // Generar la URL para el correo
        $resetUrl = route('password.reset', $token);
        Log::info('URL de reset generada: ' . $resetUrl);
        
        // Enviar correo
        Mail::to($usuario->correo)->send(new PasswordResetMail($token, $usuario->correo));
        
        Log::info('Correo de recuperación enviado a: ' . $usuario->correo);
        Log::info('===== PASSWORD RESET - Proceso exitoso =====');
        
        return redirect()->route('login')->with('success', 'Te hemos enviado un correo con las instrucciones para restablecer tu contraseña.');
        
    } catch (\Exception $e) {
        Log::error('Error enviando correo de recuperación: ' . $e->getMessage());
        Log::error('Trace correo: ' . $e->getTraceAsString());
        return back()->withErrors([
            'email' => 'Error al enviar el correo. Por favor, inténtalo de nuevo.'
        ]);
    }
}
public function showResetForm($token = null)
{
    Log::info('===== PASSWORD RESET - Mostrando formulario con token =====');
    Log::info('Token recibido: ' . ($token ?? 'NO TOKEN'));
    
    if (!$token) {
        Log::warning('No se recibió token en la URL');
        return redirect()->route('password.request')->withErrors([
            'token' => 'El enlace de recuperación no es válido.'
        ]);
    }
    
    $usuario = Usuario::where('token', $token)->first();

    if (!$usuario) {
        Log::warning('Token no válido o expirado: ' . $token);
        return redirect()->route('password.request')->withErrors([
            'token' => 'El enlace de recuperación es inválido o ha expirado.'
        ]);
    }

    Log::info('Token válido. Usuario ID: ' . $usuario->idUsuario);
    
    return view('auth.reset-password', [
        'token' => $token, 
        'correo' => $usuario->correo  // Cambiado a 'correo'
    ]);
}


public function resetPassword(Request $request)
{
    Log::info('===== PASSWORD RESET - Procesando nueva contraseña =====');
    Log::info('Datos recibidos:', $request->all());
    
    try {
        Log::info('Validando datos para reset...');
        
        // Cambia 'email' por 'correo' en la validación
        $request->validate([
            'token' => 'required',
            'correo' => 'required|email',  // ← Cambiado a 'correo'
            'password' => 'required|min:6|confirmed',
        ], [
            'correo.required' => 'El campo correo electrónico es obligatorio.',  // ← Cambiado
            'correo.email' => 'Debe ingresar un correo electrónico válido.',  // ← Cambiado
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.'
        ]);
        
        Log::info('Validación exitosa para reset');
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Error en validación de reset:', $e->errors());
        throw $e;
    }

    Log::info('Buscando usuario con token y correo...');
    Log::info('Correo: ' . $request->correo);  // ← Cambiado
    Log::info('Token: ' . $request->token);
    
    try {
        // Buscar por 'correo' en lugar de 'email'
        $usuario = Usuario::where('correo', $request->correo)  // ← Cambiado
                         ->where('token', $request->token)
                         ->first();

        if ($usuario) {
            Log::info('Usuario encontrado para reset. ID: ' . $usuario->idUsuario);
        } else {
            Log::warning('Usuario NO encontrado con token y correo');
            Log::info('Query: SELECT * FROM usuarios WHERE correo = ? AND token = ?', 
                     [$request->correo, $request->token]);  // ← Cambiado
        }
        
    } catch (\Exception $e) {
        Log::error('Error buscando usuario para reset: ' . $e->getMessage());
        return back()->withErrors([
            'correo' => 'Error al verificar los datos.'  // ← Cambiado
        ]);
    }

    if (!$usuario) {
        Log::info('Retornando error: token/correo no válidos');
        return back()->withErrors([
            'correo' => 'El enlace de recuperación es inválido o ha expirado.'  // ← Cambiado
        ]);
    }

    try {
        Log::info('Actualizando contraseña para usuario ID: ' . $usuario->idUsuario);
        
        // CAMBIO AQUÍ: Usar bcrypt() en lugar de Hash::make()
        $usuario->update([
            'clave' => bcrypt($request->password),  // ← CAMBIADO A bcrypt()
            'token' => null
        ]);
        
        Log::info('Contraseña actualizada exitosamente para usuario ID: ' . $usuario->idUsuario);
        Log::info('===== PASSWORD RESET - Proceso completado =====');
        
        return redirect()->route('login')->with('success', 'Tu contraseña ha sido restablecida exitosamente. Ahora puedes iniciar sesión.');
        
    } catch (\Exception $e) {
        Log::error('Error actualizando contraseña: ' . $e->getMessage());
        Log::error('Trace actualización: ' . $e->getTraceAsString());
        return back()->withErrors([
            'password' => 'Error al actualizar la contraseña.'
        ]);
    }
}
}