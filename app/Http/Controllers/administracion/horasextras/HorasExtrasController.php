<?php

namespace App\Http\Controllers\administracion\horasextras;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HorasExtrasController extends Controller
{
    //

    public function index()
    {
        return view('administracion.horasextras.index'); // 👈 Ruta actualizada
    }
}
