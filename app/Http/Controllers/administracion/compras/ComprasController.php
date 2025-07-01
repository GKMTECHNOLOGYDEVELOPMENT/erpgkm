<?php

namespace App\Http\Controllers\administracion\compras;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComprasController extends Controller
{
    public function index()
    {
        
        return view('administracion.compras.index');
    }
}
