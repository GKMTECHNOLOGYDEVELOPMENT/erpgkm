<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class LockscreenController extends Controller
{
    public function show()
    {
        return view('auth.cover-lockscreen'); // Vista de la pantalla de bloqueo
    }
}
