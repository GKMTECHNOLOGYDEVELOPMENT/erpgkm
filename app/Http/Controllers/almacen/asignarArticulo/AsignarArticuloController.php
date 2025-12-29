<?php

namespace App\Http\Controllers\almacen\asignarArticulo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AsignarArticuloController extends Controller
{
    public function index()
    {
        return view('almacen.asignar-articulos.index');
    }

    public function create()
    {
        // Solo retorna la vista estática
        return view('almacen.asignar-articulos.create');
    }
}
