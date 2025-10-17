<?php

namespace App\Http\Controllers\almacen\ubicaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UbicacionesVistaController extends Controller
{
    public function vistaAlmacen()
    {
        // Obtener las sedes disponibles desde la tabla sucursal
        $sedes = DB::table('sucursal')
            ->select('nombre')
            ->where('estado', 1) // Asumiendo que 1 = activo
            ->orderBy('nombre')
            ->pluck('nombre');

        return view('almacen.ubicaciones.vista-almacen', compact('sedes'));
    }




    public function getDatosRacks(Request $request)
    {
        Log::debug('=== INICIO getDatosRacks ===', [
            'params' => $request->all()
        ]);

        $periodo = $request->input('periodo', 30);
        $sede = $request->input('sede', '');
        $buscar = $request->input('buscar', '');

        $fechaInicio = now()->subDays($periodo);

        // Query de ubicaciones base
        $queryUbicaciones = DB::table('rack_ubicaciones as ru')
            ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
            ->select(
                'ru.idRackUbicacion',
                'ru.codigo',
                'ru.codigo_unico',
                'ru.nivel as piso',
                'ru.posicion',
                'ru.capacidad_maxima',
                'ru.estado_ocupacion',
                'r.nombre as rack_nombre',
                'r.sede',
                'r.idRack'
            )
            ->where('r.estado', 'activo');

        if (!empty($sede)) {
            $queryUbicaciones->where('r.sede', $sede);
        }
        if (!empty($buscar)) {
            $queryUbicaciones->where('r.nombre', 'like', '%' . $buscar . '%');
        }

        $ubicaciones = $queryUbicaciones->get();

        // ✅ Obtener productos por ubicación y AGRUPAR categorías y tipos
        $ubicacionIds = $ubicaciones->pluck('idRackUbicacion')->toArray();

        // ✅ MODIFICADO: OBTENER CATEGORÍAS PARA REPUESTOS CON MÚLTIPLOS MODELOS
        $productosPorUbicacion = DB::table('rack_ubicacion_articulos as rua')
            ->leftJoin('articulos as a', 'rua.articulo_id', '=', 'a.idArticulos')
            ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')
            // Para repuestos: obtener categorías desde articulo_modelo
            ->leftJoin('articulo_modelo as am', function ($join) {
                $join->on('a.idArticulos', '=', 'am.articulo_id')
                    ->where('a.idTipoArticulo', '=', 2);
            })
            ->leftJoin('modelo as m_repuesto', 'am.modelo_id', '=', 'm_repuesto.idModelo')
            ->leftJoin('categoria as c_repuesto', 'm_repuesto.idCategoria', '=', 'c_repuesto.idCategoria')
            // Para productos normales: categoría directa
            ->leftJoin('modelo as m_normal', 'a.idModelo', '=', 'm_normal.idModelo')
            ->leftJoin('categoria as c_normal', 'm_normal.idCategoria', '=', 'c_normal.idCategoria')
            ->leftJoin('clientegeneral as cg', 'rua.cliente_general_id', '=', 'cg.idClienteGeneral')
            ->whereIn('rua.rack_ubicacion_id', $ubicacionIds)
            ->select(
                'rua.rack_ubicacion_id',
                'rua.cantidad',
                'rua.custodia_id',
                'rua.cliente_general_id',
                'a.nombre as producto',
                'ta.nombre as tipo_articulo',
                'ta.idTipoArticulo',
                // ✅ CATEGORÍA CORRECTA: Si es repuesto usa c_repuesto, sino c_normal
                DB::raw('CASE 
            WHEN a.idTipoArticulo = 2 THEN c_repuesto.nombre 
            ELSE c_normal.nombre 
        END as categoria'),
                'cg.descripcion as cliente_general_nombre'
            )
            ->get()
            ->groupBy('rack_ubicacion_id');

        // Calcular actividad (mantener igual)
        $movimientos = DB::table('rack_movimientos')
            ->select('rack_origen_id', 'rack_destino_id', 'cantidad', 'created_at')
            ->where('created_at', '>=', $fechaInicio)
            ->get();

        $actividadPorRack = [];
        foreach ($movimientos as $mov) {
            $racksInvolucrados = [];
            if ($mov->rack_origen_id) $racksInvolucrados[] = $mov->rack_origen_id;
            if ($mov->rack_destino_id) $racksInvolucrados[] = $mov->rack_destino_id;

            foreach ($racksInvolucrados as $rackId) {
                $actividadPorRack[$rackId] = ($actividadPorRack[$rackId] ?? 0) + 1;
            }
        }

        $maxActividad = !empty($actividadPorRack) ? max($actividadPorRack) : 1;
        $actividadNormalizada = [];
        foreach ($actividadPorRack as $rackId => $actividad) {
            $porcentaje = min(round(($actividad / $maxActividad) * 100), 100);
            $actividadNormalizada[$rackId] = $porcentaje;
        }

        // Preparar datos para el heatmap
        $data = [];
        $rackGroups = [];

        // Agrupar por rack
        foreach ($ubicaciones as $ub) {
            $rackGroups[$ub->rack_nombre][] = $ub;
        }

        // Generar datos para el heatmap
        $y = 0;
        foreach ($rackGroups as $rackNombre => $ubicacionesRack) {
            $x = 0;

            foreach ($ubicacionesRack as $ub) {
                // ✅ Obtener productos para esta ubicación
                $productosUbicacion = $productosPorUbicacion[$ub->idRackUbicacion] ?? collect();

                // Calcular cantidad total
                $cantidadTotal = $productosUbicacion->sum('cantidad');

                $porcentajeOcupacion = 0;
                if ($ub->capacidad_maxima > 0) {
                    $porcentajeOcupacion = round(($cantidadTotal / $ub->capacidad_maxima) * 100);
                }

                // ✅ Obtener información AGRUPADA de productos
                $producto = 'Vacío';
                $categorias = 'Sin categoría';
                $tiposArticulo = 'Sin tipo';
                $tieneCustodia = false;
                $infoCustodia = null;

                // Dentro del if ($productosUbicacion->isNotEmpty()), modifica esta sección:
                if ($productosUbicacion->isNotEmpty()) {
                    // ✅ VERIFICAR SI HAY CUSTODIAS
                    $tieneCustodia = $productosUbicacion->where('custodia_id', '!=', null)->isNotEmpty();

                    // Si hay un solo producto
                    if ($productosUbicacion->count() === 1) {
                        $primerProducto = $productosUbicacion->first();
                        $producto = $primerProducto->producto;
                        $categorias = $primerProducto->categoria ?? 'Sin categoría';
                        // ✅ SOLO CAMBIA EL TIPO ARTÍCULO SI HAY CUSTODIA
                        $tiposArticulo = $tieneCustodia ? 'CUSTODIA' : ($primerProducto->tipo_articulo ?? 'Sin tipo');
                    } else {
                        // ✅ Si hay múltiples productos, mostrar el primero y agregar "+X más"
                        $primerProducto = $productosUbicacion->first();
                        $producto = $primerProducto->producto . ' +' . ($productosUbicacion->count() - 1) . ' más';

                        // ✅ AGRUPAR categorías y tipos únicos separados por comas
                        $categoriasUnicas = $productosUbicacion->pluck('categoria')
                            ->filter()
                            ->unique()
                            ->values();
                        $categorias = $categoriasUnicas->isNotEmpty() ?
                            $categoriasUnicas->join(', ') : 'Sin categoría';

                        $tiposUnicos = $productosUbicacion->pluck('tipo_articulo')
                            ->filter()
                            ->unique()
                            ->values();

                        // ✅ SI HAY CUSTODIA, AGREGAR "CUSTODIA" A LOS TIPOS
                        if ($tieneCustodia) {
                            $tiposUnicos = $tiposUnicos->push('CUSTODIA')->unique();
                        }

                        $tiposArticulo = $tiposUnicos->isNotEmpty() ?
                            $tiposUnicos->join(', ') : 'Sin tipo';
                    }
                }

                // Usar actividad normalizada
                $valorActividad = $actividadNormalizada[$ub->idRack] ?? 0;

                // Extraer letra del rack
                $letraRack = substr($rackNombre, 0, 1);

                $data[] = [
                    'x' => $x,
                    'y' => $y,
                    'value' => $valorActividad,
                    'ocupacion' => $porcentajeOcupacion,
                    'rack' => $rackNombre,
                    'letra' => $letraRack,
                    'ubicacion' => $ub->codigo_unico ?? $ub->codigo,
                    'producto' => $producto,
                    'cantidad' => $cantidadTotal,
                    'capacidad' => $ub->capacidad_maxima,
                    'categoria' => $categorias,
                    'tipo_articulo' => $tiposArticulo, // ✅ Aquí ya vendrá "CUSTODIA" si corresponde
                    'piso' => $ub->piso,
                    'estado' => $ub->estado_ocupacion,
                    'sede' => $ub->sede,
                    'actividad_bruta' => $actividadPorRack[$ub->idRack] ?? 0,
                    'max_actividad' => $maxActividad,
                    'total_productos' => $productosUbicacion->count()
                ];

                $x++;
            }
            $y++;
        }

        // Stats finales
        $stats = [
            'totalRacks' => count($rackGroups),
            'activeRacks' => count(array_filter($actividadNormalizada, fn($v) => $v > 0)),
            'avgActivity' => !empty($actividadNormalizada) ? round(array_sum($actividadNormalizada) / count($actividadNormalizada)) : 0,
            'totalUbicaciones' => count($ubicaciones),
            'ocupadas' => $ubicaciones->where('estado_ocupacion', '!=', 'vacio')->count(),
        ];

        Log::debug('=== FINAL getDatosRacks ===', [
            'total_data_points' => count($data),
            'ubicaciones_con_custodia' => count(array_filter($data, fn($d) => str_contains($d['tipo_articulo'], 'CUSTODIA'))), // ← CORREGIDO
            'stats' => $stats
        ]);

        return response()->json([
            'success' => true,
            'data' => $data,
            'stats' => $stats
        ]);
    }

    public function getDatosActualizados($rack)
    {
        try {
            Log::debug('=== INICIO getDatosActualizados ===', ['rack' => $rack]);

            $sede = request('sede');

            // Si no viene sede por query, buscar en la base de datos
            if (!$sede) {
                $rackInfo = DB::table('racks')
                    ->where('nombre', $rack)
                    ->where('estado', 'activo')
                    ->first();

                if (!$rackInfo) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Rack no encontrado'
                    ], 404);
                }
                $sede = $rackInfo->sede;
            }

            // Reutilizar la lógica de tu getDatosRacks pero filtrado por un rack específico
            $rackEstructurado = $this->obtenerEstructuraRackIndividual($rack, $sede);

            Log::debug('=== FINAL getDatosActualizados ===', [
                'rack' => $rack,
                'sede' => $sede,
                'niveles' => count($rackEstructurado['niveles'] ?? []),
                'ubicaciones' => array_sum(array_map(fn($nivel) => count($nivel['ubicaciones']), $rackEstructurado['niveles'] ?? []))
            ]);

            return response()->json([
                'success' => true,
                'data' => $rackEstructurado
            ]);
        } catch (\Exception $e) {
            Log::error('Error en getDatosActualizados: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar datos del rack',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function obtenerEstructuraRackIndividual($rackNombre, $sede)
    {
        // Query base similar a tu getDatosRacks pero para un rack específico
        $ubicaciones = DB::table('rack_ubicaciones as ru')
            ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
            ->select(
                'ru.idRackUbicacion',
                'ru.codigo',
                'ru.codigo_unico',
                'ru.nivel',
                'ru.posicion',
                'ru.capacidad_maxima',
                'ru.estado_ocupacion',
                'r.nombre as rack_nombre',
                'r.sede',
                'r.idRack'
            )
            ->where('r.nombre', $rackNombre)
            ->where('r.sede', $sede)
            ->where('r.estado', 'activo')
            ->orderBy('ru.nivel', 'desc')
            ->orderBy('ru.posicion')
            ->get();

        if ($ubicaciones->isEmpty()) {
            throw new \Exception("No se encontraron ubicaciones para el rack {$rackNombre} en sede {$sede}");
        }

        $ubicacionIds = $ubicaciones->pluck('idRackUbicacion')->toArray();

        // ✅ Obtener productos con toda la información (ACTUALIZADO CON CLIENTE GENERAL)
        $productosCompletos = DB::table('rack_ubicacion_articulos as rua')
            ->leftJoin('articulos as a', 'rua.articulo_id', '=', 'a.idArticulos')
            ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')
            ->leftJoin('modelo as m', 'a.idModelo', '=', 'm.idModelo')
            ->leftJoin('categoria as c', 'm.idCategoria', '=', 'c.idCategoria')
            ->leftJoin('clientegeneral as cg', 'rua.cliente_general_id', '=', 'cg.idClienteGeneral') // ✅ NUEVO JOIN
            ->leftJoin('custodias as cust', 'rua.custodia_id', '=', 'cust.id')
            ->leftJoin('modelo as m_cust', 'cust.idModelo', '=', 'm_cust.idModelo')
            ->leftJoin('categoria as c_cust', 'm_cust.idCategoria', '=', 'c_cust.idCategoria')
            ->leftJoin('marca as mar_cust', 'cust.idMarca', '=', 'mar_cust.idMarca')
            ->leftJoin('tickets as t_cust', 'cust.numero_ticket', '=', 't_cust.numero_ticket')
            ->leftJoin('clientegeneral as cg_cust', 't_cust.idClienteGeneral', '=', 'cg_cust.idClienteGeneral')
            ->leftJoin('inventario_ingresos_clientes as iic', function ($join) {
                $join->on('a.idArticulos', '=', 'iic.articulo_id')
                    ->where('iic.tipo_ingreso', '=', 'ajuste');
            })
            ->leftJoin('clientegeneral as cg_ingreso', 'iic.cliente_general_id', '=', 'cg_ingreso.idClienteGeneral')
            ->whereIn('rua.rack_ubicacion_id', $ubicacionIds)
            ->select(
                'rua.rack_ubicacion_id',
                'rua.cantidad',
                'rua.custodia_id',
                'rua.cliente_general_id', // ✅ NUEVO
                'a.idArticulos',
                DB::raw('CASE 
                WHEN ta.idTipoArticulo = 2 AND a.codigo_repuesto IS NOT NULL AND a.codigo_repuesto != "" 
                THEN a.codigo_repuesto 
                ELSE a.nombre 
            END as producto'),
                'a.nombre as nombre_original',
                'a.codigo_repuesto',
                'a.stock_total',
                'ta.nombre as tipo_articulo',
                'ta.idTipoArticulo',
                'c.nombre as categoria',
                'cg.descripcion as cliente_general_nombre', // ✅ NUEVO
                'cust.codigocustodias',
                'cust.serie',
                'cust.idMarca',
                'cust.idModelo',
                'cust.numero_ticket',
                'c_cust.nombre as categoria_custodia',
                'mar_cust.nombre as marca_nombre',
                'm_cust.nombre as modelo_nombre',
                'cg_cust.idClienteGeneral as cliente_general_id_custodia',
                'cg_cust.descripcion as cliente_general_nombre_custodia',
                'cg_ingreso.idClienteGeneral as cliente_general_id_ingreso',
                'cg_ingreso.descripcion as cliente_general_nombre_ingreso'
            )
            ->get()
            ->groupBy('rack_ubicacion_id');

        // Estructurar el rack igual que en tu detalleRack
        $rackEstructurado = [
            'nombre' => $ubicaciones->first()->rack_nombre,
            'sede' => $ubicaciones->first()->sede,
            'id' => $ubicaciones->first()->idRack,
            'niveles' => []
        ];

        // Agrupar por niveles
        $niveles = $ubicaciones->groupBy('nivel');

        foreach ($niveles as $nivelNum => $ubicacionesNivel) {
            $ubicacionesEstructuradas = [];

            foreach ($ubicacionesNivel as $ubicacion) {
                $productosUbicacion = $productosCompletos[$ubicacion->idRackUbicacion] ?? collect();

                $cantidadTotal = $productosUbicacion->sum('cantidad');

                // Mapear productos (igual que en tu detalleRack)
                $productos = $productosUbicacion->map(function ($art) {
                    if ($art->custodia_id) {
                        return [
                            'id' => $art->idArticulos,
                            'nombre' => $art->serie ?: $art->codigocustodias ?: 'Custodia ' . $art->custodia_id,
                            'cantidad' => $art->cantidad,
                            'stock_total' => $art->stock_total,
                            'tipo_articulo' => 'CUSTODIA',
                            'categoria' => $art->categoria_custodia ?: 'Custodia',
                            'custodia_id' => $art->custodia_id,
                            'codigocustodias' => $art->codigocustodias,
                            'serie' => $art->serie,
                            'idMarca' => $art->idMarca,
                            'idModelo' => $art->idModelo,
                            'marca_nombre' => $art->marca_nombre,
                            'modelo_nombre' => $art->modelo_nombre,
                            'numero_ticket' => $art->numero_ticket,
                            'cliente_general_id' => $art->cliente_general_id_custodia,
                            'cliente_general_nombre' => $art->cliente_general_nombre_custodia ?: 'Sin cliente'
                        ];
                    }

                    $mostrandoCodigoRepuesto = ($art->idTipoArticulo == 2 && !empty($art->codigo_repuesto));

                    return [
                        'id' => $art->idArticulos,
                        'nombre' => $art->producto,
                        'nombre_original' => $art->nombre_original,
                        'codigo_repuesto' => $art->codigo_repuesto,
                        'cantidad' => $art->cantidad,
                        'stock_total' => $art->stock_total,
                        'tipo_articulo' => $art->tipo_articulo,
                        'idTipoArticulo' => $art->idTipoArticulo,
                        'categoria' => $art->categoria,
                        'custodia_id' => null,
                        'es_repuesto' => $art->idTipoArticulo == 2,
                        'mostrando_codigo_repuesto' => $mostrandoCodigoRepuesto,
                        'cliente_general_id' => $art->cliente_general_id,
                        'cliente_general_nombre' => $art->cliente_general_nombre ?: 'Sin cliente'
                    ];
                })->values();

                // Calcular estado
                $porcentajeOcupacion = 0;
                if ($ubicacion->capacidad_maxima > 0) {
                    $porcentajeOcupacion = ($cantidadTotal / $ubicacion->capacidad_maxima) * 100;
                }

                $estado = $ubicacion->estado_ocupacion;
                if ($estado == 'vacio' && $cantidadTotal > 0) {
                    if ($porcentajeOcupacion > 0 && $porcentajeOcupacion <= 24) $estado = 'bajo';
                    elseif ($porcentajeOcupacion <= 49) $estado = 'medio';
                    elseif ($porcentajeOcupacion <= 74) $estado = 'alto';
                    elseif ($porcentajeOcupacion > 74) $estado = 'muy_alto';
                } elseif ($cantidadTotal == 0) {
                    $estado = 'vacio';
                }

                // Acumular categorías y tipos
                $categoriasUnicas = $productos->pluck('categoria')->filter()->unique();
                $tiposUnicos = $productos->pluck('tipo_articulo')->filter()->unique();
                $clientesUnicos = $productos->pluck('cliente_general_nombre')
                    ->filter(fn($cliente) => $cliente && $cliente !== 'Sin cliente')
                    ->unique();

                $ubicacionesEstructuradas[] = [
                    'id' => $ubicacion->idRackUbicacion,
                    'codigo' => $ubicacion->codigo_unico ?? $ubicacion->codigo,
                    'productos' => $productos->toArray(),
                    'producto' => $productos->isNotEmpty() ? $productos->first()['nombre'] : null,
                    'cantidad' => $cantidadTotal,
                    'cantidad_total' => $cantidadTotal,
                    'stock_total' => $productos->isNotEmpty() ? $productos->first()['stock_total'] : null,
                    'tipo_articulo' => $productos->isNotEmpty() ? $productos->first()['tipo_articulo'] : null,
                    'categoria' => $productos->isNotEmpty() ? $productos->first()['categoria'] : null,
                    'capacidad' => $ubicacion->capacidad_maxima,
                    'estado' => $estado,
                    'nivel' => $ubicacion->nivel,
                    'fecha' => now()->toISOString(),
                    'categorias_acumuladas' => $categoriasUnicas->isNotEmpty() ? $categoriasUnicas->join(', ') : 'Sin categoría',
                    'tipos_acumulados' => $tiposUnicos->isNotEmpty() ? $tiposUnicos->join(', ') : 'Sin tipo',
                    'clientes_acumulados' => $clientesUnicos->isNotEmpty() ? $clientesUnicos->join(', ') : 'Sin cliente'
                ];
            }

            $rackEstructurado['niveles'][] = [
                'numero' => $nivelNum,
                'ubicaciones' => $ubicacionesEstructuradas
            ];
        }

        return $rackEstructurado;
    }

    public function detalleRack($rack)
    {
        // Obtener la sede desde el query parameter
        $sede = request('sede');

        if (!$sede) {
            $rackInfo = DB::table('racks')
                ->where('nombre', $rack)
                ->where('estado', 'activo')
                ->first();

            if (!$rackInfo) {
                return redirect()->route('almacen.vista')->with('error', 'Rack no encontrado');
            }

            $sede = $rackInfo->sede;
        }

        // ✅ CORREGIDO: Consulta sin los JOINS que causan duplicados
        // ✅ CORREGIDO: Consulta con JOIN CORRECTO para categoría de repuestos
        $rackData = DB::table('racks as r')
            ->join('rack_ubicaciones as ru', 'r.idRack', '=', 'ru.rack_id')
            ->leftJoin('rack_ubicacion_articulos as rua', 'ru.idRackUbicacion', '=', 'rua.rack_ubicacion_id')
            ->leftJoin('articulos as a', 'rua.articulo_id', '=', 'a.idArticulos')
            ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')
            // ✅ PARA REPUESTOS: categoría desde articulo_modelo
            ->leftJoin('articulo_modelo as am', function ($join) {
                $join->on('a.idArticulos', '=', 'am.articulo_id')
                    ->where('a.idTipoArticulo', '=', 2);
            })
            ->leftJoin('modelo as m_repuesto', 'am.modelo_id', '=', 'm_repuesto.idModelo')
            ->leftJoin('categoria as c_repuesto', 'm_repuesto.idCategoria', '=', 'c_repuesto.idCategoria')
            // ✅ PARA PRODUCTOS NORMALES: categoría directa
            ->leftJoin('modelo as m_normal', 'a.idModelo', '=', 'm_normal.idModelo')
            ->leftJoin('categoria as c_normal', 'm_normal.idCategoria', '=', 'c_normal.idCategoria')
            // ✅ JOIN para custodias con marcas, modelos Y TICKETS
            ->leftJoin('custodias as cust', 'rua.custodia_id', '=', 'cust.id')
            ->leftJoin('modelo as m_cust', 'cust.idModelo', '=', 'm_cust.idModelo')
            ->leftJoin('categoria as c_cust', 'm_cust.idCategoria', '=', 'c_cust.idCategoria')
            ->leftJoin('marca as mar_cust', 'cust.idMarca', '=', 'mar_cust.idMarca')
            // ✅ JOIN para tickets de custodias
            ->leftJoin('tickets as t_cust', 'cust.numero_ticket', '=', 't_cust.numero_ticket')
            ->leftJoin('clientegeneral as cg_cust', 't_cust.idClienteGeneral', '=', 'cg_cust.idClienteGeneral')
            // ✅ JOIN para cliente general de PRODUCTOS NORMALES desde rack_ubicacion_articulos
            ->leftJoin('clientegeneral as cg', 'rua.cliente_general_id', '=', 'cg.idClienteGeneral')
            ->select(
                'r.idRack',
                'r.nombre as rack_nombre',
                'r.sede',
                'ru.idRackUbicacion',
                'ru.codigo',
                'ru.codigo_unico',
                'ru.nivel',
                'ru.posicion',
                'ru.capacidad_maxima',
                'ru.estado_ocupacion',
                'ru.updated_at',
                'a.idArticulos',
                // ✅ CORREGIDO: Aplicar lógica de repuestos
                DB::raw('CASE 
            WHEN ta.idTipoArticulo = 2 AND a.codigo_repuesto IS NOT NULL AND a.codigo_repuesto != "" 
            THEN a.codigo_repuesto 
            ELSE a.nombre 
        END as producto'),
                'a.nombre as nombre_original',
                'a.codigo_repuesto',
                'a.stock_total',
                'ta.nombre as tipo_articulo',
                'ta.idTipoArticulo',
                // ✅ CATEGORÍA CORRECTA: Si es repuesto usa c_repuesto, sino c_normal
                DB::raw('CASE 
            WHEN a.idTipoArticulo = 2 THEN c_repuesto.nombre 
            ELSE c_normal.nombre 
        END as categoria'),
                'rua.cantidad',
                'rua.custodia_id',
                'rua.cliente_general_id',
                'cg.descripcion as cliente_general_nombre',
                // ✅ Campos de custodia
                'cust.codigocustodias',
                'cust.serie',
                'cust.idMarca',
                'cust.idModelo',
                'cust.numero_ticket',
                'c_cust.nombre as categoria_custodia',
                'mar_cust.nombre as marca_nombre',
                'm_cust.nombre as modelo_nombre',
                // ✅ Campos de cliente general para CUSTODIAS
                'cg_cust.idClienteGeneral as cliente_general_id_custodia',
                'cg_cust.descripcion as cliente_general_nombre_custodia'
            )
            ->where('r.nombre', $rack)
            ->where('r.sede', $sede)
            ->where('r.estado', 'activo')
            ->orderBy('ru.nivel', 'desc')
            ->orderBy('ru.posicion')
            ->get();

        // Si no existe el rack, redirigir
        if ($rackData->isEmpty()) {
            return redirect()->route('almacen.vista')->with('error', "Rack '{$rack}' no encontrado en la sede '{$sede}'");
        }

        $rackId = $rackData->first()->idRack;
        $ubicacionesIds = $rackData->pluck('idRackUbicacion')->unique();

        // Obtener historial de movimientos (mantener igual)
        $historialPorUbicacion = [];
        if ($ubicacionesIds->isNotEmpty()) {
            $movimientos = DB::table('rack_movimientos')
                ->where(function ($query) use ($ubicacionesIds) {
                    $query->whereIn('ubicacion_origen_id', $ubicacionesIds)
                        ->orWhereIn('ubicacion_destino_id', $ubicacionesIds);
                })
                ->orWhere('rack_origen_id', $rackId)
                ->orWhere('rack_destino_id', $rackId)
                ->select(
                    'idMovimiento',
                    'articulo_id',
                    'ubicacion_origen_id',
                    'ubicacion_destino_id',
                    'rack_origen_id',
                    'rack_destino_id',
                    'cantidad',
                    'tipo_movimiento',
                    'observaciones',
                    'created_at',
                    'codigo_ubicacion_origen',
                    'codigo_ubicacion_destino',
                    'nombre_rack_origen',
                    'nombre_rack_destino'
                )
                ->orderBy('created_at', 'desc')
                ->get();

            // Procesar movimientos para cada ubicación (mantener igual)
            foreach ($movimientos as $mov) {
                if ($mov->ubicacion_origen_id && in_array($mov->ubicacion_origen_id, $ubicacionesIds->toArray())) {
                    $historialPorUbicacion[$mov->ubicacion_origen_id][] = [
                        'fecha' => $mov->created_at,
                        'producto' => 'Producto Movido',
                        'cantidad' => $mov->cantidad,
                        'tipo' => $mov->tipo_movimiento,
                        'desde' => $mov->codigo_ubicacion_origen,
                        'hacia' => $mov->codigo_ubicacion_destino,
                        'rack_origen' => $mov->nombre_rack_origen,
                        'rack_destino' => $mov->nombre_rack_destino,
                        'observaciones' => $mov->observaciones
                    ];
                }

                if ($mov->ubicacion_destino_id && in_array($mov->ubicacion_destino_id, $ubicacionesIds->toArray())) {
                    $historialPorUbicacion[$mov->ubicacion_destino_id][] = [
                        'fecha' => $mov->created_at,
                        'producto' => 'Producto Movido',
                        'cantidad' => $mov->cantidad,
                        'tipo' => $mov->tipo_movimiento,
                        'desde' => $mov->codigo_ubicacion_origen,
                        'hacia' => $mov->codigo_ubicacion_destino,
                        'rack_origen' => $mov->nombre_rack_origen,
                        'rack_destino' => $mov->nombre_rack_destino,
                        'observaciones' => $mov->observaciones
                    ];
                }

                if ((!$mov->ubicacion_origen_id && !$mov->ubicacion_destino_id) &&
                    ($mov->rack_origen_id == $rackId || $mov->rack_destino_id == $rackId)
                ) {
                    foreach ($ubicacionesIds as $ubicacionId) {
                        $historialPorUbicacion[$ubicacionId][] = [
                            'fecha' => $mov->created_at,
                            'producto' => 'Movimiento de Rack',
                            'cantidad' => $mov->cantidad,
                            'tipo' => $mov->tipo_movimiento,
                            'desde' => $mov->nombre_rack_origen,
                            'hacia' => $mov->nombre_rack_destino,
                            'rack_origen' => $mov->nombre_rack_origen,
                            'rack_destino' => $mov->nombre_rack_destino,
                            'observaciones' => $mov->observaciones
                        ];
                    }
                }
            }
        }

        // Estructurar datos para la vista
        $rackEstructurado = [
            'nombre' => $rackData->first()->rack_nombre,
            'sede' => $rackData->first()->sede,
            'niveles' => []
        ];

        // Agrupar por niveles y ubicaciones
        $niveles = $rackData->groupBy('nivel');

        foreach ($niveles as $nivelNum => $ubicacionesNivel) {
            $ubicacionesEstructuradas = [];

            // Agrupar por ubicación
            $ubicacionesAgrupadas = $ubicacionesNivel->groupBy('idRackUbicacion');

            foreach ($ubicacionesAgrupadas as $ubicacionId => $articulos) {
                $primerArticulo = $articulos->first();

                // ✅ NUEVO: Agrupar artículos por cliente_general_id para evitar duplicados
                $articulosAgrupados = $articulos->groupBy('cliente_general_id');

                // ... dentro del foreach de $ubicacionesAgrupadas

                $productosAgrupados = collect();

                // ✅ NUEVO: VERIFICAR SI HAY ARTÍCULOS VÁLIDOS ANTES DE PROCESAR
                $articulosValidos = $articulos->filter(function ($art) {
                    return $art->idArticulos !== null || $art->custodia_id !== null;
                });

                if ($articulosValidos->isNotEmpty()) {
                    foreach ($articulosAgrupados as $clienteGeneralId => $artsDelMismoCliente) {
                        $primerArtDelCliente = $artsDelMismoCliente->first();
                        $cantidadPorCliente = $artsDelMismoCliente->sum('cantidad');

                        // ✅ VERIFICACIÓN ADICIONAL: Solo procesar si tiene artículo o custodia
                        if (!$primerArtDelCliente->idArticulos && !$primerArtDelCliente->custodia_id) {
                            continue; // Saltar este "producto fantasma"
                        }

                        // ✅ SI ES CUSTODIA
                        if ($primerArtDelCliente->custodia_id) {
                            $productosAgrupados->push([
                                'id' => $primerArtDelCliente->idArticulos,
                                'nombre' => $primerArtDelCliente->serie ?: $primerArtDelCliente->codigocustodias ?: 'Custodia ' . $primerArtDelCliente->custodia_id,
                                'cantidad' => $cantidadPorCliente,
                                'stock_total' => $primerArtDelCliente->stock_total,
                                'tipo_articulo' => 'CUSTODIA',
                                'categoria' => $primerArtDelCliente->categoria_custodia ?: 'Custodia',
                                'custodia_id' => $primerArtDelCliente->custodia_id,
                                'codigocustodias' => $primerArtDelCliente->codigocustodias,
                                'serie' => $primerArtDelCliente->serie,
                                'idMarca' => $primerArtDelCliente->idMarca,
                                'idModelo' => $primerArtDelCliente->idModelo,
                                'marca_nombre' => $primerArtDelCliente->marca_nombre,
                                'modelo_nombre' => $primerArtDelCliente->modelo_nombre,
                                'numero_ticket' => $primerArtDelCliente->numero_ticket,
                                'cliente_general_id' => $primerArtDelCliente->cliente_general_id_custodia,
                                'cliente_general_nombre' => $primerArtDelCliente->cliente_general_nombre_custodia ?: 'Sin cliente'
                            ]);
                        } else {
                            // ✅ SI ES PRODUCTO NORMAL - APLICAR LÓGICA DE REPUESTOS
                            $mostrandoCodigoRepuesto = ($primerArtDelCliente->idTipoArticulo == 2 && !empty($primerArtDelCliente->codigo_repuesto));

                            $productosAgrupados->push([
                                'id' => $primerArtDelCliente->idArticulos,
                                'nombre' => $primerArtDelCliente->producto,
                                'nombre_original' => $primerArtDelCliente->nombre_original,
                                'codigo_repuesto' => $primerArtDelCliente->codigo_repuesto,
                                'cantidad' => $cantidadPorCliente,
                                'stock_total' => $primerArtDelCliente->stock_total,
                                'tipo_articulo' => $primerArtDelCliente->tipo_articulo,
                                'idTipoArticulo' => $primerArtDelCliente->idTipoArticulo,
                                'categoria' => $primerArtDelCliente->categoria,
                                'custodia_id' => null,
                                'es_repuesto' => $primerArtDelCliente->idTipoArticulo == 2,
                                'mostrando_codigo_repuesto' => $mostrandoCodigoRepuesto,
                                'cliente_general_id' => $primerArtDelCliente->cliente_general_id,
                                'cliente_general_nombre' => $primerArtDelCliente->cliente_general_nombre ?: 'Sin cliente'
                            ]);
                        }
                    }
                }

                // ✅ CALCULAR CANTIDAD TOTAL SOLO SI HAY PRODUCTOS VÁLIDOS
                $cantidadTotal = $productosAgrupados->isNotEmpty() ? $productosAgrupados->sum('cantidad') : 0;

                // Determinar estado basado en porcentaje de ocupación
                $porcentajeOcupacion = 0;
                if ($primerArticulo->capacidad_maxima > 0) {
                    $porcentajeOcupacion = ($cantidadTotal / $primerArticulo->capacidad_maxima) * 100;
                }

                // Calcular estado
                $estado = $primerArticulo->estado_ocupacion;
                if ($estado == 'vacio' && $cantidadTotal > 0) {
                    if ($porcentajeOcupacion > 0 && $porcentajeOcupacion <= 24) $estado = 'bajo';
                    elseif ($porcentajeOcupacion <= 49) $estado = 'medio';
                    elseif ($porcentajeOcupacion <= 74) $estado = 'alto';
                    elseif ($porcentajeOcupacion > 74) $estado = 'muy_alto';
                } elseif ($cantidadTotal == 0) {
                    $estado = 'vacio';
                }

                // Acumular categorías y tipos
                $categoriasUnicas = $productosAgrupados->pluck('categoria')->filter()->unique();
                $tiposUnicos = $productosAgrupados->pluck('tipo_articulo')->filter()->unique();
                $clientesUnicos = $productosAgrupados->pluck('cliente_general_nombre')
                    ->filter(fn($cliente) => $cliente && $cliente !== 'Sin cliente')
                    ->unique();

                $ubicacionesEstructuradas[] = [
                    'id' => $primerArticulo->idRackUbicacion,
                    'codigo' => $primerArticulo->codigo_unico ?? $primerArticulo->codigo,
                    'productos' => $productosAgrupados->toArray(),
                    'producto' => $productosAgrupados->isNotEmpty() ? $productosAgrupados->first()['nombre'] : null,
                    'cantidad' => $cantidadTotal,
                    'cantidad_total' => $cantidadTotal,
                    'stock_total' => $productosAgrupados->isNotEmpty() ? $productosAgrupados->first()['stock_total'] : null,
                    'tipo_articulo' => $productosAgrupados->isNotEmpty() ? $productosAgrupados->first()['tipo_articulo'] : null,
                    'categoria' => $productosAgrupados->isNotEmpty() ? $productosAgrupados->first()['categoria'] : null,
                    'capacidad' => $primerArticulo->capacidad_maxima,
                    'estado' => $estado,
                    'nivel' => $primerArticulo->nivel,
                    'fecha' => $primerArticulo->updated_at, // ✅ AHORA SÍ EXISTE
                    'categorias_acumuladas' => $categoriasUnicas->isNotEmpty() ? $categoriasUnicas->join(', ') : 'Sin categoría',
                    'tipos_acumulados' => $tiposUnicos->isNotEmpty() ? $tiposUnicos->join(', ') : 'Sin tipo',
                    'clientes_acumulados' => $clientesUnicos->isNotEmpty() ? $clientesUnicos->join(', ') : 'Sin cliente',
                    'historial' => $historialPorUbicacion[$primerArticulo->idRackUbicacion] ?? []
                ];
            }

            $rackEstructurado['niveles'][] = [
                'numero' => $nivelNum,
                'ubicaciones' => $ubicacionesEstructuradas
            ];
        }

        // Obtener lista de todos los racks para navegación
        $todosRacks = DB::table('racks')
            ->where('sede', $sede)
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->pluck('nombre')
            ->toArray();

        return view('almacen.ubicaciones.detalle-rack', [
            'rack' => $rackEstructurado,
            'todosRacks' => $todosRacks,
            'rackActual' => $rack,
            'sedeActual' => $sede
        ]);
    }


    public function iniciarReubicacion(Request $request)
    {
        DB::beginTransaction();
        try {
            Log::info('🚀 ========== INICIO iniciarReubicacion ==========');
            Log::debug('📥 DATOS CRUDOS RECIBIDOS:', $request->all());

            // Forzar casting a enteros ANTES de la validación
            $request->merge([
                'ubicacion_origen_id' => (int) $request->ubicacion_origen_id,
                'cantidad' => (int) $request->cantidad,
                'articulo_id' => $request->articulo_id ? (int) $request->articulo_id : null,
                'cliente_general_id' => $request->cliente_general_id ? (int) $request->cliente_general_id : null,
                'custodia_id' => $request->custodia_id ? (int) $request->custodia_id : null
            ]);

            Log::debug('🔄 DATOS DESPUÉS DEL CASTING:', $request->all());

            // ========== VALIDACIÓN FLEXIBLE PARA AMBOS CASOS ==========
            Log::info('📋 INICIANDO VALIDACIÓN DE DATOS');

            $validator = Validator::make($request->all(), [
                'ubicacion_origen_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
                'producto' => 'required|string|min:1|max:255',
                'cantidad' => 'required|integer|min:1',
                'articulo_id' => 'nullable|integer|exists:articulos,idArticulos',
                'cliente_general_id' => 'nullable|integer|exists:clientegeneral,idClienteGeneral',
                'custodia_id' => 'nullable|integer|exists:custodias,id'
            ], [
                'cantidad.min' => 'La cantidad debe ser al menos 1 unidad.',
                'cantidad.integer' => 'La cantidad debe ser un número entero.',
                'ubicacion_origen_id.exists' => 'La ubicación origen no existe.',
                'producto.required' => 'El nombre del producto es requerido.'
            ]);

            // ✅ VALIDACIÓN PERSONALIZADA: Debe tener articulo_id O custodia_id
            $validator->after(function ($validator) use ($request) {
                if (!$request->articulo_id && !$request->custodia_id) {
                    $validator->errors()->add(
                        'articulo_id',
                        'Se requiere un artículo ID o custodia ID para la reubicación.'
                    );
                }

                // Si es producto normal, debe tener cliente_general_id
                if ($request->articulo_id && !$request->cliente_general_id) {
                    $validator->errors()->add(
                        'cliente_general_id',
                        'El cliente general es requerido para productos normales.'
                    );
                }
            });

            if ($validator->fails()) {
                Log::warning('❌ VALIDACIÓN FALLIDA:', [
                    'errors' => $validator->errors()->toArray(),
                    'input_data' => $request->all()
                ]);

                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }
            Log::info('✅ VALIDACIÓN EXITOSA');

            // ========== VERIFICAR UBICACIÓN ORIGEN ==========
            Log::info('🔎 BUSCANDO UBICACIÓN ORIGEN ID: ' . $request->ubicacion_origen_id);
            $ubicacionOrigen = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->select('ru.*', 'r.nombre as rack_nombre')
                ->where('ru.idRackUbicacion', $request->ubicacion_origen_id)
                ->first();

            if (!$ubicacionOrigen) {
                Log::warning('❌ UBICACIÓN ORIGEN NO ENCONTRADA:', [
                    'id_buscado' => $request->ubicacion_origen_id
                ]);

                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Ubicación origen no encontrada'
                ], 404);
            }
            Log::info('✅ UBICACIÓN ORIGEN ENCONTRADA:', [
                'id' => $ubicacionOrigen->idRackUbicacion,
                'codigo' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                'rack' => $ubicacionOrigen->rack_nombre
            ]);

            // ========== BUSCAR PRODUCTO ESPECÍFICO (AMBOS CASOS) ==========
            Log::info('🔍 BUSCANDO PRODUCTO ESPECÍFICO:', [
                'ubicacion_id' => $request->ubicacion_origen_id,
                'articulo_id' => $request->articulo_id,
                'cliente_general_id' => $request->cliente_general_id,
                'custodia_id' => $request->custodia_id
            ]);

            $query = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $request->ubicacion_origen_id);

            // ✅ BUSQUEDA FLEXIBLE: Por artículo+cliente O por custodia
            if ($request->custodia_id) {
                // Caso custodia
                $query->where('custodia_id', $request->custodia_id);
            } else {
                // Caso producto normal
                $query->where('articulo_id', $request->articulo_id)
                    ->where('cliente_general_id', $request->cliente_general_id);
            }

            $productoEspecifico = $query->first();

            if (!$productoEspecifico) {
                Log::warning('❌ PRODUCTO ESPECÍFICO NO ENCONTRADO:', [
                    'ubicacion_id' => $request->ubicacion_origen_id,
                    'articulo_id' => $request->articulo_id,
                    'cliente_general_id' => $request->cliente_general_id,
                    'custodia_id' => $request->custodia_id
                ]);

                // Debug: Ver qué productos existen en esta ubicación
                $productosEnUbicacion = DB::table('rack_ubicacion_articulos')
                    ->where('rack_ubicacion_id', $request->ubicacion_origen_id)
                    ->get();

                Log::debug('📦 PRODUCTOS EXISTENTES EN LA UBICACIÓN:', [
                    'total_productos' => $productosEnUbicacion->count(),
                    'productos' => $productosEnUbicacion->toArray()
                ]);

                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el producto específico en la ubicación origen'
                ], 404);
            }

            Log::info('✅ PRODUCTO ESPECÍFICO ENCONTRADO:', [
                'producto_id' => $productoEspecifico->articulo_id,
                'custodia_id' => $productoEspecifico->custodia_id,
                'cliente_general_id' => $productoEspecifico->cliente_general_id,
                'cantidad_actual' => $productoEspecifico->cantidad
            ]);

            $cantidadDisponible = $productoEspecifico->cantidad;

            // ========== CALCULAR CANTIDAD A MOVER ==========
            $cantidadAMover = min($request->cantidad, $cantidadDisponible);
            Log::info('🧮 CANTIDAD A MOVER CALCULADA:', [
                'cantidad_solicitada' => $request->cantidad,
                'cantidad_disponible' => $cantidadDisponible,
                'cantidad_a_mover' => $cantidadAMover
            ]);

            // ========== OBTENER INFORMACIÓN ADICIONAL ==========
            Log::info('📊 OBTENIENDO INFORMACIÓN ADICIONAL');

            $nombreProducto = $request->producto;
            $clienteNombre = 'Sin cliente';

            // ✅ OBTENER NOMBRE DEL PRODUCTO SEGÚN EL TIPO
            if ($request->custodia_id) {
                // Caso custodia - obtener datos de la custodia
                $custodiaInfo = DB::table('custodias as c')
                    ->leftJoin('tickets as t', 'c.numero_ticket', '=', 't.numero_ticket')
                    ->leftJoin('clientegeneral as cg', 't.idClienteGeneral', '=', 'cg.idClienteGeneral')
                    ->where('c.id', $request->custodia_id)
                    ->select('c.serie', 'c.codigocustodias', 'cg.descripcion as cliente_nombre')
                    ->first();

                if ($custodiaInfo) {
                    $nombreProducto = $custodiaInfo->serie ?: $custodiaInfo->codigocustodias ?: 'Custodia ' . $request->custodia_id;
                    $clienteNombre = $custodiaInfo->cliente_nombre ?: 'Sin cliente';
                }
            } else {
                // Caso producto normal
                $clienteInfo = DB::table('clientegeneral')
                    ->where('idClienteGeneral', $request->cliente_general_id)
                    ->first();
                $clienteNombre = $clienteInfo->descripcion ?? 'Sin cliente';

                // Obtener nombre del producto con lógica de repuestos
                $productoInfo = DB::table('articulos as a')
                    ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')
                    ->where('a.idArticulos', $request->articulo_id)
                    ->select(
                        'a.idArticulos',
                        'a.nombre',
                        'a.codigo_repuesto',
                        'ta.idTipoArticulo',
                        DB::raw('CASE 
                        WHEN ta.idTipoArticulo = 2 AND a.codigo_repuesto IS NOT NULL AND a.codigo_repuesto != "" 
                        THEN a.codigo_repuesto 
                        ELSE a.nombre 
                    END as producto_mostrar')
                    )
                    ->first();

                if ($productoInfo) {
                    $nombreProducto = $productoInfo->producto_mostrar ?? $productoInfo->nombre ?? $request->producto;
                }
            }

            // ========== PREPARAR RESPUESTA ==========
            $responseData = [
                'ubicacion_origen' => [
                    'id' => $ubicacionOrigen->idRackUbicacion,
                    'codigo' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                    'rack_nombre' => $ubicacionOrigen->rack_nombre,
                    'producto' => $nombreProducto,
                    'articulo_id' => $request->articulo_id,
                    'custodia_id' => $request->custodia_id,
                    'cliente_general_id' => $request->cliente_general_id,
                    'cliente_nombre' => $clienteNombre,
                    'cantidad' => $cantidadAMover,
                    'cantidad_disponible' => $cantidadDisponible,
                    'cantidad_solicitada_original' => $request->cantidad,
                    'es_custodia' => (bool) $request->custodia_id
                ]
            ];

            Log::info('🎉 REUBICACIÓN INICIADA EXITOSAMENTE:', [
                'ubicacion_origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                'producto' => $nombreProducto,
                'cliente' => $clienteNombre,
                'cantidad_a_mover' => $cantidadAMover,
                'es_custodia' => (bool) $request->custodia_id
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Modo reubicación activado',
                'data' => $responseData
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('💥 ERROR CRÍTICO en iniciarReubicacion:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }
    public function confirmarReubicacion(Request $request)
    {
        DB::beginTransaction();

        try {
            Log::debug('Datos recibidos para confirmar reubicación:', $request->all());

            // Forzar casting a enteros ANTES de la validación
            $request->merge([
                'ubicacion_origen_id' => (int) $request->ubicacion_origen_id,
                'ubicacion_destino_id' => (int) $request->ubicacion_destino_id,
                'cantidad' => (int) $request->cantidad,
                'es_custodia' => (bool) $request->es_custodia,
                'custodia_id' => $request->custodia_id ? (int) $request->custodia_id : null
            ]);

            $validator = Validator::make($request->all(), [
                'ubicacion_origen_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
                'ubicacion_destino_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
                'producto' => 'required|string|min:1|max:255',
                'cantidad' => 'required|integer|min:1',
                'tipo_reubicacion' => 'required|in:mismo_rack,otro_rack',
                'es_custodia' => 'sometimes|boolean',
                'custodia_id' => 'nullable|integer|exists:custodias,id' // Ajusta según tu tabla de custodias
            ], [
                'producto.required' => 'El nombre del producto es requerido',
                'cantidad.min' => 'La cantidad debe ser al menos 1 unidad.',
            ]);

            if ($validator->fails()) {
                Log::warning('Validación fallida en confirmarReubicacion:', [
                    'errors' => $validator->errors()->toArray(),
                    'input_data' => $request->all()
                ]);
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verificar que las ubicaciones sean diferentes
            if ($request->ubicacion_origen_id == $request->ubicacion_destino_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes reubicar a la misma ubicación'
                ], 422);
            }

            // Obtener información de ambas ubicaciones
            $ubicacionOrigen = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->select('ru.*', 'r.nombre as rack_nombre', 'r.idRack as rack_id')
                ->where('ru.idRackUbicacion', $request->ubicacion_origen_id)
                ->first();

            $ubicacionDestino = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->select('ru.*', 'r.nombre as rack_nombre', 'r.idRack as rack_id')
                ->where('ru.idRackUbicacion', $request->ubicacion_destino_id)
                ->first();

            // ✅ NUEVO: Lógica diferente para custodias vs productos normales
            if ($request->es_custodia) {
                // LÓGICA PARA CUSTODIAS
                return $this->reubicarCustodia($request, $ubicacionOrigen, $ubicacionDestino);
            } else {
                // LÓGICA PARA PRODUCTOS NORMALES (tu código actual)
                return $this->reubicarProductoNormal($request, $ubicacionOrigen, $ubicacionDestino);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al confirmar reubicación: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }
    // ✅ NUEVO: Método para reubicar custodias
    private function reubicarCustodia($request, $ubicacionOrigen, $ubicacionDestino)
    {
        // ✅ VERIFICAR QUE CUSTODIA_ID NO SEA NULL
        if (!$request->custodia_id || $request->custodia_id === 'null') {
            Log::warning('custodia_id es null o vacío:', [
                'custodia_id_recibido' => $request->custodia_id,
                'request_completo' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'El ID de custodia es requerido para la reubicación'
            ], 422);
        }

        // ✅ CAST A ENTERO
        $custodiaId = (int) $request->custodia_id;

        Log::debug('=== INICIO DEBUG CUSTODIA ===');
        Log::debug('Datos recibidos para reubicar custodia:', [
            'ubicacion_origen_id' => $request->ubicacion_origen_id,
            'ubicacion_destino_id' => $request->ubicacion_destino_id,
            'custodia_id' => $custodiaId,
            'es_custodia' => $request->es_custodia,
            'producto' => $request->producto
        ]);

        // Verificar si la custodia existe
        $custodiaExiste = DB::table('custodias')
            ->where('id', $custodiaId)
            ->exists();

        if (!$custodiaExiste) {
            Log::warning('Custodia no existe en tabla custodias:', [
                'custodia_id_buscado' => $custodiaId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'La custodia no existe en el sistema. ID: ' . $custodiaId
            ], 404);
        }

        // Verificar ubicación destino
        $productosEnDestino = DB::table('rack_ubicacion_articulos')
            ->where('rack_ubicacion_id', $request->ubicacion_destino_id)
            ->exists();

        if ($productosEnDestino) {
            return response()->json([
                'success' => false,
                'message' => 'La ubicación destino ya está ocupada'
            ], 422);
        }

        // Buscar la custodia en la ubicación origen
        $custodiaEnOrigen = DB::table('rack_ubicacion_articulos')
            ->where('rack_ubicacion_id', $request->ubicacion_origen_id)
            ->where('custodia_id', $custodiaId)
            ->first();

        if (!$custodiaEnOrigen) {
            // Diagnóstico detallado
            $productosEnOrigen = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $request->ubicacion_origen_id)
                ->get();

            $custodiaEnSistema = DB::table('rack_ubicacion_articulos')
                ->where('custodia_id', $custodiaId)
                ->first();

            Log::warning('Custodia no encontrada en ubicación origen:', [
                'ubicacion_origen_id' => $request->ubicacion_origen_id,
                'custodia_id_buscado' => $custodiaId,
                'custodia_en_otra_ubicacion' => $custodiaEnSistema,
                'productos_en_origen' => $productosEnOrigen->toArray()
            ]);

            $mensajeError = 'No se encontró la custodia en la ubicación origen. ';

            if ($custodiaEnSistema) {
                $mensajeError .= "La custodia está actualmente en la ubicación ID: {$custodiaEnSistema->rack_ubicacion_id}";
            }

            return response()->json([
                'success' => false,
                'message' => $mensajeError
            ], 404);
        }

        try {
            // 1. Mover la custodia
            DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $request->ubicacion_origen_id)
                ->where('custodia_id', $custodiaId)
                ->update([
                    'rack_ubicacion_id' => $request->ubicacion_destino_id,
                    'updated_at' => now()
                ]);

            Log::debug('Custodia movida exitosamente:', [
                'desde' => $request->ubicacion_origen_id,
                'hacia' => $request->ubicacion_destino_id
            ]);

            // 2. Actualizar estados de ocupación
            $this->actualizarEstadoOcupacion($request->ubicacion_origen_id);
            $this->actualizarEstadoOcupacion($request->ubicacion_destino_id);

            // 3. ✅ ACTUALIZADO: Registrar el movimiento CON custodia_id
            DB::table('rack_movimientos')->insert([
                'articulo_id' => null, // Para custodias
                'custodia_id' => $custodiaId, // ✅ Ahora la columna existe
                'ubicacion_origen_id' => $request->ubicacion_origen_id,
                'ubicacion_destino_id' => $request->ubicacion_destino_id,
                'rack_origen_id' => $ubicacionOrigen->rack_id,
                'rack_destino_id' => $ubicacionDestino->rack_id,
                'cantidad' => 1,
                'tipo_movimiento' => 'reubicacion_custodia', // ✅ Tipo específico
                'usuario_id' => auth()->id() ?? 1, // ✅ Agregar usuario que realiza la acción
                'observaciones' => 'Reubicación de custodia: ' . $request->producto,
                'codigo_ubicacion_origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                'codigo_ubicacion_destino' => $ubicacionDestino->codigo_unico ?? $ubicacionDestino->codigo,
                'nombre_rack_origen' => $ubicacionOrigen->rack_nombre,
                'nombre_rack_destino' => $ubicacionDestino->rack_nombre,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            Log::debug('Movimiento registrado en historial con custodia_id:', ['custodia_id' => $custodiaId]);

            // ✅ Obtener datos actualizados
            $ubicacionOrigenActualizada = $this->obtenerUbicacionConProductos($request->ubicacion_origen_id);
            $ubicacionDestinoActualizada = $this->obtenerUbicacionConProductos($request->ubicacion_destino_id);

            DB::commit();

            Log::debug('=== REUBICACIÓN CUSTODIA EXITOSA ===', [
                'custodia_id' => $custodiaId,
                'origen_actualizado' => $ubicacionOrigenActualizada['productos'] ?? [],
                'destino_actualizado' => $ubicacionDestinoActualizada['productos'] ?? []
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Custodia reubicada exitosamente',
                'data' => [
                    'origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                    'destino' => $ubicacionDestino->codigo_unico ?? $ubicacionDestino->codigo,
                    'cantidad' => 1,
                    'tipo' => 'reubicacion_custodia',
                    'custodia_id' => $custodiaId,
                    'ubicaciones_actualizadas' => [
                        'origen' => $ubicacionOrigenActualizada,
                        'destino' => $ubicacionDestinoActualizada
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error durante la reubicación de custodia: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno durante la reubicación: ' . $e->getMessage()
            ], 500);
        }
    }

    private function reubicarProductoNormal($request, $ubicacionOrigen, $ubicacionDestino)
    {
        // Verificar si la ubicación destino tiene productos DEL MISMO CLIENTE Y ARTÍCULO
        $productoExistenteEnDestino = DB::table('rack_ubicacion_articulos')
            ->where('rack_ubicacion_id', $request->ubicacion_destino_id)
            ->where('articulo_id', $request->articulo_id)
            ->where('cliente_general_id', $request->cliente_general_id)
            ->exists();

        if ($productoExistenteEnDestino) {
            return response()->json([
                'success' => false,
                'message' => 'La ubicación destino ya contiene este producto para el mismo cliente'
            ], 422);
        }

        // Buscar específicamente el artículo Y cliente en origen
        $articuloEnOrigen = DB::table('rack_ubicacion_articulos')
            ->where('rack_ubicacion_id', $request->ubicacion_origen_id)
            ->where('articulo_id', $request->articulo_id)
            ->where('cliente_general_id', $request->cliente_general_id)
            ->first();

        if (!$articuloEnOrigen) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el producto específico en la ubicación origen'
            ], 404);
        }

        $cantidadDisponible = $articuloEnOrigen->cantidad;

        if ($cantidadDisponible < $request->cantidad) {
            return response()->json([
                'success' => false,
                'message' => 'Cantidad insuficiente en la ubicación origen. Disponible: ' . $cantidadDisponible
            ], 422);
        }

        // ✅ CORREGIDO: Insertar en destino CON EL MISMO CLIENTE_GENERAL_ID
        DB::table('rack_ubicacion_articulos')->insert([
            'rack_ubicacion_id' => $request->ubicacion_destino_id,
            'articulo_id' => $request->articulo_id,
            'cliente_general_id' => $request->cliente_general_id,
            'cantidad' => $request->cantidad,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // ✅ CORREGIDO: Actualizar origen - ELIMINAR si la cantidad queda en 0
        $nuevaCantidadOrigen = $articuloEnOrigen->cantidad - $request->cantidad;

        if ($nuevaCantidadOrigen > 0) {
            // Si queda cantidad, actualizar
            DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $request->ubicacion_origen_id)
                ->where('articulo_id', $request->articulo_id)
                ->where('cliente_general_id', $request->cliente_general_id)
                ->update([
                    'cantidad' => $nuevaCantidadOrigen,
                    'updated_at' => now()
                ]);
        } else {
            // ✅ SI LA CANTIDAD LLEGA A 0, ELIMINAR EL REGISTRO COMPLETAMENTE
            DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $request->ubicacion_origen_id)
                ->where('articulo_id', $request->articulo_id)
                ->where('cliente_general_id', $request->cliente_general_id)
                ->delete();
        }

        // Actualizar estados
        $this->actualizarEstadoOcupacion($request->ubicacion_origen_id);
        $this->actualizarEstadoOcupacion($request->ubicacion_destino_id);

        // Obtener nombre del cliente para el historial
        $clienteInfo = DB::table('clientegeneral')
            ->where('idClienteGeneral', $request->cliente_general_id)
            ->first();

        // Registrar movimiento
        DB::table('rack_movimientos')->insert([
            'articulo_id' => $request->articulo_id,
            'ubicacion_origen_id' => $request->ubicacion_origen_id,
            'ubicacion_destino_id' => $request->ubicacion_destino_id,
            'rack_origen_id' => $ubicacionOrigen->rack_id,
            'rack_destino_id' => $ubicacionDestino->rack_id,
            'cantidad' => $request->cantidad,
            'tipo_movimiento' => 'reubicacion',
            'observaciones' => 'Reubicación de producto - Cliente: ' . ($clienteInfo->descripcion ?? 'Sin cliente'),
            'codigo_ubicacion_origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
            'codigo_ubicacion_destino' => $ubicacionDestino->codigo_unico ?? $ubicacionDestino->codigo,
            'nombre_rack_origen' => $ubicacionOrigen->rack_nombre,
            'nombre_rack_destino' => $ubicacionDestino->rack_nombre,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Obtener datos actualizados
        $ubicacionOrigenActualizada = $this->obtenerUbicacionConProductos($request->ubicacion_origen_id);
        $ubicacionDestinoActualizada = $this->obtenerUbicacionConProductos($request->ubicacion_destino_id);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Producto reubicado exitosamente',
            'data' => [
                'origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                'destino' => $ubicacionDestino->codigo_unico ?? $ubicacionDestino->codigo,
                'cantidad' => $request->cantidad,
                'cliente' => $clienteInfo->descripcion ?? 'Sin cliente',
                'tipo' => $request->tipo_reubicacion,
                'ubicaciones_actualizadas' => [
                    'origen' => $ubicacionOrigenActualizada,
                    'destino' => $ubicacionDestinoActualizada
                ]
            ]
        ]);
    }
    private function obtenerUbicacionConProductos($ubicacionId)
    {
        try {
            Log::debug("🔍 Obteniendo productos COMPLETOS para ubicación: {$ubicacionId}");

            // Obtener información base de la ubicación
            $ubicacionBase = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->where('ru.idRackUbicacion', $ubicacionId)
                ->select(
                    'ru.idRackUbicacion as id',
                    'ru.codigo_unico as codigo',
                    'ru.codigo',
                    'ru.nivel',
                    'ru.capacidad_maxima as capacidad',
                    'ru.estado_ocupacion',
                    'r.nombre as rack_nombre'
                )
                ->first();

            if (!$ubicacionBase) {
                return [
                    'id' => $ubicacionId,
                    'productos' => [],
                    'cantidad_total' => 0,
                    'estado' => 'vacio'
                ];
            }

            // ✅ CORREGIDO: Obtener productos normales - CON JOIN CORRECTO PARA CATEGORÍA
            $productos = DB::table('rack_ubicacion_articulos as rua')
                ->leftJoin('articulos as a', 'rua.articulo_id', '=', 'a.idArticulos')
                ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')
                // ✅ PARA REPUESTOS: categoría desde articulo_modelo
                ->leftJoin('articulo_modelo as am', function ($join) {
                    $join->on('a.idArticulos', '=', 'am.articulo_id')
                        ->where('a.idTipoArticulo', '=', 2);
                })
                ->leftJoin('modelo as m_repuesto', 'am.modelo_id', '=', 'm_repuesto.idModelo')
                ->leftJoin('categoria as c_repuesto', 'm_repuesto.idCategoria', '=', 'c_repuesto.idCategoria')
                // ✅ PARA PRODUCTOS NORMALES: categoría directa
                ->leftJoin('modelo as m_normal', 'a.idModelo', '=', 'm_normal.idModelo')
                ->leftJoin('categoria as c_normal', 'm_normal.idCategoria', '=', 'c_normal.idCategoria')
                ->leftJoin('clientegeneral as cg', 'rua.cliente_general_id', '=', 'cg.idClienteGeneral')
                ->where('rua.rack_ubicacion_id', $ubicacionId)
                ->whereNull('rua.custodia_id')
                ->select(
                    'a.idArticulos as id',
                    DB::raw('CASE 
            WHEN ta.idTipoArticulo = 2 AND a.codigo_repuesto IS NOT NULL AND a.codigo_repuesto != "" 
            THEN a.codigo_repuesto 
            ELSE a.nombre 
        END as nombre'),
                    'a.nombre as nombre_original',
                    'a.codigo_repuesto',
                    'a.stock_total',
                    'ta.nombre as tipo_articulo',
                    'ta.idTipoArticulo',
                    // ✅ CATEGORÍA CORRECTA: Si es repuesto usa c_repuesto, sino c_normal
                    DB::raw('CASE 
            WHEN a.idTipoArticulo = 2 THEN c_repuesto.nombre 
            ELSE c_normal.nombre 
        END as categoria'),
                    'rua.cantidad',
                    'rua.cliente_general_id',
                    'cg.descripcion as cliente_general_nombre',
                    DB::raw('NULL as custodia_id'),
                    DB::raw('(ta.idTipoArticulo = 2) as es_repuesto'),
                    DB::raw('(ta.idTipoArticulo = 2 AND a.codigo_repuesto IS NOT NULL AND a.codigo_repuesto != "") as mostrando_codigo_repuesto')
                )
                ->get()
                ->map(function ($item) {
                    return (array) $item;
                })
                ->toArray();

            // ✅ Obtener custodias - CONVERTIR A ARRAY
            $custodias = DB::table('rack_ubicacion_articulos as rua')
                ->leftJoin('custodias as cust', 'rua.custodia_id', '=', 'cust.id')
                ->leftJoin('modelo as m', 'cust.idModelo', '=', 'm.idModelo')
                ->leftJoin('categoria as c', 'm.idCategoria', '=', 'c.idCategoria')
                ->leftJoin('marca as mar', 'cust.idMarca', '=', 'mar.idMarca')
                ->leftJoin('tickets as t', 'cust.numero_ticket', '=', 't.numero_ticket')
                ->leftJoin('clientegeneral as cg', 't.idClienteGeneral', '=', 'cg.idClienteGeneral')
                ->where('rua.rack_ubicacion_id', $ubicacionId)
                ->whereNotNull('rua.custodia_id')
                ->select(
                    DB::raw('NULL as id'),
                    DB::raw('COALESCE(cust.serie, cust.codigocustodias, CONCAT("Custodia ", cust.id)) as nombre'),
                    DB::raw('NULL as nombre_original'),
                    DB::raw('NULL as codigo_repuesto'),
                    DB::raw('NULL as stock_total'),
                    DB::raw('"CUSTODIA" as tipo_articulo'),
                    DB::raw('NULL as idTipoArticulo'),
                    'c.nombre as categoria',
                    'rua.cantidad',
                    'cg.idClienteGeneral as cliente_general_id',
                    'cg.descripcion as cliente_general_nombre',
                    'cust.id as custodia_id',
                    'cust.codigocustodias',
                    'cust.serie',
                    'cust.idMarca',
                    'cust.idModelo',
                    'mar.nombre as marca_nombre',
                    'm.nombre as modelo_nombre',
                    'cust.numero_ticket',
                    DB::raw('false as es_repuesto'),
                    DB::raw('false as mostrando_codigo_repuesto')
                )
                ->get()
                ->map(function ($item) {
                    return (array) $item; // ✅ CONVERTIR OBJETO A ARRAY
                })
                ->toArray();

            // Combinar arrays (ya no necesitas ->toArray() porque ya son arrays)
            $todosLosProductos = array_merge($productos, $custodias);
            $cantidadTotal = array_sum(array_column($todosLosProductos, 'cantidad'));

            // Calcular estado
            $porcentajeOcupacion = 0;
            if ($ubicacionBase->capacidad > 0) {
                $porcentajeOcupacion = ($cantidadTotal / $ubicacionBase->capacidad) * 100;
            }

            $estado = $ubicacionBase->estado_ocupacion;
            if ($estado == 'vacio' && $cantidadTotal > 0) {
                if ($porcentajeOcupacion > 0 && $porcentajeOcupacion <= 24) $estado = 'bajo';
                elseif ($porcentajeOcupacion <= 49) $estado = 'medio';
                elseif ($porcentajeOcupacion <= 74) $estado = 'alto';
                elseif ($porcentajeOcupacion > 74) $estado = 'muy_alto';
            } elseif ($cantidadTotal == 0) {
                $estado = 'vacio';
            }

            // Acumular categorías y tipos - USAR ARRAYS
            $categoriasUnicas = array_unique(array_filter(array_column($todosLosProductos, 'categoria')));
            $tiposUnicos = array_unique(array_filter(array_column($todosLosProductos, 'tipo_articulo')));

            $clientesNombres = array_filter(
                array_column($todosLosProductos, 'cliente_general_nombre'),
                fn($cliente) => $cliente && $cliente !== 'Sin cliente'
            );
            $clientesUnicos = array_unique($clientesNombres);

            Log::debug("✅ Ubicación {$ubicacionId} procesada:", [
                'productos' => count($todosLosProductos),
                'cantidad_total' => $cantidadTotal,
                'estado' => $estado
            ]);

            return [
                'id' => $ubicacionBase->id,
                'codigo' => $ubicacionBase->codigo_unico ?? $ubicacionBase->codigo,
                'productos' => $todosLosProductos,
                'producto' => !empty($todosLosProductos) ? $todosLosProductos[0]['nombre'] : null,
                'cantidad' => $cantidadTotal,
                'cantidad_total' => $cantidadTotal,
                'stock_total' => !empty($todosLosProductos) ? ($todosLosProductos[0]['stock_total'] ?? null) : null,
                'tipo_articulo' => !empty($todosLosProductos) ? $todosLosProductos[0]['tipo_articulo'] : null,
                'categoria' => !empty($todosLosProductos) ? $todosLosProductos[0]['categoria'] : null,
                'capacidad' => $ubicacionBase->capacidad,
                'estado' => $estado,
                'nivel' => $ubicacionBase->nivel,
                'fecha' => now()->toISOString(),
                'categorias_acumuladas' => !empty($categoriasUnicas) ? implode(', ', $categoriasUnicas) : 'Sin categoría',
                'tipos_acumulados' => !empty($tiposUnicos) ? implode(', ', $tiposUnicos) : 'Sin tipo',
                'clientes_acumulados' => !empty($clientesUnicos) ? implode(', ', $clientesUnicos) : 'Sin cliente',
                'historial' => []
            ];
        } catch (\Exception $e) {
            Log::error("❌ Error en obtenerUbicacionConProductos: " . $e->getMessage());
            return [
                'id' => $ubicacionId,
                'productos' => [],
                'cantidad_total' => 0,
                'estado' => 'vacio'
            ];
        }
    }

    public function cancelarReubicacion(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Reubicación cancelada'
        ]);
    }

    /**
     * Calcular estado de ocupación basado en porcentaje
     */
    private function calcularEstadoOcupacion($cantidad, $capacidadMaxima)
    {
        if ($capacidadMaxima <= 0) return 'vacio';

        $porcentaje = ($cantidad / $capacidadMaxima) * 100;

        if ($porcentaje == 0) return 'vacio';
        if ($porcentaje <= 24) return 'bajo';
        if ($porcentaje <= 49) return 'medio';
        if ($porcentaje <= 74) return 'alto';
        return 'muy_alto';
    }


    public function listarProductos()
    {
        try {
            $productos = DB::table('articulos as a')
                ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')
                ->leftJoin('modelo as m', 'a.idModelo', '=', 'm.idModelo')
                ->leftJoin('categoria as c', 'm.idCategoria', '=', 'c.idCategoria')
                ->where('a.estado', 1)
                ->select(
                    'a.idArticulos as id',
                    'a.nombre',
                    'a.codigo_repuesto',
                    'ta.nombre as tipo_articulo',
                    'ta.idTipoArticulo',
                    'c.nombre as categoria',
                    'a.stock_total as stock'
                )
                ->orderBy('a.nombre')
                ->get();

            // Procesar los datos para aplicar la lógica de visualización
            $productosProcesados = $productos->map(function ($producto) {
                $mostrarComoRepuesto = $producto->idTipoArticulo == 2; // 2 = REPUESTOS

                // ✅ NUEVO: Determinar qué nombre mostrar según el tipo
                $nombreMostrar = $mostrarComoRepuesto
                    ? ($producto->codigo_repuesto ?: $producto->nombre)
                    : $producto->nombre;

                return [
                    'id' => $producto->id,
                    'nombre' => $nombreMostrar,
                    'nombre_original' => $producto->nombre, // Mantener el nombre original
                    'codigo_repuesto' => $producto->codigo_repuesto,
                    'tipo_articulo' => $producto->tipo_articulo,
                    'idTipoArticulo' => $producto->idTipoArticulo,
                    'categoria' => $producto->categoria,
                    'stock' => $producto->stock,
                    'es_repuesto' => $mostrarComoRepuesto,
                    // ✅ NUEVO: Campo para indicar si se está mostrando el código de repuesto
                    'mostrando_codigo_repuesto' => $mostrarComoRepuesto && !empty($producto->codigo_repuesto)
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $productosProcesados
            ]);
        } catch (\Exception $e) {
            Log::error('Error al listar productos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar productos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function agregarProducto(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'ubicacion_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
                'articulo_id' => 'required|integer|exists:articulos,idArticulos',
                'cantidad' => 'required|integer|min:1',
                'cliente_general_id' => 'required|integer|exists:clientegeneral,idClienteGeneral',
                'tipo_ingreso' => 'required|in:compra,entrada_proveedor,ajuste',
                'observaciones' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Obtener información de la ubicación
            $ubicacion = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->select('ru.*', 'r.nombre as rack_nombre', 'r.idRack as rack_id')
                ->where('ru.idRackUbicacion', $request->ubicacion_id)
                ->first();

            // ✅ CORREGIDO: Calcular cantidad total actual en la ubicación PARA ESTE CLIENTE Y ARTÍCULO
            $cantidadTotalActual = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $request->ubicacion_id)
                ->where('articulo_id', $request->articulo_id)
                ->where('cliente_general_id', $request->cliente_general_id)
                ->sum('cantidad');

            // Verificar que la nueva cantidad no supere la capacidad
            if (($cantidadTotalActual + $request->cantidad) > $ubicacion->capacidad_maxima) {
                return response()->json([
                    'success' => false,
                    'message' => 'La cantidad supera la capacidad máxima de la ubicación. Espacio disponible: ' . ($ubicacion->capacidad_maxima - $cantidadTotalActual) . ' unidades'
                ], 422);
            }

            // Obtener información del producto
            $producto = DB::table('articulos')
                ->where('idArticulos', $request->articulo_id)
                ->first();

            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            // ✅ NUEVO: Calcular nuevo stock total
            $stockActual = $producto->stock_total ?? 0;
            $nuevoStockTotal = $stockActual + $request->cantidad;

            // ✅ NUEVO: Actualizar stock_total en la tabla articulos
            DB::table('articulos')
                ->where('idArticulos', $request->articulo_id)
                ->update([
                    'stock_total' => $nuevoStockTotal,
                    'updated_at' => now()
                ]);

            // ✅ CORREGIDO: Verificar si ya existe el artículo en la ubicación CON EL MISMO CLIENTE
            $articuloExistente = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $request->ubicacion_id)
                ->where('articulo_id', $request->articulo_id)
                ->where('cliente_general_id', $request->cliente_general_id)
                ->first();

            if ($articuloExistente) {
                // Actualizar cantidad existente
                DB::table('rack_ubicacion_articulos')
                    ->where('rack_ubicacion_id', $request->ubicacion_id)
                    ->where('articulo_id', $request->articulo_id)
                    ->where('cliente_general_id', $request->cliente_general_id)
                    ->update([
                        'cantidad' => $articuloExistente->cantidad + $request->cantidad,
                        'updated_at' => now()
                    ]);
            } else {
                try {
                    // Insertar nuevo registro CON CLIENTE GENERAL
                    DB::table('rack_ubicacion_articulos')->insert([
                        'rack_ubicacion_id' => $request->ubicacion_id,
                        'articulo_id' => $request->articulo_id,
                        'cliente_general_id' => $request->cliente_general_id,
                        'cantidad' => $request->cantidad,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } catch (\Exception $e) {
                    // ✅ MANEJO DE ERROR DE UNICIDAD - Si falla por la constraint, actualizar
                    if (str_contains($e->getMessage(), 'Duplicate entry')) {
                        DB::table('rack_ubicacion_articulos')
                            ->where('rack_ubicacion_id', $request->ubicacion_id)
                            ->where('articulo_id', $request->articulo_id)
                            ->where('cliente_general_id', $request->cliente_general_id)
                            ->update([
                                'cantidad' => DB::raw('cantidad + ' . $request->cantidad),
                                'updated_at' => now()
                            ]);
                    } else {
                        throw $e;
                    }
                }
            }

            // ✅ NUEVO: Registrar en inventario_ingresos_clientes
            DB::table('inventario_ingresos_clientes')->insert([
                'articulo_id' => $request->articulo_id,
                'cliente_general_id' => $request->cliente_general_id,
                'tipo_ingreso' => $request->tipo_ingreso,
                'cantidad' => $request->cantidad,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Actualizar estado de ocupación de la ubicación
            $nuevaCantidadTotal = $cantidadTotalActual + $request->cantidad;
            $nuevoEstado = $this->calcularEstadoOcupacion($nuevaCantidadTotal, $ubicacion->capacidad_maxima);

            DB::table('rack_ubicaciones')
                ->where('idRackUbicacion', $request->ubicacion_id)
                ->update([
                    'estado_ocupacion' => $nuevoEstado,
                    'updated_at' => now()
                ]);

            // Registrar el movimiento
            DB::table('rack_movimientos')->insert([
                'articulo_id' => $request->articulo_id,
                'ubicacion_destino_id' => $request->ubicacion_id,
                'rack_destino_id' => $ubicacion->rack_id,
                'cantidad' => $request->cantidad,
                'tipo_movimiento' => 'ajuste',
                'observaciones' => $request->observaciones ?: 'Ingreso de producto con cliente general',
                'codigo_ubicacion_destino' => $ubicacion->codigo_unico ?? $ubicacion->codigo,
                'nombre_rack_destino' => $ubicacion->rack_nombre,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Producto agregado exitosamente con cliente general',
                'data' => [
                    'producto' => [
                        'id' => $producto->idArticulos,
                        'nombre' => $producto->nombre,
                        'cantidad' => $request->cantidad,
                        'stock_anterior' => $stockActual,
                        'stock_nuevo' => $nuevoStockTotal
                    ],
                    'cliente_general_id' => $request->cliente_general_id,
                    'espacio_disponible' => $ubicacion->capacidad_maxima - $nuevaCantidadTotal,
                    'cantidad_total_ubicacion' => $nuevaCantidadTotal
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al agregar producto: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function vaciarUbicacion(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'ubicacion_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Obtener información de la ubicación
            $ubicacion = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->select('ru.*', 'r.nombre as rack_nombre', 'r.idRack as rack_id')
                ->where('ru.idRackUbicacion', $request->ubicacion_id)
                ->first();

            // Obtener todos los productos de esta ubicación
            $productosEnUbicacion = DB::table('rack_ubicacion_articulos as rua')
                ->join('articulos as a', 'rua.articulo_id', '=', 'a.idArticulos')
                ->where('rua.rack_ubicacion_id', $request->ubicacion_id)
                ->select('a.nombre as producto', 'rua.cantidad', 'a.idArticulos', 'a.stock_total')
                ->get();

            // Verificar que la ubicación tenga productos
            if ($productosEnUbicacion->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'La ubicación ya está vacía'
                ], 422);
            }

            // ✅ NUEVO: Actualizar stock_total para cada artículo y registrar en inventario_ingresos_clientes
            foreach ($productosEnUbicacion as $producto) {
                $nuevoStockTotal = $producto->stock_total - $producto->cantidad;

                if ($nuevoStockTotal < 0) {
                    $nuevoStockTotal = 0;
                }

                DB::table('articulos')
                    ->where('idArticulos', $producto->idArticulos)
                    ->update([
                        'stock_total' => $nuevoStockTotal,
                        'updated_at' => now()
                    ]);

                // ✅ NUEVO: Obtener el último cliente general asociado a este artículo
                $ultimoClienteGeneral = DB::table('inventario_ingresos_clientes')
                    ->where('articulo_id', $producto->idArticulos)
                    ->where('tipo_ingreso', 'ajuste')
                    ->orderBy('created_at', 'desc')
                    ->first();

                // ✅ NUEVO: Registrar en inventario_ingresos_clientes (cantidad negativa)
                DB::table('inventario_ingresos_clientes')->insert([
                    'articulo_id' => $producto->idArticulos,
                    'cliente_general_id' => $ultimoClienteGeneral ? $ultimoClienteGeneral->cliente_general_id : null,
                    'tipo_ingreso' => 'ajuste',
                    'cantidad' => -$producto->cantidad, // ✅ Cantidad negativa para salida
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Registrar movimientos de salida para cada producto
            foreach ($productosEnUbicacion as $producto) {
                DB::table('rack_movimientos')->insert([
                    'articulo_id' => $producto->idArticulos,
                    'ubicacion_origen_id' => $request->ubicacion_id,
                    'rack_origen_id' => $ubicacion->rack_id,
                    'cantidad' => $producto->cantidad,
                    'tipo_movimiento' => 'salida',
                    'observaciones' => 'Ubicación vaciada manualmente - ' . $producto->producto,
                    'codigo_ubicacion_origen' => $ubicacion->codigo_unico ?? $ubicacion->codigo,
                    'nombre_rack_origen' => $ubicacion->rack_nombre,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Vaciar TODOS los productos de la ubicación en rack_ubicacion_articulos
            DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $request->ubicacion_id)
                ->delete();

            // Actualizar la ubicación a estado vacío
            DB::table('rack_ubicaciones')
                ->where('idRackUbicacion', $request->ubicacion_id)
                ->update([
                    'estado_ocupacion' => 'vacio',
                    'updated_at' => now()
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ubicación vaciada exitosamente. Se removieron ' . $productosEnUbicacion->count() . ' productos.',
                'data' => [
                    'productos_removidos' => $productosEnUbicacion->count(),
                    'productos' => $productosEnUbicacion->pluck('producto')
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al vaciar ubicación: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }


    public function listarRacksDisponibles()
    {
        try {
            // Excluir el rack actual y obtener racks activos
            $racks = DB::table('racks')
                ->where('estado', 'activo')
                ->where('idRack', '!=', request('rack_actual')) // Puedes enviar el rack actual desde el frontend si es necesario
                ->select('idRack as id', 'nombre', 'sede')
                ->orderBy('nombre')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $racks
            ]);
        } catch (\Exception $e) {
            Log::error('Error al listar racks disponibles: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar racks disponibles'
            ], 500);
        }
    }

    public function listarUbicacionesVacias($rackId)
    {
        try {
            $ubicaciones = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->where('ru.rack_id', $rackId)
                ->where('ru.estado_ocupacion', 'vacio')
                ->where(function ($query) {
                    $query->where('ru.articulo_id', null)
                        ->orWhere('ru.cantidad_actual', 0);
                })
                ->select(
                    'ru.idRackUbicacion as id',
                    'ru.codigo',
                    'ru.codigo_unico',
                    'ru.capacidad_maxima',
                    'ru.nivel',
                    'ru.posicion',
                    'r.nombre as rack_nombre'
                )
                ->orderBy('ru.nivel')
                ->orderBy('ru.posicion')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $ubicaciones
            ]);
        } catch (\Exception $e) {
            Log::error('Error al listar ubicaciones vacías: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar ubicaciones vacías'
            ], 500);
        }
    }


    public function crearRack(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'nombre' => [
                    'required',
                    'string',
                    'max:10',
                    function ($attribute, $value, $fail) use ($request) {
                        $existe = DB::table('racks')
                            ->where('nombre', $value)
                            ->where('sede', $request->sede)
                            ->exists();

                        if ($existe) {
                            $fail("El nombre '$value' ya está en uso en la sede {$request->sede}.");
                        }
                    }
                ],
                'sede' => 'required|string|max:50|exists:sucursal,nombre',
                'filas' => 'required|integer|min:1|max:12',
                'columnas' => 'required|integer|min:1|max:24',
                'capacidad_maxima' => 'required|integer|min:1|max:1000',
                'estado' => 'required|in:activo,inactivo'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Crear el rack
            $rackId = DB::table('racks')->insertGetId([
                'nombre' => $request->nombre,
                'sede' => $request->sede,
                'filas' => $request->filas,
                'columnas' => $request->columnas,
                'estado' => $request->estado,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $rack = DB::table('racks')->where('idRack', $rackId)->first();
            $capacidadMaxima = $request->input('capacidad_maxima', 100);

            // Generar ubicaciones automáticamente
            $this->generarUbicacionesAutomaticas($rack, $capacidadMaxima);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rack creado exitosamente con ' . ($request->filas * $request->columnas) . ' ubicaciones generadas',
                'data' => [
                    'id' => $rackId,
                    'total_ubicaciones' => $request->filas * $request->columnas
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear rack: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }


    public function actualizarDimensionesRack(Request $request, $rackId)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'filas' => 'required|integer|min:1|max:12',
                'columnas' => 'required|integer|min:1|max:24',
                'capacidad_maxima' => 'required|integer|min:1|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Obtener el rack actual
            $rack = DB::table('racks')->where('idRack', $rackId)->first();

            if (!$rack) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rack no encontrado'
                ], 404);
            }

            // Obtener las ubicaciones existentes
            $ubicacionesExistentes = DB::table('rack_ubicaciones')
                ->where('rack_id', $rackId)
                ->get();

            // Actualizar las dimensiones del rack
            DB::table('racks')
                ->where('idRack', $rackId)
                ->update([
                    'filas' => $request->filas,
                    'columnas' => $request->columnas,
                    'updated_at' => now()
                ]);

            $rackActualizado = DB::table('racks')->where('idRack', $rackId)->first();
            $nuevasUbicacionesGeneradas = $this->sincronizarUbicaciones($rackActualizado, $ubicacionesExistentes, $request->capacidad_maxima);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dimensiones actualizadas exitosamente. ' . $nuevasUbicacionesGeneradas . ' nuevas ubicaciones generadas.',
                'data' => [
                    'rack_id' => $rackId,
                    'nuevas_ubicaciones' => $nuevasUbicacionesGeneradas,
                    'total_filas' => $request->filas,
                    'total_columnas' => $request->columnas
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar dimensiones del rack: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    private function sincronizarUbicaciones($rack, $ubicacionesExistentes, $capacidadMaxima = 100)
    {
        $nuevasUbicaciones = [];
        $now = now();
        $ubicacionesCreadas = 0;

        // Crear un mapa de ubicaciones existentes para verificación rápida
        $mapaExistente = [];
        foreach ($ubicacionesExistentes as $ubicacion) {
            $clave = "{$ubicacion->nivel}-{$ubicacion->posicion}";
            $mapaExistente[$clave] = $ubicacion;
        }

        // Generar todas las ubicaciones según las nuevas dimensiones
        for ($nivel = 1; $nivel <= $rack->filas; $nivel++) {
            for ($posicion = 1; $posicion <= $rack->columnas; $posicion++) {
                $clave = "{$nivel}-{$posicion}";

                // Si la ubicación no existe, crearla
                if (!isset($mapaExistente[$clave])) {
                    $codigo = $this->generarCodigoUbicacion($rack->nombre, $nivel, $posicion);
                    $codigoUnico = $rack->nombre . '-' . $codigo;

                    $nuevasUbicaciones[] = [
                        'rack_id' => $rack->idRack,
                        'codigo' => $codigo,
                        'codigo_unico' => $codigoUnico,
                        'nivel' => $nivel,
                        'posicion' => $posicion,
                        'estado_ocupacion' => 'vacio',
                        'capacidad_maxima' => $capacidadMaxima,
                        'articulo_id' => null,
                        'cantidad_actual' => 0,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];

                    $ubicacionesCreadas++;
                }
            }
        }

        // Insertar las nuevas ubicaciones en lote
        if (!empty($nuevasUbicaciones)) {
            DB::table('rack_ubicaciones')->insert($nuevasUbicaciones);

            Log::debug('Nuevas ubicaciones generadas', [
                'rack_id' => $rack->idRack,
                'rack_nombre' => $rack->nombre,
                'nuevas_ubicaciones' => count($nuevasUbicaciones),
                'filas' => $rack->filas,
                'columnas' => $rack->columnas
            ]);
        }

        return $ubicacionesCreadas;
    }

    /**
     * Genera ubicaciones automáticamente para un rack
     */
    private function generarUbicacionesAutomaticas($rack, $capacidadMaxima = 100)
    {
        $ubicaciones = [];
        $now = now();

        // Generar ubicaciones basadas en filas y columnas
        for ($nivel = 1; $nivel <= $rack->filas; $nivel++) {
            for ($posicion = 1; $posicion <= $rack->columnas; $posicion++) {
                // Generar código automático (ej: A1-01, A1-02, etc.)
                $codigo = $this->generarCodigoUbicacion($rack->nombre, $nivel, $posicion);
                $codigoUnico = $rack->nombre . '-' . $codigo;

                $ubicaciones[] = [
                    'rack_id' => $rack->idRack,
                    'codigo' => $codigo,
                    'codigo_unico' => $codigoUnico,
                    'nivel' => $nivel,
                    'posicion' => $posicion,
                    'estado_ocupacion' => 'vacio',
                    'capacidad_maxima' => $capacidadMaxima,

                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
        }

        // Insertar todas las ubicaciones en lote
        if (!empty($ubicaciones)) {
            DB::table('rack_ubicaciones')->insert($ubicaciones);
        }

        Log::debug('Ubicaciones generadas automáticamente', [
            'rack_id' => $rack->idRack,
            'rack_nombre' => $rack->nombre,
            'total_ubicaciones' => count($ubicaciones),
            'filas' => $rack->filas,
            'columnas' => $rack->columnas,
            'capacidad_maxima' => $capacidadMaxima
        ]);
    }

    /**
     * Genera el código de ubicación basado en el formato existente
     */
    private function generarCodigoUbicacion($nombreRack, $nivel, $posicion)
    {
        // Formato: {LetraRack}{Nivel}-{Posición con 2 dígitos}
        // Ejemplo: A1-01, A1-02, B2-01, etc.
        return $nombreRack . $nivel . '-' . str_pad($posicion, 2, '0', STR_PAD_LEFT);
    }

    public function sugerirSiguienteLetra(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sede' => 'required|string|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sede requerida'
                ], 422);
            }

            $sede = $request->sede;

            // Obtener todas las letras usadas en esta sede específica
            $letrasUsadas = DB::table('racks')
                ->where('sede', $sede) // Filtramos por el nombre de la sede
                ->where('estado', 'activo')
                ->pluck('nombre')
                ->map(function ($nombre) {
                    // Extraer solo la letra (asumiendo que el nombre es una sola letra)
                    $letra = strtoupper(trim($nombre));
                    // Si es una sola letra A-Z, devolverla, sino null
                    return preg_match('/^[A-Z]$/', $letra) ? $letra : null;
                })
                ->filter() // Remover nulls
                ->unique()
                ->sort()
                ->values();

            Log::debug('Letras usadas en sede ' . $sede, ['letras' => $letrasUsadas->toArray()]);

            // Definir el abecedario completo
            $abecedario = range('A', 'Z');

            // Encontrar la primera letra disponible
            $siguienteLetra = null;
            foreach ($abecedario as $letra) {
                if (!$letrasUsadas->contains($letra)) {
                    $siguienteLetra = $letra;
                    break;
                }
            }

            // Si todas las letras están usadas, sugerir patrón con números
            if (!$siguienteLetra) {
                // Buscar el último rack numérico en esta sede
                $racksConNumeros = DB::table('racks')
                    ->where('sede', $sede)
                    ->where('estado', 'activo')
                    ->where('nombre', 'regexp', '^[A-Z][0-9]+$')
                    ->pluck('nombre')
                    ->sort()
                    ->values();

                if ($racksConNumeros->isNotEmpty()) {
                    $ultimoRack = $racksConNumeros->last();
                    // Extraer número y aumentar
                    preg_match('/([A-Z])(\d+)/', $ultimoRack, $matches);
                    if (count($matches) === 3) {
                        $letraBase = $matches[1];
                        $numero = (int)$matches[2] + 1;
                        $siguienteLetra = $letraBase . $numero;
                    } else {
                        $siguienteLetra = 'A1';
                    }
                } else {
                    // Si no hay racks con números, empezar con A1
                    $siguienteLetra = 'A1';
                }
            }

            // Si aún no hay sugerencia, usar doble letra (AA, AB, etc.)
            if (!$siguienteLetra) {
                // Buscar si ya hay racks con doble letra en esta sede
                $racksDobleLetra = DB::table('racks')
                    ->where('sede', $sede)
                    ->where('estado', 'activo')
                    ->where('nombre', 'regexp', '^[A-Z]{2}$')
                    ->pluck('nombre')
                    ->sort()
                    ->values();

                if ($racksDobleLetra->isNotEmpty()) {
                    $ultimaDobleLetra = $racksDobleLetra->last();
                    // Incrementar la doble letra (AA -> AB, AB -> AC, etc.)
                    $siguienteLetra = ++$ultimaDobleLetra;
                } else {
                    $siguienteLetra = 'AA';
                }
            }

            Log::debug('Siguiente letra sugerida', [
                'sede' => $sede,
                'sugerencia' => $siguienteLetra,
                'letras_usadas' => $letrasUsadas->toArray()
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'sugerencia' => $siguienteLetra,
                    'letras_usadas' => $letrasUsadas->toArray(),
                    'abecedario_completo' => $abecedario
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error al sugerir siguiente letra: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al generar sugerencia: ' . $e->getMessage()
            ], 500);
        }
    }
    public function listarRacks()
    {
        try {
            $racks = DB::table('racks')
                ->where('estado', 'activo')
                ->select('idRack', 'nombre', 'sede', 'filas', 'columnas')
                ->orderBy('nombre')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $racks
            ]);
        } catch (\Exception $e) {
            Log::error('Error al listar racks: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar racks'
            ], 500);
        }
    }

    public function obtenerInfoRack($id)
    {
        try {
            $rack = DB::table('racks')
                ->where('idRack', $id)
                ->select('idRack', 'nombre', 'sede', 'filas', 'columnas')
                ->first();

            if (!$rack) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rack no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $rack
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener info del rack: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar información del rack'
            ], 500);
        }
    }

    public function crearUbicacion(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'rack_id' => 'required|integer|exists:racks,idRack',
                'codigo' => 'required|string|max:20',
                'nivel' => 'required|integer|min:1',
                'posicion' => 'required|integer|min:1',
                'capacidad_maxima' => 'required|integer|min:1',
                'estado_ocupacion' => 'required|in:vacio,bajo,medio,alto,muy_alto'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verificar que no exista ya una ubicación con el mismo código en el mismo rack
            $ubicacionExistente = DB::table('rack_ubicaciones')
                ->where('rack_id', $request->rack_id)
                ->where('codigo', $request->codigo)
                ->exists();

            if ($ubicacionExistente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe una ubicación con este código en el rack seleccionado'
                ], 422);
            }

            // Generar código único automáticamente
            $rack = DB::table('racks')->where('idRack', $request->rack_id)->first();
            $codigoUnico = $rack->nombre . '-' . $request->codigo;

            // Crear la ubicación
            $ubicacionId = DB::table('rack_ubicaciones')->insertGetId([
                'rack_id' => $request->rack_id,
                'codigo' => $request->codigo,
                'codigo_unico' => $codigoUnico,
                'nivel' => $request->nivel,
                'posicion' => $request->posicion,
                'capacidad_maxima' => $request->capacidad_maxima,
                'estado_ocupacion' => $request->estado_ocupacion,
                'articulo_id' => null, // Siempre null al crear
                'cantidad_actual' => 0, // Siempre 0 al crear
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ubicación creada exitosamente',
                'data' => [
                    'id' => $ubicacionId,
                    'codigo_unico' => $codigoUnico
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear ubicación: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }


    public function actualizarProducto(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'ubicacion_id' => 'required|exists:rack_ubicaciones,idRackUbicacion',
                'articulo_id' => 'required|exists:articulos,idArticulos',
                'cantidad' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $ubicacionId = $request->ubicacion_id;
            $articuloId = $request->articulo_id;
            $nuevaCantidad = $request->cantidad;

            // Buscar la ubicación
            $ubicacion = DB::table('rack_ubicaciones')
                ->where('idRackUbicacion', $ubicacionId)
                ->first();

            if (!$ubicacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ubicación no encontrada'
                ], 404);
            }

            // Buscar el registro en rack_ubicacion_articulos
            $productoUbicacion = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $ubicacionId)
                ->where('articulo_id', $articuloId)
                ->first();

            if (!$productoUbicacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'El producto no se encuentra en esta ubicación'
                ], 404);
            }

            // Obtener información del artículo
            $articulo = DB::table('articulos')
                ->where('idArticulos', $articuloId)
                ->first();

            if (!$articulo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Artículo no encontrado'
                ], 404);
            }

            // Guardar la cantidad anterior para el historial
            $cantidadAnterior = $productoUbicacion->cantidad;
            $diferencia = $nuevaCantidad - $cantidadAnterior;

            // ✅ NUEVO: Actualizar stock_total en articulos
            $nuevoStockTotal = $articulo->stock_total + $diferencia;

            if ($nuevoStockTotal < 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede establecer una cantidad que resulte en stock negativo'
                ], 422);
            }

            DB::table('articulos')
                ->where('idArticulos', $articuloId)
                ->update([
                    'stock_total' => $nuevoStockTotal,
                    'updated_at' => now()
                ]);

            // Actualizar la cantidad en rack_ubicacion_articulos
            DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $ubicacionId)
                ->where('articulo_id', $articuloId)
                ->update([
                    'cantidad' => $nuevaCantidad,
                    'updated_at' => now()
                ]);

            // ✅ NUEVO: Obtener el último cliente general asociado a este artículo
            $ultimoClienteGeneral = DB::table('inventario_ingresos_clientes')
                ->where('articulo_id', $articuloId)
                ->where('tipo_ingreso', 'ajuste')
                ->orderBy('created_at', 'desc')
                ->first();

            // ✅ NUEVO: Registrar en inventario_ingresos_clientes solo si hay diferencia
            if ($diferencia != 0) {
                DB::table('inventario_ingresos_clientes')->insert([
                    'articulo_id' => $articuloId,
                    'cliente_general_id' => $ultimoClienteGeneral ? $ultimoClienteGeneral->cliente_general_id : null,
                    'tipo_ingreso' => 'ajuste',
                    'cantidad' => $diferencia, // ✅ Solo la diferencia (positiva o negativa)
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Obtener solo el nombre del artículo para el historial
            $nombreArticulo = DB::table('articulos')
                ->where('idArticulos', $articuloId)
                ->value('nombre') ?? 'Producto';

            // Obtener información del rack para el historial
            $rack = DB::table('racks')
                ->where('idRack', $ubicacion->rack_id)
                ->first();

            // Registrar en el historial (rack_movimientos)
            DB::table('rack_movimientos')->insert([
                'articulo_id' => $articuloId,
                'ubicacion_origen_id' => $ubicacionId,
                'ubicacion_destino_id' => $ubicacionId,
                'rack_origen_id' => $ubicacion->rack_id,
                'rack_destino_id' => $ubicacion->rack_id,
                'cantidad' => $nuevaCantidad,
                'tipo_movimiento' => 'ajuste',
                'usuario_id' => auth()->id() ?? 1,
                'observaciones' => "Actualización de cantidad: {$cantidadAnterior} → {$nuevaCantidad} - {$nombreArticulo}",
                'codigo_ubicacion_origen' => $ubicacion->codigo,
                'codigo_ubicacion_destino' => $ubicacion->codigo,
                'nombre_rack_origen' => $rack->nombre ?? 'N/A',
                'nombre_rack_destino' => $rack->nombre ?? 'N/A',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Actualizar el estado de ocupación de la ubicación
            $this->actualizarEstadoOcupacion($ubicacionId);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cantidad actualizada exitosamente',
                'data' => [
                    'cantidad_anterior' => $cantidadAnterior,
                    'cantidad_nueva' => $nuevaCantidad,
                    'diferencia' => $diferencia,
                    'stock_anterior' => $articulo->stock_total,
                    'stock_nuevo' => $nuevoStockTotal,
                    'cliente_general_id' => $ultimoClienteGeneral ? $ultimoClienteGeneral->cliente_general_id : null,
                    'ubicacion_codigo' => $ubicacion->codigo
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar producto en ubicación: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el producto: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Eliminar un producto específico de una ubicación
     */
    public function eliminarProducto(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'ubicacion_id' => 'required|exists:rack_ubicaciones,idRackUbicacion',
                'articulo_id' => 'required|exists:articulos,idArticulos'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $ubicacionId = $request->ubicacion_id;
            $articuloId = $request->articulo_id;

            // Buscar la ubicación
            $ubicacion = DB::table('rack_ubicaciones')
                ->where('idRackUbicacion', $ubicacionId)
                ->first();

            if (!$ubicacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ubicación no encontrada'
                ], 404);
            }

            // Buscar el registro en rack_ubicacion_articulos
            $productoUbicacion = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $ubicacionId)
                ->where('articulo_id', $articuloId)
                ->first();

            if (!$productoUbicacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'El producto no se encuentra en esta ubicación'
                ], 404);
            }

            // Obtener información del artículo
            $articulo = DB::table('articulos')
                ->where('idArticulos', $articuloId)
                ->first();

            // Guardar información para el historial
            $cantidadEliminada = $productoUbicacion->cantidad;
            $nombreArticulo = $articulo->nombre ?? 'Producto';

            // ✅ NUEVO: Obtener el último cliente general asociado a este artículo
            $ultimoClienteGeneral = DB::table('inventario_ingresos_clientes')
                ->where('articulo_id', $articuloId)
                ->where('tipo_ingreso', 'ajuste')
                ->orderBy('created_at', 'desc')
                ->first();

            // ✅ NUEVO: Actualizar stock_total en articulos (restar la cantidad eliminada)
            $nuevoStockTotal = $articulo->stock_total - $cantidadEliminada;

            if ($nuevoStockTotal < 0) {
                $nuevoStockTotal = 0; // No permitir stock negativo
            }

            DB::table('articulos')
                ->where('idArticulos', $articuloId)
                ->update([
                    'stock_total' => $nuevoStockTotal,
                    'updated_at' => now()
                ]);

            // ✅ NUEVO: Registrar en inventario_ingresos_clientes (cantidad negativa)
            DB::table('inventario_ingresos_clientes')->insert([
                'articulo_id' => $articuloId,
                'cliente_general_id' => $ultimoClienteGeneral ? $ultimoClienteGeneral->cliente_general_id : null,
                'tipo_ingreso' => 'ajuste',
                'cantidad' => -$cantidadEliminada, // ✅ Cantidad negativa para salida
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Eliminar el registro de la tabla rack_ubicacion_articulos
            DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $ubicacionId)
                ->where('articulo_id', $articuloId)
                ->delete();

            // Obtener información del rack para el historial
            $rack = DB::table('racks')
                ->where('idRack', $ubicacion->rack_id)
                ->first();

            // Registrar en el historial (rack_movimientos)
            DB::table('rack_movimientos')->insert([
                'articulo_id' => $articuloId,
                'ubicacion_origen_id' => $ubicacionId,
                'ubicacion_destino_id' => null,
                'rack_origen_id' => $ubicacion->rack_id,
                'rack_destino_id' => null,
                'cantidad' => 0,
                'tipo_movimiento' => 'salida',
                'usuario_id' => auth()->id() ?? 1,
                'observaciones' => "Producto eliminado: {$nombreArticulo} ({$cantidadEliminada} unidades)",
                'codigo_ubicacion_origen' => $ubicacion->codigo,
                'codigo_ubicacion_destino' => null,
                'nombre_rack_origen' => $rack->nombre ?? 'N/A',
                'nombre_rack_destino' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Actualizar el estado de ocupación de la ubicación
            $this->actualizarEstadoOcupacion($ubicacionId);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado exitosamente de la ubicación',
                'data' => [
                    'producto_eliminado' => $nombreArticulo,
                    'cantidad_eliminada' => $cantidadEliminada,
                    'stock_anterior' => $articulo->stock_total,
                    'stock_nuevo' => $nuevoStockTotal,
                    'cliente_general_id' => $ultimoClienteGeneral ? $ultimoClienteGeneral->cliente_general_id : null,
                    'ubicacion_codigo' => $ubicacion->codigo
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar producto de ubicación: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método auxiliar para actualizar el estado de ocupación de una ubicación
     */
    private function actualizarEstadoOcupacion($ubicacionId)
    {
        // Buscar la ubicación
        $ubicacion = DB::table('rack_ubicaciones')
            ->where('idRackUbicacion', $ubicacionId)
            ->first();

        if (!$ubicacion) return;

        // Calcular cantidad total en la ubicación
        $cantidadTotal = DB::table('rack_ubicacion_articulos')
            ->where('rack_ubicacion_id', $ubicacionId)
            ->sum('cantidad');

        // Calcular porcentaje de ocupación
        $porcentajeOcupacion = $ubicacion->capacidad_maxima > 0
            ? ($cantidadTotal / $ubicacion->capacidad_maxima) * 100
            : 0;

        // Determinar el estado basado en el porcentaje
        $nuevoEstado = 'vacio';

        if ($cantidadTotal > 0) {
            if ($porcentajeOcupacion <= 25) {
                $nuevoEstado = 'bajo';
            } elseif ($porcentajeOcupacion <= 50) {
                $nuevoEstado = 'medio';
            } elseif ($porcentajeOcupacion <= 75) {
                $nuevoEstado = 'alto';
            } else {
                $nuevoEstado = 'muy_alto';
            }
        }

        // Actualizar el estado de la ubicación
        DB::table('rack_ubicaciones')
            ->where('idRackUbicacion', $ubicacionId)
            ->update([
                'estado_ocupacion' => $nuevoEstado,
                'updated_at' => now()
            ]);
    }


    // Agrega este método al controlador para listar clientes generales
    public function listarClientesGenerales()
    {
        try {
            $clientes = DB::table('clientegeneral')
                ->where('estado', 1)
                ->select('idClienteGeneral as id', 'descripcion')
                ->orderBy('descripcion')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $clientes
            ]);
        } catch (\Exception $e) {
            Log::error('Error al listar clientes generales: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar clientes generales'
            ], 500);
        }
    }
}
