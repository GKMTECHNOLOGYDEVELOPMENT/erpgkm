<?php

namespace App\Http\Controllers\administracion\cotizaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class cotizacionController extends Controller
{
    public function index () {
        return view('cotizaciones.agregar-cotizacion');
    }
}
