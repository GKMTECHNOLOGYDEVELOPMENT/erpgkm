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
          $request->validate(['email' => 'required|email']);
      
          $response = Password::broker('users')->sendResetLink(
              $request->only('email')
          );
      
          return $response == Password::RESET_LINK_SENT
              ? back()->with('status', trans($response))
              : back()->withErrors(['email' => trans($response)]);
      }
      
      



      
}
