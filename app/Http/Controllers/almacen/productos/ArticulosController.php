<?php

namespace App\Http\Controllers\almacen\productos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticulosController extends Controller
{
    public function index()
    {
        return view('almacen.productos.articulos');
    }
}
