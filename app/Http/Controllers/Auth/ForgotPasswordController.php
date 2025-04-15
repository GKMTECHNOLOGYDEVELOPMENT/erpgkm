<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    //


      // Mostrar formulario de recuperación
      public function showLinkRequestForm()
      {
          return view('auth.forgot-password');
      }

     
      
      public function sendResetLinkEmail(Request $request)
      {
          // Validar el correo electrónico
          $request->validate([
              'correo' => 'required|email|exists:usuarios,correo', // Usamos 'correo' en lugar de 'email'
          ]);
      
          // Registrar el correo en el log para verificar que se está recibiendo correctamente
          Log::info('Correo de recuperación recibido: ' . $request->correo);
      
          // Enviar el enlace de restablecimiento de la contraseña
          $response = Password::sendResetLink(
              ['email' => $request->correo]  // Aquí, estamos usando 'email' para la función de Password
          );
      
          // Registrar la respuesta de la función de Password
          Log::info('Respuesta de envío de enlace de recuperación: ' . $response);
      
          // Si el enlace se envió correctamente
          return $response == Password::RESET_LINK_SENT
              ? back()->with('status', '¡Enlace de recuperación enviado a tu correo!')
              : back()->withErrors(['correo' => 'No se pudo enviar el enlace. Intenta de nuevo.']);
      }
      
      
      



      
}
