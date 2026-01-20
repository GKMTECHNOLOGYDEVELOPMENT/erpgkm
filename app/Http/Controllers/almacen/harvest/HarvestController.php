<?php

namespace App\Http\Controllers\Almacen\Harvest;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\HarvestRetiro;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HarvestExport;
use App\Exports\HarvestDetailExport;

class HarvestController extends Controller
{
    /**
     * Muestra el listado de repuestos de harvest agrupados
     */
 public function index(Request $request)
{
    // Consulta principal para agrupar repuestos de harvest
    $query = HarvestRetiro::select(
            'harvest_retiros.id_articulo',
            'harvest_retiros.codigo_repuesto',
            DB::raw('SUM(harvest_retiros.cantidad_retirada) as total_retirado')
        )
        ->join('articulos', 'harvest_retiros.id_articulo', '=', 'articulos.idArticulos')
        ->where('harvest_retiros.estado', 'Activo')
        ->where('articulos.idTipoArticulo', 2) // Solo repuestos
        ->groupBy('harvest_retiros.id_articulo', 'harvest_retiros.codigo_repuesto')
        ->with(['articulo' => function($query) {
            $query->select('idArticulos', 'nombre', 'idsubcategoria', 'codigo_repuesto')
                ->with([
                    'subcategoria' => function($q) {
                        $q->select('id', 'nombre');
                    },
                    'modelos' => function($q) {
                        $q->select('modelo.idModelo', 'modelo.nombre');
                    },
                    'modeloPrincipal' => function($q) {
                        $q->select('idModelo', 'nombre');
                    }
                ]);
        }]);

    // Filtro por búsqueda - Ahora incluye búsqueda por modelos
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('harvest_retiros.codigo_repuesto', 'like', "%{$search}%")
              ->orWhereHas('articulo', function($articuloQuery) use ($search) {
                  $articuloQuery->where('nombre', 'like', "%{$search}%")
                      ->orWhereHas('modelos', function($modeloQuery) use ($search) {
                          $modeloQuery->where('nombre', 'like', "%{$search}%");
                      })
                      ->orWhereHas('modeloPrincipal', function($modeloQuery) use ($search) {
                          $modeloQuery->where('nombre', 'like', "%{$search}%");
                      });
              })
              ->orWhereHas('articulo.subcategoria', function($subcatQuery) use ($search) {
                  $subcatQuery->where('nombre', 'like', "%{$search}%");
              });
        });
    }

    // Filtro por subcategoría
    if ($request->has('subcategoria') && $request->subcategoria != 'todas') {
        $query->whereHas('articulo', function($q) use ($request) {
            $q->where('idsubcategoria', $request->subcategoria);
        });
    }

    // Ordenar por total retirado (descendente)
    $repuestos = $query->orderBy('total_retirado', 'desc')
        ->paginate(15);

    // Obtener todas las subcategorías para el filtro
    $subcategorias = Subcategoria::orderBy('nombre')->get();

    // Estadísticas generales
    $totalRepuestos = HarvestRetiro::where('estado', 'Activo')
        ->whereHas('articulo', function($q) {
            $q->where('idTipoArticulo', 2);
        })
        ->distinct('id_articulo')
        ->count('id_articulo');

    $totalUnidades = HarvestRetiro::where('estado', 'Activo')
        ->whereHas('articulo', function($q) {
            $q->where('idTipoArticulo', 2);
        })
        ->sum('cantidad_retirada');

    // Si es petición AJAX, retornar JSON
    if ($request->ajax()) {
        return response()->json([
            'html' => view('almacen.harvest.partials.table', compact('repuestos'))->render(),
            'totalRepuestos' => $totalRepuestos,
            'totalUnidades' => $totalUnidades
        ]);
    }

    // Mantener filtros en la paginación para la carga inicial
    if ($request->has('search')) {
        $repuestos->appends(['search' => $request->search]);
    }
    if ($request->has('subcategoria')) {
        $repuestos->appends(['subcategoria' => $request->subcategoria]);
    }

    return view('almacen.harvest.index', compact(
        'repuestos',
        'subcategorias',
        'totalRepuestos',
        'totalUnidades'
    ));
}

    /**
     * Muestra el detalle de un repuesto específico (para modal)
     */
    public function show($idArticulo, Request $request)
    {
        // Obtener el artículo con sus relaciones
        $articulo = Articulo::where('idArticulos', $idArticulo)
            ->where('idTipoArticulo', 2)
            ->with(['subcategoria', 'modelos'])
            ->firstOrFail();

        // Obtener todos los retiros de este artículo
        $retiros = HarvestRetiro::where('id_articulo', $idArticulo)
            ->where('estado', 'Activo')
            ->with(['custodia.ticket.cliente', 'responsable'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calcular total retirado
        $totalRetirado = HarvestRetiro::where('id_articulo', $idArticulo)
            ->where('estado', 'Activo')
            ->sum('cantidad_retirada');

        // Obtener modelos compatibles
        $modelos = $articulo->modelos ?? collect();

        // Si es petición AJAX (para modal), retornar vista parcial
        if ($request->ajax()) {
            return view('almacen.harvest.show', compact(
                'articulo',
                'retiros',
                'totalRetirado',
                'modelos'
            ))->render();
        }

        // Si no es AJAX, redirigir o retornar error
        return back()->with('error', 'Acceso no válido');
    }

    /**
     * Exportar listado de repuestos a Excel
     */
    public function export(Request $request)
    {
        // Aplicar los mismos filtros del index
        $query = HarvestRetiro::select(
                'harvest_retiros.id_articulo',
                'harvest_retiros.codigo_repuesto',
                DB::raw('SUM(harvest_retiros.cantidad_retirada) as total_retirado')
            )
            ->join('articulos', 'harvest_retiros.id_articulo', '=', 'articulos.idArticulos')
            ->where('harvest_retiros.estado', 'Activo')
            ->where('articulos.idTipoArticulo', 2)
            ->groupBy('harvest_retiros.id_articulo', 'harvest_retiros.codigo_repuesto')
            ->with(['articulo' => function($query) {
                $query->select('idArticulos', 'nombre', 'idsubcategoria')
                    ->with(['subcategoria' => function($q) {
                        $q->select('id', 'nombre');
                    }]);
            }]);

        // Aplicar filtros si existen
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('harvest_retiros.codigo_repuesto', 'like', "%{$search}%")
                  ->orWhereHas('articulo', function($articuloQuery) use ($search) {
                      $articuloQuery->where('nombre', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('subcategoria') && $request->subcategoria != 'todas') {
            $query->whereHas('articulo', function($q) use ($request) {
                $q->where('idsubcategoria', $request->subcategoria);
            });
        }

        $repuestos = $query->orderBy('total_retirado', 'desc')->get();

        // Crear array para exportación
        $exportData = [];
        
        foreach ($repuestos as $index => $repuesto) {
            $exportData[] = [
                'N°' => $index + 1,
                'Código Repuesto' => $repuesto->codigo_repuesto,
                'Nombre' => $repuesto->articulo->nombre ?? 'N/A',
                'Subcategoría' => $repuesto->articulo->subcategoria->nombre ?? 'Sin categoría',
                'Total Retirado' => $repuesto->total_retirado,
                'Unidad' => 'unidades'
            ];
        }

        // Agregar totales
        $exportData[] = [];
        $exportData[] = [
            'N°' => '',
            'Código Repuesto' => 'TOTALES:',
            'Nombre' => '',
            'Subcategoría' => '',
            'Total Retirado' => $repuestos->sum('total_retirado'),
            'Unidad' => 'unidades'
        ];

        // Exportar a Excel
        return Excel::download(new HarvestExport($exportData), 'harvest_repuestos_' . date('Ymd_His') . '.xlsx');
    }

    /**
     * Exportar detalle de un repuesto específico
     */
    public function exportDetail($idArticulo)
    {
        $articulo = Articulo::findOrFail($idArticulo);
        $retiros = HarvestRetiro::where('id_articulo', $idArticulo)
            ->where('estado', 'Activo')
            ->with(['custodia.ticket.cliente', 'responsable'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRetirado = $retiros->sum('cantidad_retirada');

        // Preparar datos para exportación
        $exportData = [];
        
        foreach ($retiros as $index => $retiro) {
            $exportData[] = [
                'N°' => $index + 1,
                'Fecha' => $retiro->created_at->format('d/m/Y H:i'),
                'Custodia' => $retiro->custodia->codigocustodias ?? 'N/A',
                'Cliente' => $retiro->custodia->cliente->nombre ?? 'N/A',
                'Cantidad' => $retiro->cantidad_retirada,
                'Responsable' => $retiro->responsable->Nombre ?? 'N/A',
                'Observaciones' => $retiro->observaciones
            ];
        }

        // Agregar total al final
        $exportData[] = [];
        $exportData[] = [
            'N°' => '',
            'Fecha' => 'TOTAL:',
            'Custodia' => '',
            'Cliente' => '',
            'Cantidad' => $totalRetirado,
            'Responsable' => '',
            'Observaciones' => ''
        ];

        $nombreArchivo = 'harvest_detalle_' . $articulo->codigo_repuesto . '_' . date('Ymd_His') . '.xlsx';
        
        return Excel::download(new HarvestDetailExport($exportData, $articulo), $nombreArchivo);
    }

    /**
     * Obtener estadísticas en tiempo real
     */
    public function estadisticas()
    {
        $topRepuestos = HarvestRetiro::select(
                'id_articulo',
                'codigo_repuesto',
                DB::raw('SUM(cantidad_retirada) as total')
            )
            ->where('estado', 'Activo')
            ->whereHas('articulo', function($q) {
                $q->where('idTipoArticulo', 2);
            })
            ->groupBy('id_articulo', 'codigo_repuesto')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        $retirosPorMes = HarvestRetiro::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'),
                DB::raw('SUM(cantidad_retirada) as total')
            )
            ->where('estado', 'Activo')
            ->where('created_at', '>=', now()->subMonths(6))
            ->whereHas('articulo', function($q) {
                $q->where('idTipoArticulo', 2);
            })
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        return response()->json([
            'topRepuestos' => $topRepuestos,
            'retirosPorMes' => $retirosPorMes
        ]);
    }
}