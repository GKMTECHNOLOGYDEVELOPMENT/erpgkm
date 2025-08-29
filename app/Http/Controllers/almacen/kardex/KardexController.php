<?php

namespace App\Http\Controllers\almacen\kardex;
use App\Http\Controllers\Controller;

use App\Models\Articulo;
use App\Models\Kardex;
use App\Models\Modelo;
use App\Models\Moneda;
use App\Models\Tipoarea;
use App\Models\Tipoarticulo;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Picqer\Barcode\BarcodeGeneratorPNG;

class KardexController extends Controller
{
    public function index(){


        return view('almacen.kardex.index');
    }


public function kardexxproducto($id)
{
    $articulo = Articulo::findOrFail($id);
    
    // Obtener todos los movimientos del kardex para este artículo ordenados por fecha descendente
    $movimientos = Kardex::where('idArticulo', $id)
                        ->orderBy('fecha', 'desc')
                        ->paginate(10);
    
    return view('almacen.kardex.producto.index', compact('articulo', 'movimientos'));
}

public function detalles($idArticulo, $id)
{
    // Obtener el artículo
    $articulo = Articulo::findOrFail($idArticulo);
    
    // Obtener el movimiento específico del kardex usando el campo 'id'
    $movimiento = Kardex::where('id', $id)
                       ->where('idArticulo', $idArticulo)
                       ->firstOrFail();
    
    return view('almacen.kardex.producto.detalles', compact('articulo', 'movimiento'));
}


    
    
}