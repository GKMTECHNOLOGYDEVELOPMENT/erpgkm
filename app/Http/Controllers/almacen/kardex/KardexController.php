<?php

namespace App\Http\Controllers\almacen\kardex;
use App\Http\Controllers\Controller;

use App\Models\Articulo;
use App\Models\DetalleCompra;
use App\Models\InventarioIngresoCliente;
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
  public function index(Request $request)
    {
        // Obtener parámetros de búsqueda y filtrado
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        // Consulta base para obtener movimientos de kardex
        $query = Kardex::with('articulo')
            ->join('articulos', 'kardex.idArticulo', '=', 'articulos.idArticulos')
            ->select('kardex.*', 'articulos.nombre', 'articulos.codigo_barras');
        
        // Aplicar filtros si existen
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('articulos.nombre', 'like', "%{$search}%")
                  ->orWhere('articulos.codigo_barras', 'like', "%{$search}%");
            });
        }
        
        if ($startDate) {
            $query->whereDate('kardex.fecha', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('kardex.fecha', '<=', $endDate);
        }
        
        // Ordenar y paginar resultados
        $movimientos = $query->orderBy('kardex.fecha', 'desc')
                            ->paginate(20);
        
        // Obtener estadísticas generales
        $totalArticulos = Articulo::count();
        $totalMovimientos = Kardex::count();
        
        // Si hay filtros aplicados, calcular movimientos filtrados
        $movimientosFiltrados = $movimientos->total();
        
        return view('almacen.kardex.index', compact(
            'movimientos', 
            'totalArticulos',
            'totalMovimientos',
            'movimientosFiltrados',
            'search',
            'startDate',
            'endDate'
        ));
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
    
    // Obtener el movimiento específico del kardex
    $movimiento = Kardex::where('id', $id)
                       ->where('idArticulo', $idArticulo)
                       ->firstOrFail();
    
    // Obtener los detalles de compra relacionados
    $detalleCompra = DetalleCompra::where('idProducto', $idArticulo)
                                ->whereHas('compra', function($query) use ($movimiento) {
                                    $query->where('fechaEmision', $movimiento->fecha);
                                })
                                ->with(['compra', 'compra.proveedor'])
                                ->first();
    
    return view('almacen.kardex.producto.detalles', compact('articulo', 'movimiento', 'detalleCompra'));
}


public function kardexProductoPorCliente($articulo_id, $cliente_id)
{
    $articulo = Articulo::findOrFail($articulo_id);
    $cliente = DB::table('clientegeneral')->where('idClienteGeneral', $cliente_id)->first();

    $movimientos = Kardex::where('idArticulo', $articulo_id)
                        ->where('cliente_general_id', $cliente_id)
                        ->orderBy('fecha', 'desc')
                        ->paginate(10);

    return view('almacen.inventario.porcliente', compact('articulo', 'cliente', 'movimientos', 'cliente_id'));
}

// Nuevo método para ver el detalle de movimientos de un mes completo
public function detalleMovimientosKardex($kardex_id)
{
    // Obtener el registro del kardex
    $kardex = Kardex::findOrFail($kardex_id);
    
    // Obtener el artículo y cliente
    $articulo = Articulo::findOrFail($kardex->idArticulo);
    $cliente = DB::table('clientegeneral')->where('idClienteGeneral', $kardex->cliente_general_id)->first();
    
    // Obtener el año y mes del registro del kardex
    $fechaKardex = \Carbon\Carbon::parse($kardex->fecha);
    $year = $fechaKardex->year;
    $month = $fechaKardex->month;
    
    // Buscar los movimientos de TODO EL MES del kardex
    $movimientos = DB::table('inventario_ingresos_clientes')
                    ->where('articulo_id', $kardex->idArticulo)
                    ->where('cliente_general_id', $kardex->cliente_general_id)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->orderBy('created_at', 'asc')
                    ->get();

    $mostrarPrecios = ($kardex->cliente_general_id == 8);

    return view('almacen.inventario.detalle-movimientos', compact(
        'articulo', 
        'cliente', 
        'movimientos', 
        'kardex',
        'mostrarPrecios',
        'year',
        'month'
    ));
}




    
    
}