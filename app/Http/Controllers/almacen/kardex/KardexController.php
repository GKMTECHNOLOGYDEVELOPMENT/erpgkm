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

        // Primero: obtener SOLO los números de orden para SALIDAS (cantidad negativa)
        $subqueryNumerosOrden = DB::table('inventario_ingresos_clientes')
            ->select(
                'articulo_id',
                DB::raw('DATE(created_at) as fecha_orden'),
                DB::raw("GROUP_CONCAT(DISTINCT numero_orden ORDER BY created_at DESC SEPARATOR ', ') as numeros_orden")
            )
            ->where('cantidad', '<', 0) // SOLO para salidas (cantidad negativa)
            ->groupBy('articulo_id', DB::raw('DATE(created_at)'));

        // Consulta base para obtener movimientos de kardex con toda la información de relaciones
        $query = Kardex::with('articulo')
            ->join('articulos', 'kardex.idArticulo', '=', 'articulos.idArticulos')
            ->leftJoin('tipoarticulos', 'articulos.idTipoArticulo', '=', 'tipoarticulos.idTipoArticulo')
            ->leftJoin('modelo', 'articulos.idModelo', '=', 'modelo.idModelo')
            ->leftJoin('marca', 'modelo.idMarca', '=', 'marca.idMarca')
            ->leftJoin('categoria', 'modelo.idCategoria', '=', 'categoria.idCategoria')
            ->leftJoin('subcategorias', 'articulos.idsubcategoria', '=', 'subcategorias.id')
            ->leftJoinSub($subqueryNumerosOrden, 'ordenes', function ($join) {
                $join->on('kardex.idArticulo', '=', 'ordenes.articulo_id')
                    ->on(DB::raw('DATE(kardex.fecha)'), '=', 'ordenes.fecha_orden');
            })
            ->select(
                'kardex.*',
                'tipoarticulos.nombre as tipo_articulo_nombre',
                'modelo.nombre as modelo_nombre',
                'marca.nombre as marca_nombre',
                'categoria.nombre as categoria_nombre',
                'subcategorias.nombre as subcategoria_nombre',
                // Usar código de repuesto si idTipoArticulo = 2, sino usar nombre
                DB::raw("CASE 
                    WHEN articulos.idTipoArticulo = 2 THEN articulos.codigo_repuesto 
                    ELSE articulos.nombre 
                END as nombre_producto"),
                'articulos.codigo_barras',
                'articulos.idTipoArticulo',
                'articulos.codigo_repuesto',
                'ordenes.numeros_orden',
                // Determinar región basado en CAS
                DB::raw("CASE 
    WHEN kardex.cas IN ('CAS GKM', 'INGRESO POR MARCA ASOCIADA TCL') THEN 'LIMA'
    WHEN kardex.cas IS NOT NULL AND kardex.cas != '' THEN 'PROVINCIA'
    ELSE 'SIN REGISTRO'
END as region")

            );

        // Aplicar filtros si existen
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('articulos.nombre', 'like', "%{$search}%")
                    ->orWhere('articulos.codigo_barras', 'like', "%{$search}%")
                    ->orWhere('articulos.codigo_repuesto', 'like', "%{$search}%")
                    ->orWhere('tipoarticulos.nombre', 'like', "%{$search}%")
                    ->orWhere('modelo.nombre', 'like', "%{$search}%")
                    ->orWhere('marca.nombre', 'like', "%{$search}%")
                    ->orWhere('categoria.nombre', 'like', "%{$search}%")
                    ->orWhere('subcategorias.nombre', 'like', "%{$search}%")
                    ->orWhere('kardex.cas', 'like', "%{$search}%")
                    // También buscar por región (LIMA o PROVINCIA)
                    ->orWhere(DB::raw("CASE 
    WHEN kardex.cas IN ('CAS GKM', 'INGRESO POR MARCA ASOCIADA TCL') THEN 'LIMA'
    WHEN kardex.cas IS NOT NULL AND kardex.cas != '' THEN 'PROVINCIA'
    ELSE 'SIN REGISTRO'
END"), 'like', "%{$search}%")

                    // Búsqueda por número de orden SOLO en salidas
                    ->orWhereExists(function ($subquery) use ($search) {
                        $subquery->select(DB::raw(1))
                            ->from('inventario_ingresos_clientes')
                            ->whereColumn('inventario_ingresos_clientes.articulo_id', 'kardex.idArticulo')
                            ->whereDate('inventario_ingresos_clientes.created_at', DB::raw('DATE(kardex.fecha)'))
                            ->where('inventario_ingresos_clientes.cantidad', '<', 0) // Solo salidas
                            ->where('inventario_ingresos_clientes.numero_orden', 'like', "%{$search}%");
                    });
            });
        }

        if ($startDate) {
            $query->whereDate('kardex.fecha', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('kardex.fecha', '<=', $endDate);
        }

        // Crear una copia de la consulta para las estadísticas (sin paginación)
        $queryStats = clone $query;

        // Obtener los datos paginados para la vista
        $movimientos = $query->orderBy('kardex.fecha', 'desc')
            ->paginate(20);

        // Obtener estadísticas FILTRADAS
        $movimientosFiltrados = $movimientos->total();

        // Obtener estadísticas generales
        $totalArticulos = Articulo::count();
        $totalMovimientos = Kardex::count();

        // Calcular estadísticas específicas de los resultados filtrados
        if ($movimientosFiltrados > 0) {
            // Obtener estadísticas de los resultados filtrados
            $estadisticasFiltradas = $queryStats
                ->select(
                    DB::raw('COUNT(*) as total_movimientos'),
                    DB::raw('SUM(kardex.unidades_entrada) as total_entradas'),
                    DB::raw('SUM(kardex.unidades_salida) as total_salidas'),
                    DB::raw('SUM(kardex.inventario_actual) as total_inventario_actual')
                )
                ->first();

            $totalMovimientosFiltrados = $estadisticasFiltradas->total_movimientos ?? 0;
            $totalEntradasFiltradas = $estadisticasFiltradas->total_entradas ?? 0;
            $totalSalidasFiltradas = $estadisticasFiltradas->total_salidas ?? 0;
            $totalInventarioActualFiltrado = $estadisticasFiltradas->total_inventario_actual ?? 0;
        } else {
            $totalMovimientosFiltrados = 0;
            $totalEntradasFiltradas = 0;
            $totalSalidasFiltradas = 0;
            $totalInventarioActualFiltrado = 0;
        }

        return view('almacen.kardex.index', compact(
            'movimientos',
            'totalArticulos',
            'totalMovimientos',
            'movimientosFiltrados',
            'totalMovimientosFiltrados',
            'totalEntradasFiltradas',
            'totalSalidasFiltradas',
            'totalInventarioActualFiltrado',
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
            ->whereHas('compra', function ($query) use ($movimiento) {
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
