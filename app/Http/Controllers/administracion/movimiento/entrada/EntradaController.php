<?php

namespace App\Http\Controllers\administracion\movimiento\entrada;

use App\Http\Controllers\Controller;
use App\Models\Modelo;
use App\Models\Proveedore;
use App\Models\Subcategoria;
use App\Models\Tipoarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntradaController extends Controller
{
public function entrada()
{
    $proveedores = Proveedore::where('estado', 1)->get(['idProveedor as id', 'nombre']);
    $subcategorias = Subcategoria::all(['id as id', 'nombre']);
    $modelos = Modelo::with(['marca:idMarca,nombre', 'categoria:idCategoria,nombre'])->get();
    $areas = Tipoarea::all(['idTipoArea as id', 'nombre']);

    return view('almacen.repuestos.movimiento.entrada.index', compact(
        'proveedores', 'subcategorias', 'modelos', 'areas'
    ));
}


}
