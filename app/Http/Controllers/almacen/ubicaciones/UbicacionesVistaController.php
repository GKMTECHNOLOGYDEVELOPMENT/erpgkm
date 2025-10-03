<?php

namespace App\Http\Controllers\almacen\ubicaciones;

use App\Http\Controllers\Controller;

class UbicacionesVistaController extends Controller
{
    public function vistaAlmacen()
    {
        return view('almacen.ubicaciones.vista-almacen');
    }

    public function detalleRack()
    {

        return view('almacen.ubicaciones.detalle-rack');
    }
}
