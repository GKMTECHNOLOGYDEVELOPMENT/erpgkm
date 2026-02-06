<?php

namespace App\Http\Controllers\administracion\formulariopersonal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormularioPersonalEmpleadoController extends Controller
{
   public function create()
    {
        return view('administracion.formulariopersonal.create');
    }}
