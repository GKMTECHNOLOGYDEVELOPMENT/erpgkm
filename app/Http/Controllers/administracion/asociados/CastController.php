<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CastController extends Controller
{
    public function index()
    {
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.cast'); 
    }
}
