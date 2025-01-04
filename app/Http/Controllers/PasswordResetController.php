<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class PasswordResetController extends Controller
{
    public function show()
    {
        return view('auth.cover-password-reset'); // Vista de restablecimiento de contraseña
    }
}
