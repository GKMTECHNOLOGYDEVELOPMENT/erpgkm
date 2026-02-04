<?php

namespace App\Http\Controllers\almacen\kardex;
use App\Http\Controllers\Controller;
use App\Exports\KardexExport;
use Maatwebsite\Excel\Facades\Excel;
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
    
    // Primero: obtener el CAS más reciente por artículo y fecha
    $subqueryCas = DB::table('kardex')
        ->select(
            'idArticulo',
            DB::raw('DATE(fecha) as fecha_kardex'),
            DB::raw('MAX(cas) as cas') // Tomar el CAS más reciente
        )
        ->groupBy('idArticulo', DB::raw('DATE(fecha)'));
    
    // Consulta base para obtener movimientos de inventario_ingresos_clientes
    $query = DB::table('inventario_ingresos_clientes as iic')
        ->join('articulos', 'iic.articulo_id', '=', 'articulos.idArticulos')
        ->leftJoinSub($subqueryCas, 'kardex_cas', function($join) {
            $join->on('iic.articulo_id', '=', 'kardex_cas.idArticulo')
                 ->on(DB::raw('DATE(iic.created_at)'), '=', 'kardex_cas.fecha_kardex');
        })
        ->leftJoin('tipoarticulos', 'articulos.idTipoArticulo', '=', 'tipoarticulos.idTipoArticulo')
        ->leftJoin('modelo', 'articulos.idModelo', '=', 'modelo.idModelo')
        ->leftJoin('marca', 'modelo.idMarca', '=', 'marca.idMarca')
        ->leftJoin('categoria', 'modelo.idCategoria', '=', 'categoria.idCategoria')
        ->leftJoin('subcategorias', 'articulos.idsubcategoria', '=', 'subcategorias.id')
        ->leftJoin('clientegeneral', 'iic.cliente_general_id', '=', 'clientegeneral.idClienteGeneral')
        ->select(
            'iic.id', // Incluir el ID principal para evitar duplicados
            'iic.articulo_id',
            'iic.cantidad',
            'iic.created_at as fecha',
            // Determinar si es entrada o salida
            DB::raw("CASE 
                WHEN iic.cantidad > 0 THEN 'ENTRADA'
                WHEN iic.cantidad < 0 THEN 'SALIDA'
                ELSE 'OTRO'
            END as tipo_movimiento"),
            DB::raw("ABS(iic.cantidad) as unidades"),
            'tipoarticulos.nombre as tipo_articulo_nombre',
            'modelo.nombre as modelo_nombre',
            'marca.nombre as marca_nombre',
            'categoria.nombre as categoria_nombre',
            'subcategorias.nombre as subcategoria_nombre',
            // Nombre del producto
            DB::raw("CASE 
                WHEN articulos.idTipoArticulo = 2 THEN articulos.codigo_repuesto 
                ELSE articulos.nombre 
            END as nombre_producto"),
            'articulos.codigo_barras',
            'articulos.idTipoArticulo',
            'articulos.codigo_repuesto',
            'iic.numero_orden',
            'iic.codigo_solicitud',
            'iic.tipo_ingreso',
            'clientegeneral.descripcion as cliente_nombre',
            // Traer el CAS del subquery
            'kardex_cas.cas',
            // Determinar región basado en tipo_ingreso
            DB::raw("CASE 
                WHEN iic.tipo_ingreso = 'salida_provincia' THEN 'PROVINCIA'
                WHEN iic.tipo_ingreso IN ('compra', 'ajuste', 'salida', 'entrada_proveedor') THEN 'LIMA'
                ELSE 'SIN REGISTRO'
            END as region")
        )
        ->distinct(); // Evitar duplicados
    
    // Aplicar filtros si existen
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('articulos.nombre', 'like', "%{$search}%")
              ->orWhere('articulos.codigo_barras', 'like', "%{$search}%")
              ->orWhere('articulos.codigo_repuesto', 'like', "%{$search}%")
              ->orWhere('tipoarticulos.nombre', 'like', "%{$search}%")
              ->orWhere('modelo.nombre', 'like', "%{$search}%")
              ->orWhere('marca.nombre', 'like', "%{$search}%")
              ->orWhere('categoria.nombre', 'like', "%{$search}%")
              ->orWhere('subcategorias.nombre', 'like', "%{$search}%")
              ->orWhere('iic.numero_orden', 'like', "%{$search}%")
              ->orWhere('iic.codigo_solicitud', 'like', "%{$search}%")
              ->orWhere('clientegeneral.descripcion', 'like', "%{$search}%")
              ->orWhere('kardex_cas.cas', 'like', "%{$search}%")
              ->orWhere(DB::raw("CASE 
                    WHEN iic.tipo_ingreso = 'salida_provincia' THEN 'PROVINCIA'
                    WHEN iic.tipo_ingreso IN ('compra', 'ajuste', 'salida', 'entrada_proveedor') THEN 'LIMA'
                    ELSE 'SIN REGISTRO'
                END"), 'like', "%{$search}%");
        });
    }
    
    if ($startDate) {
        $query->whereDate('iic.created_at', '>=', $startDate);
    }
    
    if ($endDate) {
        $query->whereDate('iic.created_at', '<=', $endDate);
    }
    
    // Crear una copia de la consulta para las estadísticas
    $queryStats = clone $query;
    
    // Obtener los datos paginados
    $movimientos = $query->orderBy('iic.created_at', 'desc')
                        ->paginate(20);
    
    // Calcular inventario actual por artículo (para mostrar en cada fila)
    $inventarioPorArticulo = [];
    $inventarioAcumulado = [];
    
    // Primero obtenemos todos los movimientos ordenados por fecha (sin duplicados)
    $todosMovimientos = DB::table('inventario_ingresos_clientes')
        ->select('articulo_id', 'cantidad', 'created_at')
        ->orderBy('created_at')
        ->get();
    
    // Calculamos inventario acumulado
    foreach ($todosMovimientos as $mov) {
        if (!isset($inventarioAcumulado[$mov->articulo_id])) {
            $inventarioAcumulado[$mov->articulo_id] = 0;
        }
        $inventarioAcumulado[$mov->articulo_id] += $mov->cantidad;
        $inventarioPorArticulo[$mov->articulo_id] = $inventarioAcumulado[$mov->articulo_id];
    }
    
    // Agregar inventario actual a cada movimiento de la página actual
    foreach ($movimientos as $movimiento) {
        $movimiento->inventario_actual = $inventarioPorArticulo[$movimiento->articulo_id] ?? 0;
        $movimiento->unidades_entrada = $movimiento->cantidad > 0 ? $movimiento->cantidad : 0;
        $movimiento->unidades_salida = $movimiento->cantidad < 0 ? abs($movimiento->cantidad) : 0;
        
        // Calcular inventario inicial (inventario actual - movimiento actual)
        $movimiento->inventario_inicial = $movimiento->inventario_actual - $movimiento->cantidad;
    }
    
    // Obtener estadísticas FILTRADAS
    $estadisticasFiltradas = $queryStats
        ->select(
            DB::raw('COUNT(DISTINCT iic.id) as total_movimientos'),
            DB::raw('SUM(CASE WHEN iic.cantidad > 0 THEN iic.cantidad ELSE 0 END) as total_entradas'),
            DB::raw('SUM(CASE WHEN iic.cantidad < 0 THEN ABS(iic.cantidad) ELSE 0 END) as total_salidas')
        )
        ->first();
    
    // Calcular inventario actual total filtrado
    $inventarioTotalFiltrado = DB::table('inventario_ingresos_clientes as iic')
        ->join('articulos', 'iic.articulo_id', '=', 'articulos.idArticulos')
        ->select(DB::raw('SUM(iic.cantidad) as inventario_total'))
        ->when($search, function($q) use ($search) {
            return $q->where(function($query) use ($search) {
                $query->where('articulos.nombre', 'like', "%{$search}%")
                      ->orWhere('articulos.codigo_barras', 'like', "%{$search}%")
                      ->orWhere('articulos.codigo_repuesto', 'like', "%{$search}%");
            });
        })
        ->when($startDate, function($q) use ($startDate) {
            return $q->whereDate('iic.created_at', '>=', $startDate);
        })
        ->when($endDate, function($q) use ($endDate) {
            return $q->whereDate('iic.created_at', '<=', $endDate);
        })
        ->first();
    
    // Estadísticas generales (sin filtros)
    $totalMovimientos = DB::table('inventario_ingresos_clientes')->count();
    $totalEntradas = DB::table('inventario_ingresos_clientes')
        ->where('cantidad', '>', 0)
        ->sum('cantidad');
    $totalSalidas = DB::table('inventario_ingresos_clientes')
        ->where('cantidad', '<', 0)
        ->sum(DB::raw('ABS(cantidad)'));
    
    // Inventario total general
    $inventarioTotalGeneral = DB::table('inventario_ingresos_clientes')
        ->sum('cantidad');
    
    return view('almacen.kardex.index', compact(
        'movimientos', 
        'totalMovimientos',
        'totalEntradas',
        'totalSalidas',
        'inventarioTotalGeneral',
        'estadisticasFiltradas',
        'inventarioTotalFiltrado',
        'search',
        'startDate',
        'endDate'
    ));
}

public function kardexxproducto($id)
{
    $articulo = Articulo::findOrFail($id);
    
    // Obtener todos los movimientos del kardex para este artÃ­culo ordenados por fecha descendente
    $movimientos = Kardex::where('idArticulo', $id)
                        ->orderBy('fecha', 'desc')
                        ->paginate(10);
    
    return view('almacen.kardex.producto.index', compact('articulo', 'movimientos'));
}




public function detalles($idArticulo, $id)
{
    // Obtener el artÃ­culo
    $articulo = Articulo::findOrFail($idArticulo);
    
    // Obtener el movimiento especÃ­fico del kardex
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

// Nuevo mÃ©todo para ver el detalle de movimientos de un mes completo
public function detalleMovimientosKardex($kardex_id)
{
    // Obtener el registro del kardex
    $kardex = Kardex::findOrFail($kardex_id);
    
    // Obtener el artÃ­culo y cliente
    $articulo = Articulo::findOrFail($kardex->idArticulo);
    $cliente = DB::table('clientegeneral')->where('idClienteGeneral', $kardex->cliente_general_id)->first();
    
    // Obtener el aÃ±o y mes del registro del kardex
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



// Agrega este método después del método index()
public function exportExcel(Request $request)
{
    $search = $request->input('search');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    
    // Nombre del archivo con fecha y filtros aplicados
    $fileName = 'kardex_general';
    
    if ($search) {
        $fileName .= '_busqueda_' . substr($search, 0, 10);
    }
    
    if ($startDate) {
        $fileName .= '_desde_' . $startDate;
    }
    
    if ($endDate) {
        $fileName .= '_hasta_' . $endDate;
    }
    
    $fileName .= '_' . now()->format('Y_m_d_H_i') . '.xlsx';
    
    return Excel::download(new KardexExport($search, $startDate, $endDate), $fileName);
}



    
    
}