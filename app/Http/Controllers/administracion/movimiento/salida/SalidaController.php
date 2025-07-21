<?php

namespace App\Http\Controllers\administracion\movimiento\salida;

use App\Http\Controllers\Controller;
use App\Models\Modelo;
use App\Models\Proveedore;
use App\Models\Subcategoria;
use App\Models\Tipoarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalidaController extends Controller
{
    public function salida()
    {
        $proveedores = Proveedore::where('estado', 1)->get(['idProveedor as id', 'nombre']);
        $subcategorias = Subcategoria::all(['id as id', 'nombre']);
        $modelos = Modelo::with(['marca:idMarca,nombre', 'categoria:idCategoria,nombre'])->get();
        $areas = Tipoarea::all(['idTipoArea as id', 'nombre']);

        return view('almacen.repuestos.movimiento.salida.index', compact(
            'proveedores', 'subcategorias', 'modelos', 'areas'
        ));
    }
}
