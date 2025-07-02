<?php

namespace App\Http\Controllers\solicitud;

use App\Http\Controllers\Controller;


class SolicitudarticuloController extends Controller
{



    public function index (){


        return view("solicitud.solicitudarticulo.index");
    }

public function create()
{
    $usuario = auth()->user()->load('tipoArea');
    $areas = \App\Models\TipoArea::all();

    $articulos = \App\Models\Articulo::with('tipoArticulo') // 👈 Aquí cargamos la relación
                    ->where('estado', 1)
                    ->get();

    return view("solicitud.solicitudarticulo.create", [
        'usuario' => $usuario,
        'areas' => $areas,
        'articulos' => $articulos
    ]);
}


}