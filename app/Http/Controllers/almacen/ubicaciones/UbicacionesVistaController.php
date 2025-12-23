<?php

namespace App\Http\Controllers\almacen\ubicaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str; // ¡Agrega esta línea!

class UbicacionesVistaController extends Controller
{
    public function vistaAlmacen()
    {
        // Obtener las sedes disponibles desde la tabla sucursal
        $sedes = DB::table('sucursal')
            ->select('nombre')
            ->where('estado', 1) // Asumiendo que 1 = activo
            ->orderBy('nombre')
            ->pluck('nombre')
            ->toArray(); // Convertir a array

        // Agregar opción para ambas sedes al principio
        array_unshift($sedes, 'Seleccionar todas las sedes');
        return view('almacen.ubicaciones.vista-almacen', compact('sedes'));
    }

    public function generarQrPorNombre(string $nombre, Request $request)
    {
        $nombre = trim($nombre);

        if ($nombre === '') {
            return response()->json(['message' => 'Nombre inválido'], 422);
        }

        // Determinar qué ruta usar basado en el parámetro
        $ruta = $request->get('ruta', 'spark'); // Por defecto 'spark'

        if ($ruta === 'spark') {
            $qrContent = url("/almacen/ubicaciones/qr/spark/{$nombre}");
        } else {
            $qrContent = url("/almacen/ubicaciones/qr/vista/{$nombre}");
        }

        $qr = QrCode::format('svg')
            ->size(350)
            ->margin(2)
            ->errorCorrection('M')
            ->generate($qrContent);

        $fileName = 'qr-ubicacion-' . Str::slug($nombre) . '.svg';

        if ($request->has('download')) {
            return response($qr, 200)
                ->header('Content-Type', 'image/svg+xml')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        }

        return response($qr, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    }


    public function vistaQR(string $nombre)
    {
        $nombre = trim($nombre);

        // Buscar la ubicación por código único o código normal
        $ubicacion = DB::table('rack_ubicaciones as ru')
            ->select(
                'ru.*',
                'r.nombre as rack_nombre',
                'r.sede',
                'r.tipo_rack',
                'r.filas',
                'r.columnas'
            )
            ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
            ->where(function ($query) use ($nombre) {
                $query->where('ru.codigo_unico', $nombre)
                    ->orWhere('ru.codigo', $nombre);
            })
            ->where('r.estado', 'activo')
            ->first();

        if (!$ubicacion) {
            return view('almacen.ubicaciones.vista_qr', [
                'error' => true,
                'mensaje' => "Ubicación '{$nombre}' no encontrada",
                'codigo' => $nombre
            ]);
        }

        // ✅ CORRECCIÓN: Obtener CAJAS con manejo correcto de repuestos
        $cajas = DB::table('cajas as cj')
            ->select(
                // Datos de la CAJA
                'cj.idCaja',
                'cj.nombre as nombre_caja',
                'cj.cantidad_actual',
                'cj.capacidad',
                'cj.estado as estado_caja',
                'cj.es_custodia',
                'cj.fecha_entrada',

                // Datos del ARTÍCULO dentro de la caja
                'a.idArticulos',
                'a.nombre as articulo_nombre',
                'a.codigo_repuesto',
                'a.stock_total',
                'ta.nombre as tipo_articulo',
                'ta.idTipoArticulo',
                DB::raw('COALESCE(cat.nombre, "Sin categoría") as categoria'),

                // ✅ Solo para no-repuestos
                'm.nombre as modelo_nombre',
                'mar.nombre as marca_nombre'
            )
            ->leftJoin('articulos as a', 'cj.idArticulo', '=', 'a.idArticulos')
            ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')

            // ✅ CORRECCIÓN: LEFT JOIN condicional para modelo (solo NO repuestos)
            ->leftJoin('modelo as m', function ($join) {
                $join->on('a.idModelo', '=', 'm.idModelo')
                    ->where('a.idTipoArticulo', '!=', 2); // Solo para NO repuestos
            })

            ->leftJoin('marca as mar', 'm.idMarca', '=', 'mar.idMarca')
            ->leftJoin('categoria as cat', 'm.idCategoria', '=', 'cat.idCategoria')
            ->where('cj.idubicaciones_rack', $ubicacion->idRackUbicacion)
            ->get();

        // ✅ CORRECCIÓN: Obtener modelos para REPUESTOS dentro de cajas
        $repuestosEnCajasIds = $cajas->where('idTipoArticulo', 2)->pluck('idArticulos')->unique();
        $modelosRepuestosEnCajas = [];

        if ($repuestosEnCajasIds->isNotEmpty()) {
            $modelosDataCajas = DB::table('articulo_modelo as am')
                ->join('modelo as m', 'am.modelo_id', '=', 'm.idModelo')
                ->leftJoin('categoria as c', 'm.idCategoria', '=', 'c.idCategoria')
                ->whereIn('am.articulo_id', $repuestosEnCajasIds)
                ->select(
                    'am.articulo_id',
                    'm.idModelo',
                    'm.nombre as modelo_nombre',
                    'c.nombre as categoria'
                )
                ->get();

            foreach ($modelosDataCajas as $modelo) {
                $modelosRepuestosEnCajas[$modelo->articulo_id][] = [
                    'id' => $modelo->idModelo,
                    'nombre' => $modelo->modelo_nombre,
                    'categoria' => $modelo->categoria
                ];
            }
        }

        // ✅ CORRECCIÓN: Transformar cajas incluyendo modelos para repuestos
        $cajasTransformadas = $cajas->map(function ($caja) use ($modelosRepuestosEnCajas) {
            $articuloInfo = null;

            if ($caja->idArticulos) {
                $articuloInfo = [
                    'id' => $caja->idArticulos,
                    'nombre' => $caja->articulo_nombre,
                    'tipo_articulo' => $caja->tipo_articulo,
                    'categoria' => $caja->categoria,
                    'codigo_repuesto' => $caja->codigo_repuesto,
                    'stock_total' => $caja->stock_total,
                    'idTipoArticulo' => $caja->idTipoArticulo,
                    'es_repuesto' => $caja->idTipoArticulo == 2,
                    'modelo_nombre' => $caja->modelo_nombre,
                    'marca_nombre' => $caja->marca_nombre,
                    'modelos' => [] // Inicializar array de modelos
                ];

                // ✅ Si es repuesto, asignar modelos
                if ($caja->idTipoArticulo == 2) {
                    $articuloInfo['modelos'] = $modelosRepuestosEnCajas[$caja->idArticulos] ?? [];
                    $articuloInfo['tiene_multiple_modelos'] = count($articuloInfo['modelos']) > 1;

                    // Si hay modelos, usar el primero como modelo principal
                    if (!empty($articuloInfo['modelos'])) {
                        $articuloInfo['modelo_nombre'] = $articuloInfo['modelos'][0]['nombre'];
                        $articuloInfo['categoria'] = $articuloInfo['modelos'][0]['categoria'] ?? $caja->categoria;
                    }
                }
            }

            return [
                'caja' => [
                    'id' => $caja->idCaja,
                    'nombre' => $caja->nombre_caja ?: 'Caja',
                    'cantidad_actual' => $caja->cantidad_actual,
                    'capacidad' => $caja->capacidad,
                    'estado' => $caja->estado_caja,
                    'porcentaje_llenado' => $caja->capacidad > 0
                        ? round(($caja->cantidad_actual / $caja->capacidad) * 100, 2)
                        : 0,
                    'es_custodia' => $caja->es_custodia,
                    'fecha_entrada' => $caja->fecha_entrada
                ],
                'contenido' => $articuloInfo ?: ['nombre' => 'Vacía', 'tipo_articulo' => null, 'modelos' => []]
            ];
        });

        // ✅ Calcular estadísticas (solo cajas)
        $totalCajas = $cajas->count();
        $totalArticulosEnCajas = $cajas->sum('cantidad_actual');
        $totalItems = $totalArticulosEnCajas; // Solo artículos en cajas

        // ✅ Obtener categorías (solo de artículos dentro de cajas)
        $categorias = collect();

        foreach ($cajasTransformadas as $cajaItem) {
            if ($cajaItem['contenido']['categoria'] ?? null) {
                $categorias->push($cajaItem['contenido']['categoria']);
            }

            // ✅ También categorías de modelos de repuestos en cajas
            if (!empty($cajaItem['contenido']['modelos'])) {
                foreach ($cajaItem['contenido']['modelos'] as $modelo) {
                    if ($modelo['categoria'] && $modelo['categoria'] !== 'Sin categoría') {
                        $categorias->push($modelo['categoria']);
                    }
                }
            }
        }

        $categoriasUnicas = $categorias->unique()->values();

        // ✅ Obtener tipos de artículos (solo de artículos en cajas)
        $tiposArticulos = collect();

        foreach ($cajasTransformadas as $cajaItem) {
            if ($cajaItem['contenido']['tipo_articulo'] ?? null) {
                $tiposArticulos->push($cajaItem['contenido']['tipo_articulo']);
            }
        }

        $tiposUnicos = $tiposArticulos->unique()->values();

        // ✅ Obtener marcas únicas (de artículos en cajas)
        $marcas = collect();

        foreach ($cajasTransformadas as $cajaItem) {
            if ($cajaItem['contenido']['marca_nombre'] ?? null) {
                $marcas->push($cajaItem['contenido']['marca_nombre']);
            }
        }

        $marcasUnicas = $marcas->unique()->values();

        // ✅ Obtener historial de movimientos
        $historial = DB::table('rack_movimientos')
            ->where('ubicacion_origen_id', $ubicacion->idRackUbicacion)
            ->orWhere('ubicacion_destino_id', $ubicacion->idRackUbicacion)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ✅ Formatear datos para la vista (sin artículos sueltos)
        $datosUbicacion = [
            'id' => $ubicacion->idRackUbicacion,
            'codigo' => $ubicacion->codigo,
            'codigo_unico' => $ubicacion->codigo_unico,
            'nivel' => $ubicacion->nivel,
            'posicion' => $ubicacion->posicion,
            'estado_ocupacion' => $ubicacion->estado_ocupacion,
            'rack_nombre' => $ubicacion->rack_nombre,
            'sede' => $ubicacion->sede,
            'tipo_rack' => $ubicacion->tipo_rack,
            'total_cajas' => $totalCajas,
            'total_articulos_en_cajas' => $totalArticulosEnCajas,
            'total_items' => $totalItems,
            'categorias' => $categoriasUnicas,
            'tipos_articulos' => $tiposUnicos,
            'marcas' => $marcasUnicas,
            'cajas' => $cajasTransformadas,
            'historial' => $historial,
            'fecha_actualizacion' => $ubicacion->updated_at,
        ];

        return view('almacen.ubicaciones.vista_qr', [
            'ubicacion' => $datosUbicacion,
            'error' => false
        ]);
    }

    public function vistaSparkQR(string $nombre)
    {
        $nombre = trim($nombre);

        // Buscar la ubicación por código único o código normal
        $ubicacion = DB::table('rack_ubicaciones as ru')
            ->select(
                'ru.*',
                'r.nombre as rack_nombre',
                'r.sede',
                'r.tipo_rack',
                'r.filas',
                'r.columnas',
                'r.idRack'
            )
            ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
            ->where(function ($query) use ($nombre) {
                $query->where('ru.codigo_unico', $nombre)
                    ->orWhere('ru.codigo', $nombre);
            })
            ->where('r.estado', 'activo')
            ->first();

        if (!$ubicacion) {
            return view('almacen.ubicaciones.vista_spark_qr', [
                'error' => true,
                'mensaje' => "Ubicación '{$nombre}' no encontrada",
                'codigo' => $nombre
            ]);
        }

        // ✅ CORRECCIÓN: Obtener todos los productos/artículos/custodias con manejo correcto de repuestos
        $productosUbicacion = DB::table('racks as r')
            ->join('rack_ubicaciones as ru', 'r.idRack', '=', 'ru.rack_id')
            ->leftJoin('rack_ubicacion_articulos as rua', 'ru.idRackUbicacion', '=', 'rua.rack_ubicacion_id')
            ->leftJoin('articulos as a', 'rua.articulo_id', '=', 'a.idArticulos')
            ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')

            // ✅ IMPORTANTE: Para repuestos, usar articulo_modelo. Para otros, usar idModelo directo
            ->leftJoin('articulo_modelo as am', function ($join) {
                $join->on('a.idArticulos', '=', 'am.articulo_id')
                    ->where('a.idTipoArticulo', '=', 2); // Solo para repuestos
            })

            // ✅ Para repuestos: modelo desde articulo_modelo
            // ✅ Para otros: modelo desde articulos.idModelo
            ->leftJoin('modelo as m', function ($join) {
                $join->on(function ($query) {
                    // Para repuestos: usar el modelo de articulo_modelo
                    $query->where('a.idTipoArticulo', '=', 2)
                        ->on('am.modelo_id', '=', 'm.idModelo');
                })->orOn(function ($query) {
                    // Para otros tipos: usar el modelo directo
                    $query->where('a.idTipoArticulo', '!=', 2)
                        ->on('a.idModelo', '=', 'm.idModelo');
                });
            })

            ->leftJoin('categoria as c', 'm.idCategoria', '=', 'c.idCategoria')
            ->leftJoin('custodias as cust', 'rua.custodia_id', '=', 'cust.id')
            ->leftJoin('modelo as m_cust', 'cust.idModelo', '=', 'm_cust.idModelo')
            ->leftJoin('categoria as c_cust', 'm_cust.idCategoria', '=', 'c_cust.idCategoria')
            ->leftJoin('marca as mar_cust', 'cust.idMarca', '=', 'mar_cust.idMarca')
            ->leftJoin('tickets as t_cust', 'cust.numero_ticket', '=', 't_cust.numero_ticket')
            ->leftJoin('clientegeneral as cg_cust', 't_cust.idClienteGeneral', '=', 'cg_cust.idClienteGeneral')
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
                'rua.idRackUbicacionArticulo',
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

                // ✅ Información del modelo
                'a.idModelo as articulo_modelo_id', // Solo válido para NO repuestos
                'am.modelo_id as repuesto_modelo_id', // Solo válido para repuestos
                'm.idModelo as modelo_id_final',
                'm.nombre as modelo_nombre',

                DB::raw('COALESCE(c.nombre, "Sin categoría") as categoria'),
                'rua.cantidad',
                'rua.custodia_id',
                'rua.cliente_general_id',
                'cg.descripcion as cliente_general_nombre',
                'cust.codigocustodias',
                'cust.serie',
                'cust.idMarca',
                'cust.idModelo',
                'cust.numero_ticket',
                'c_cust.nombre as categoria_custodia',
                'mar_cust.nombre as marca_nombre',
                'm_cust.nombre as modelo_nombre',
                'cg_cust.idClienteGeneral as cliente_general_id_custodia',
                'cg_cust.descripcion as cliente_general_nombre_custodia'
            )
            ->where('ru.idRackUbicacion', $ubicacion->idRackUbicacion)
            ->where('r.estado', 'activo')
            ->get();

        // ✅ CORRECCIÓN: Obtener TODOS los modelos para los repuestos encontrados (artículos sueltos)
        $repuestosIds = $productosUbicacion->where('idTipoArticulo', 2)->pluck('idArticulos')->unique();
        $todosModelosRepuestos = [];

        if ($repuestosIds->isNotEmpty()) {
            $modelosData = DB::table('articulo_modelo as am')
                ->join('modelo as m', 'am.modelo_id', '=', 'm.idModelo')
                ->leftJoin('marca as mar', 'm.idMarca', '=', 'mar.idMarca')
                ->leftJoin('categoria as cat', 'm.idCategoria', '=', 'cat.idCategoria')
                ->whereIn('am.articulo_id', $repuestosIds)
                ->select(
                    'am.articulo_id',
                    'm.idModelo',
                    'm.nombre as modelo_nombre',
                    'mar.nombre as marca_nombre',
                    'cat.nombre as categoria_nombre'
                )
                ->orderBy('m.nombre')
                ->get();

            // Organizar por artículo_id
            foreach ($modelosData as $modelo) {
                $todosModelosRepuestos[$modelo->articulo_id][] = [
                    'id' => $modelo->idModelo,
                    'nombre' => $modelo->modelo_nombre,
                    'marca' => $modelo->marca_nombre,
                    'categoria' => $modelo->categoria_nombre
                ];
            }
        }

        // ✅ CORRECCIÓN: Obtener CAJAS con manejo correcto de repuestos
        $cajas = DB::table('cajas as cj')
            ->select(
                // Datos de la CAJA
                'cj.idCaja',
                'cj.nombre as nombre_caja',
                'cj.cantidad_actual',
                'cj.capacidad',
                'cj.estado as estado_caja',
                'cj.es_custodia',
                'cj.fecha_entrada',

                // Datos del ARTÍCULO dentro de la caja
                'a.idArticulos',
                'a.nombre as articulo_nombre',
                'a.codigo_repuesto',
                'a.stock_total',
                'ta.nombre as tipo_articulo',
                'ta.idTipoArticulo',
                DB::raw('COALESCE(cat.nombre, "Sin categoría") as categoria'),

                // ✅ Solo para no-repuestos
                'm.nombre as modelo_nombre',
                'mar.nombre as marca_nombre'
            )
            ->leftJoin('articulos as a', 'cj.idArticulo', '=', 'a.idArticulos')
            ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')

            // ✅ CORRECCIÓN: LEFT JOIN condicional para modelo (solo NO repuestos)
            ->leftJoin('modelo as m', function ($join) {
                $join->on('a.idModelo', '=', 'm.idModelo')
                    ->where('a.idTipoArticulo', '!=', 2); // Solo para NO repuestos
            })

            ->leftJoin('marca as mar', 'm.idMarca', '=', 'mar.idMarca')
            ->leftJoin('categoria as cat', 'm.idCategoria', '=', 'cat.idCategoria')
            ->where('cj.idubicaciones_rack', $ubicacion->idRackUbicacion)
            ->get();

        // ✅ CORRECCIÓN: Obtener modelos para REPUESTOS dentro de cajas
        $repuestosEnCajasIds = $cajas->where('idTipoArticulo', 2)->pluck('idArticulos')->unique();
        $todosModelosRepuestosEnCajas = [];

        if ($repuestosEnCajasIds->isNotEmpty()) {
            $modelosDataCajas = DB::table('articulo_modelo as am')
                ->join('modelo as m', 'am.modelo_id', '=', 'm.idModelo')
                ->leftJoin('marca as mar', 'm.idMarca', '=', 'mar.idMarca')
                ->leftJoin('categoria as cat', 'm.idCategoria', '=', 'cat.idCategoria')
                ->whereIn('am.articulo_id', $repuestosEnCajasIds)
                ->select(
                    'am.articulo_id',
                    'm.idModelo',
                    'm.nombre as modelo_nombre',
                    'mar.nombre as marca_nombre',
                    'cat.nombre as categoria_nombre'
                )
                ->orderBy('m.nombre')
                ->get();

            // Organizar por artículo_id
            foreach ($modelosDataCajas as $modelo) {
                $todosModelosRepuestosEnCajas[$modelo->articulo_id][] = [
                    'id' => $modelo->idModelo,
                    'nombre' => $modelo->modelo_nombre,
                    'marca' => $modelo->marca_nombre,
                    'categoria' => $modelo->categoria_nombre
                ];
            }
        }

        // ✅ CORRECCIÓN: Transformar productos de la ubicación (incluyendo custodias) CON MODELOS
        $productosAgrupados = collect();
        $articulosValidos = $productosUbicacion->filter(function ($art) {
            return $art->idArticulos !== null || $art->custodia_id !== null;
        });

        if ($articulosValidos->isNotEmpty()) {
            foreach ($articulosValidos as $art) {
                // ✅ SI ES CUSTODIA
                if ($art->custodia_id) {
                    $productosAgrupados->push([
                        'id' => $art->idArticulos,
                        'idRackUbicacionArticulo' => $art->idRackUbicacionArticulo,
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
                        'cliente_general_nombre' => $art->cliente_general_nombre_custodia ?: 'Sin cliente',
                        'modelos' => [] // Custodias no tienen modelos múltiples
                    ]);
                } else {
                    // ✅ SI ES PRODUCTO NORMAL
                    $mostrandoCodigoRepuesto = ($art->idTipoArticulo == 2 && !empty($art->codigo_repuesto));

                    // ✅ Obtener modelos según el tipo de artículo
                    $modelosDelArticulo = [];

                    if ($art->idTipoArticulo == 2) {
                        // Para repuestos: obtener todos los modelos de la tabla articulo_modelo
                        $modelosDelArticulo = $todosModelosRepuestos[$art->idArticulos] ?? [];
                    } else {
                        // Para otros tipos: usar el modelo directo (si existe)
                        if ($art->modelo_nombre) {
                            $modelosDelArticulo = [[
                                'id' => $art->modelo_id_final,
                                'nombre' => $art->modelo_nombre,
                                'marca' => null,
                                'categoria' => $art->categoria
                            ]];
                        }
                    }

                    $productosAgrupados->push([
                        'id' => $art->idArticulos,
                        'idRackUbicacionArticulo' => $art->idRackUbicacionArticulo,
                        'nombre' => $art->producto,
                        'nombre_original' => $art->nombre_original,
                        'codigo_repuesto' => $art->codigo_repuesto,
                        'cantidad' => $art->cantidad,
                        'stock_total' => $art->stock_total,
                        'tipo_articulo' => $art->tipo_articulo,
                        'idTipoArticulo' => $art->idTipoArticulo,
                        'categoria' => $art->categoria,

                        // ✅ Información del modelo
                        'modelo_id' => $art->idTipoArticulo != 2 ? $art->articulo_modelo_id : null,
                        'modelo_nombre' => $art->idTipoArticulo != 2 ? $art->modelo_nombre : null,

                        // ✅ Para repuestos: todos los modelos
                        'modelos' => $modelosDelArticulo,
                        'tiene_multiple_modelos' => $art->idTipoArticulo == 2 && count($modelosDelArticulo) > 1,

                        'custodia_id' => null,
                        'es_repuesto' => $art->idTipoArticulo == 2,
                        'mostrando_codigo_repuesto' => $mostrandoCodigoRepuesto,
                        'cliente_general_id' => $art->cliente_general_id,
                        'cliente_general_nombre' => $art->cliente_general_nombre ?: 'Sin cliente'
                    ]);
                }
            }
        }

        // ✅ CORRECCIÓN: Transformar cajas para mostrar artículos dentro CON MODELOS
        $cajasTransformadas = $cajas->map(function ($caja) use ($todosModelosRepuestosEnCajas) {
            $articuloInfo = null;

            if ($caja->idArticulos) {
                $articuloInfo = [
                    'id' => $caja->idArticulos,
                    'nombre' => $caja->articulo_nombre,
                    'tipo_articulo' => $caja->tipo_articulo,
                    'categoria' => $caja->categoria,
                    'codigo_repuesto' => $caja->codigo_repuesto,
                    'stock_total' => $caja->stock_total,
                    'idTipoArticulo' => $caja->idTipoArticulo,
                    'es_repuesto' => $caja->idTipoArticulo == 2,
                    'modelo_nombre' => $caja->modelo_nombre,
                    'marca_nombre' => $caja->marca_nombre,
                    'modelos' => [], // Inicializar array de modelos
                    'tiene_multiple_modelos' => false
                ];

                // ✅ Si es repuesto, asignar modelos
                if ($caja->idTipoArticulo == 2) {
                    $articuloInfo['modelos'] = $todosModelosRepuestosEnCajas[$caja->idArticulos] ?? [];
                    $articuloInfo['tiene_multiple_modelos'] = count($articuloInfo['modelos']) > 1;

                    // Si hay modelos, usar el primero como modelo principal
                    if (!empty($articuloInfo['modelos'])) {
                        $articuloInfo['modelo_nombre'] = $articuloInfo['modelos'][0]['nombre'];
                        $articuloInfo['categoria'] = $articuloInfo['modelos'][0]['categoria'] ?? $caja->categoria;
                        $articuloInfo['marca_nombre'] = $articuloInfo['modelos'][0]['marca'] ?? $caja->marca_nombre;
                    }
                }
            }

            return [
                'caja' => [
                    'id' => $caja->idCaja,
                    'nombre' => $caja->nombre_caja ?: 'Caja',
                    'cantidad_actual' => $caja->cantidad_actual,
                    'capacidad' => $caja->capacidad,
                    'estado' => $caja->estado_caja,
                    'porcentaje_llenado' => $caja->capacidad > 0
                        ? round(($caja->cantidad_actual / $caja->capacidad) * 100, 2)
                        : 0,
                    'es_custodia' => $caja->es_custodia,
                    'fecha_entrada' => $caja->fecha_entrada
                ],
                'contenido' => $articuloInfo ?: [
                    'nombre' => 'Vacía',
                    'tipo_articulo' => null,
                    'modelos' => [],
                    'tiene_multiple_modelos' => false
                ]
            ];
        });

        // ✅ Calcular estadísticas
        $totalArticulosSueltos = $productosAgrupados->sum('cantidad');
        $totalCajas = $cajas->count();
        $totalArticulosEnCajas = $cajas->sum('cantidad_actual');
        $totalItems = $totalArticulosSueltos + $totalArticulosEnCajas;

        // ✅ Calcular estado basado en capacidad
        $estadoUbicacion = $this->calcularEstadoPorCapacidad($totalArticulosSueltos, $ubicacion->capacidad_maxima);

        // ✅ Obtener categorías y tipos únicos (incluyendo categorías de modelos)
        $categoriasUnicas = collect();

        // Categorías de productos sueltos
        foreach ($productosAgrupados as $producto) {
            if ($producto['categoria'] && $producto['categoria'] !== 'Sin categoría') {
                $categoriasUnicas->push($producto['categoria']);
            }

            // ✅ También categorías de modelos de repuestos
            if (!empty($producto['modelos'])) {
                foreach ($producto['modelos'] as $modelo) {
                    if ($modelo['categoria'] && $modelo['categoria'] !== 'Sin categoría') {
                        $categoriasUnicas->push($modelo['categoria']);
                    }
                }
            }
        }

        // Categorías de artículos dentro de cajas
        foreach ($cajasTransformadas as $cajaItem) {
            if ($cajaItem['contenido']['categoria'] ?? null) {
                $categoriasUnicas->push($cajaItem['contenido']['categoria']);
            }

            // ✅ También categorías de modelos de repuestos en cajas
            if (!empty($cajaItem['contenido']['modelos'])) {
                foreach ($cajaItem['contenido']['modelos'] as $modelo) {
                    if ($modelo['categoria'] && $modelo['categoria'] !== 'Sin categoría') {
                        $categoriasUnicas->push($modelo['categoria']);
                    }
                }
            }
        }

        $categoriasUnicas = $categoriasUnicas->unique()->values();

        $tiposUnicos = $productosAgrupados->pluck('tipo_articulo')
            ->merge($cajasTransformadas->pluck('contenido.tipo_articulo'))
            ->filter(fn($tipo) => $tipo && trim($tipo) !== '')
            ->unique()
            ->values();

        // ✅ Obtener historial de movimientos de esta ubicación
        $historial = DB::table('rack_movimientos')
            ->where('ubicacion_origen_id', $ubicacion->idRackUbicacion)
            ->orWhere('ubicacion_destino_id', $ubicacion->idRackUbicacion)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ✅ Normalizar tipos de movimiento y limpiar observaciones
        $historialTransformado = $historial->map(function ($mov) {
            $tipoNormalizado = match (strtolower($mov->tipo_movimiento)) {
                'entrada', 'ingreso' => 'ingreso',
                'salida' => 'salida',
                'reubicacion', 'reubicación' => 'reubicacion',
                'reubicacion_custodia', 'reubicación custodia', 'reubicación_custodia' => 'reubicacion_custodia',
                'ajuste' => 'ajuste',
                default => strtolower($mov->tipo_movimiento)
            };

            $observacionesLimpias = $mov->observaciones;
            if ($mov->observaciones) {
                $observacionesLimpias = preg_replace('/Producto:\s*\d+\s*-?\s*/', '', $mov->observaciones);
                $observacionesLimpias = preg_replace('/Artículo:\s*\d+\s*-?\s*/', '', $mov->observaciones);
                $observacionesLimpias = preg_replace('/\s*-\s*$/', '', $observacionesLimpias);
                $observacionesLimpias = preg_replace('/^\s*-\s*/', '', $observacionesLimpias);
                $observacionesLimpias = trim($observacionesLimpias);
            }

            return [
                'fecha' => $mov->created_at,
                'producto' => 'Artículo Movido',
                'cantidad' => $mov->cantidad,
                'tipo' => $tipoNormalizado,
                'desde' => $mov->codigo_ubicacion_origen,
                'hacia' => $mov->codigo_ubicacion_destino,
                'rack_origen' => $mov->nombre_rack_origen,
                'rack_destino' => $mov->nombre_rack_destino,
                'observaciones' => $observacionesLimpias
            ];
        });

        // ✅ Formatear datos para la vista Spark
        $datosUbicacion = [
            'id' => $ubicacion->idRackUbicacion,
            'codigo' => $ubicacion->codigo,
            'codigo_unico' => $ubicacion->codigo_unico,
            'nivel' => $ubicacion->nivel,
            'posicion' => $ubicacion->posicion,
            'capacidad_maxima' => $ubicacion->capacidad_maxima,
            'estado_ocupacion' => $estadoUbicacion,
            'rack_nombre' => $ubicacion->rack_nombre,
            'rack_id' => $ubicacion->idRack,
            'sede' => $ubicacion->sede,
            'tipo_rack' => $ubicacion->tipo_rack,
            'filas' => $ubicacion->filas,
            'columnas' => $ubicacion->columnas,

            // Estadísticas
            'total_articulos_sueltos' => $totalArticulosSueltos,
            'total_cajas' => $totalCajas,
            'total_articulos_en_cajas' => $totalArticulosEnCajas,
            'total_items' => $totalItems,

            // Información detallada
            'categorias' => $categoriasUnicas,
            'tipos_articulos' => $tiposUnicos,
            'productos' => $productosAgrupados->toArray(),
            'cajas' => $cajasTransformadas,
            'historial' => $historialTransformado,
            'fecha_actualizacion' => $ubicacion->updated_at,

            // Información adicional para display
            'producto_display' => $productosAgrupados->isNotEmpty() ?
                ($productosAgrupados->count() === 1 ?
                    $productosAgrupados->first()['nombre'] :
                    $productosAgrupados->first()['nombre'] . ' +' . ($productosAgrupados->count() - 1) . ' más'
                ) : 'Vacía',

            'cantidad_total' => $productosAgrupados->isNotEmpty() ? $productosAgrupados->sum('cantidad') : 0,
            'categorias_acumuladas' => $categoriasUnicas->isNotEmpty() ? $categoriasUnicas->join(', ') : 'Sin categoría',
            'tipos_acumulados' => $tiposUnicos->isNotEmpty() ? $tiposUnicos->join(', ') : 'Sin tipo',
        ];

        return view('almacen.ubicaciones.vista_spark_qr', [
            'ubicacion' => $datosUbicacion,
            'error' => false
        ]);
    }



    // En UbicacionesVistaController.php
    public function obtenerTipoRack(Request $request)
    {
        try {
            $rack = DB::table('racks')
                ->where('nombre', $request->nombre)
                ->where('sede', $request->sede)
                ->select('tipo_rack')
                ->first();

            if (!$rack) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rack no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'tipo_rack' => $rack->tipo_rack
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener tipo de rack'
            ], 500);
        }
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
                'r.tipo_rack', // ✅ NUEVO: Incluir tipo_rack
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

        // CALCULAR ACTIVIDAD - AGREGAR LOG DETALLADO
        Log::debug('=== INICIO CÁLCULO ACTIVIDAD ===', [
            'periodo' => $periodo,
            'fecha_inicio' => $fechaInicio,
            'sede_filtrada' => $sede,
            'buscar' => $buscar
        ]);

        // Calcular actividad (mantener igual)
        $movimientos = DB::table('rack_movimientos')
            ->select('rack_origen_id', 'rack_destino_id', 'cantidad', 'created_at')
            ->where('created_at', '>=', $fechaInicio)
            ->get();

        Log::debug('📊 MOVIMIENTOS ENCONTRADOS:', [
            'total_movimientos' => $movimientos->count(),
            'movimientos_sample' => $movimientos->take(3)->map(function ($mov) {
                return [
                    'rack_origen' => $mov->rack_origen_id,
                    'rack_destino' => $mov->rack_destino_id,
                    'cantidad' => $mov->cantidad,
                    'fecha' => $mov->created_at
                ];
            })
        ]);

        $actividadPorRack = [];
        foreach ($movimientos as $mov) {
            $racksInvolucrados = [];
            if ($mov->rack_origen_id) $racksInvolucrados[] = $mov->rack_origen_id;
            if ($mov->rack_destino_id) $racksInvolucrados[] = $mov->rack_destino_id;

            foreach ($racksInvolucrados as $rackId) {
                $actividadPorRack[$rackId] = ($actividadPorRack[$rackId] ?? 0) + 1;
            }
        }

        Log::debug('🎯 ACTIVIDAD POR RACK:', [
            'total_racks_con_actividad' => count($actividadPorRack),
            'actividad_detallada' => $actividadPorRack,
            'racks_ids' => array_keys($actividadPorRack)
        ]);

        // ✅ NUEVO: Calcular porcentaje de actividad de forma MÁS JUSTA
        $totalMovimientosPeriodo = $movimientos->count();
        $racksConMovimientos = array_filter($actividadPorRack); // Solo racks con actividad

        Log::debug('📈 DATOS PARA CÁLCULO:', [
            'total_movimientos_periodo' => $totalMovimientosPeriodo,
            'racks_con_movimientos_count' => count($racksConMovimientos),
            'racks_con_movimientos_detalle' => $racksConMovimientos
        ]);

        if ($totalMovimientosPeriodo > 0 && !empty($racksConMovimientos)) {
            // Calcular el porcentaje de actividad de CADA rack basado en el total de movimientos
            $porcentajesRacks = [];
            foreach ($racksConMovimientos as $rackId => $movimientosRack) {
                // Porcentaje = (movimientos del rack / total movimientos) * 100
                $porcentajeRack = ($movimientosRack / $totalMovimientosPeriodo) * 100;
                $porcentajeFinal = min(round($porcentajeRack), 100);
                $porcentajesRacks[$rackId] = $porcentajeFinal;

                Log::debug("🔢 CÁLCULO RACK {$rackId}:", [
                    'movimientos_rack' => $movimientosRack,
                    'total_movimientos' => $totalMovimientosPeriodo,
                    'porcentaje_calculado' => $porcentajeRack,
                    'porcentaje_final' => $porcentajeFinal
                ]);
            }

            // El promedio de actividad es el promedio de estos porcentajes
            $avgActivity = round(array_sum($porcentajesRacks) / count($porcentajesRacks));

            Log::debug('📊 PROMEDIO FINAL:', [
                'suma_porcentajes' => array_sum($porcentajesRacks),
                'cantidad_racks' => count($porcentajesRacks),
                'avg_activity_calculado' => array_sum($porcentajesRacks) / count($porcentajesRacks),
                'avg_activity_final' => $avgActivity,
                'porcentajes_individuales' => $porcentajesRacks
            ]);
        } else {
            $avgActivity = 0;
            Log::debug('❌ SIN ACTIVIDAD:', [
                'total_movimientos_periodo' => $totalMovimientosPeriodo,
                'racks_con_movimientos' => count($racksConMovimientos),
                'avg_activity' => 0
            ]);
        }
        // Mantener la actividad normalizada para el heatmap individual (PARA COMPARAR)
        $maxActividad = !empty($actividadPorRack) ? max($actividadPorRack) : 1;
        $actividadNormalizada = [];
        foreach ($actividadPorRack as $rackId => $actividad) {
            $porcentaje = min(round(($actividad / $maxActividad) * 100), 100);
            $actividadNormalizada[$rackId] = $porcentaje;
        }

        Log::debug('🔥 COMPARACIÓN MÉTODOS:', [
            'METODO_ANTIGUO_normalizado' => [
                'max_actividad' => $maxActividad,
                'actividad_normalizada' => $actividadNormalizada,
                'promedio_antiguo' => !empty($actividadNormalizada) ? round(array_sum($actividadNormalizada) / count($actividadNormalizada)) : 0
            ],
            'METODO_NUEVO_porcentual' => [
                'avg_activity' => $avgActivity
            ]
        ]);

        // Preparar datos para el heatmap
        $data = [];
        $rackGroups = [];

        // Agrupar por rack
        foreach ($ubicaciones as $ub) {
            $rackGroups[$ub->rack_nombre][] = $ub;
        }

        Log::debug('🏗️ ESTRUCTURA RACKS:', [
            'total_rack_groups' => count($rackGroups),
            'racks_nombres' => array_keys($rackGroups),
            'ubicaciones_por_rack' => array_map('count', $rackGroups)
        ]);

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
                    'tipo_rack' => $ub->tipo_rack ?? 'spark', // ✅ NUEVO: Agregar tipo_rack
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
            'activeRacks' => count(array_filter($actividadPorRack)), // ✅ Usar actividadPorRack, no normalizada
            'avgActivity' => $avgActivity, // ✅ Usar el nuevo cálculo (39%)
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
        // Query base para ubicaciones
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

        // ✅ CORREGIDO: Obtener productos normales Y custodias - SIN AGRUPAR
        $productosCompletos = DB::table('rack_ubicacion_articulos as rua')
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

            // ✅ PARA CUSTODIAS
            ->leftJoin('custodias as cust', 'rua.custodia_id', '=', 'cust.id')
            ->leftJoin('modelo as m_cust', 'cust.idModelo', '=', 'm_cust.idModelo')
            ->leftJoin('categoria as c_cust', 'm_cust.idCategoria', '=', 'c_cust.idCategoria')
            ->leftJoin('marca as mar_cust', 'cust.idMarca', '=', 'mar_cust.idMarca')
            ->leftJoin('tickets as t_cust', 'cust.numero_ticket', '=', 't_cust.numero_ticket')
            ->leftJoin('clientegeneral as cg_cust', 't_cust.idClienteGeneral', '=', 'cg_cust.idClienteGeneral')

            // ✅ Cliente general para productos normales
            ->leftJoin('clientegeneral as cg', 'rua.cliente_general_id', '=', 'cg.idClienteGeneral')

            ->whereIn('rua.rack_ubicacion_id', $ubicacionIds)

            // ✅ NUEVO: SELECCIONAR EL ID ÚNICO DE CADA REGISTRO
            ->select(
                'rua.idRackUbicacionArticulo', // ✅ ESTE ES EL ID ÚNICO DE CADA REGISTRO
                'rua.rack_ubicacion_id',
                'rua.cantidad',
                'rua.custodia_id',
                'rua.cliente_general_id',
                'rua.articulo_id',

                // Campos para productos normales
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

                // ✅ CATEGORÍA CORRECTA
                DB::raw('CASE 
            WHEN a.idTipoArticulo = 2 THEN c_repuesto.nombre 
            ELSE c_normal.nombre 
        END as categoria'),

                'cg.descripcion as cliente_general_nombre',

                // Campos para custodias
                'cust.codigocustodias',
                'cust.serie',
                'cust.idMarca',
                'cust.idModelo',
                'cust.numero_ticket',
                'c_cust.nombre as categoria_custodia',
                'mar_cust.nombre as marca_nombre',
                'm_cust.nombre as modelo_nombre',
                'cg_cust.idClienteGeneral as cliente_general_id_custodia',
                'cg_cust.descripcion as cliente_general_nombre_custodia'
            )
            ->get()
            ->groupBy('rack_ubicacion_id');

        // Estructurar el rack
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

                // Mapear productos (incluyendo custodias)
                $productos = $productosUbicacion->map(function ($art) {
                    // ✅ SI ES CUSTODIA
                    if ($art->custodia_id) {
                        return [
                            'id' => $art->articulo_id, // Puede ser null para custodias
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

                    // ✅ SI ES PRODUCTO NORMAL
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

                    // ✅ CORREGIDO: Mostrar información de múltiples productos
                    'producto' => $productos->isNotEmpty() ?
                        ($productos->count() === 1 ?
                            $productos->first()['nombre'] :
                            $productos->first()['nombre'] . ' +' . ($productos->count() - 1) . ' más'
                        ) : null,

                    'cantidad' => $cantidadTotal,
                    'cantidad_total' => $cantidadTotal,
                    'stock_total' => $productos->isNotEmpty() ? $productos->first()['stock_total'] : null,

                    // ✅ CORREGIDO: Usar tipos acumulados en lugar del primero
                    'tipo_articulo' => $tiposUnicos->isNotEmpty() ? $tiposUnicos->join(', ') : null,

                    // ✅ CORREGIDO: Usar categorías acumuladas en lugar de la primera
                    'categoria' => $categoriasUnicas->isNotEmpty() ? $categoriasUnicas->join(', ') : null,

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

        // ✅ CORREGIDO: Consulta que incluye el ID único de cada registro
        $rackData = DB::table('racks as r')
            ->join('rack_ubicaciones as ru', 'r.idRack', '=', 'ru.rack_id')
            ->leftJoin('rack_ubicacion_articulos as rua', 'ru.idRackUbicacion', '=', 'rua.rack_ubicacion_id')
            ->leftJoin('articulos as a', 'rua.articulo_id', '=', 'a.idArticulos')
            ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')

            // ✅ IMPORTANTE: Para repuestos, usar articulo_modelo. Para otros, usar idModelo directo
            ->leftJoin('articulo_modelo as am', function ($join) {
                $join->on('a.idArticulos', '=', 'am.articulo_id')
                    ->where('a.idTipoArticulo', '=', 2); // Solo para repuestos
            })

            // ✅ Para repuestos: modelo desde articulo_modelo
            // ✅ Para otros: modelo desde articulos.idModelo
            ->leftJoin('modelo as m', function ($join) {
                $join->on(function ($query) {
                    // Para repuestos: usar el modelo de articulo_modelo
                    $query->where('a.idTipoArticulo', '=', 2)
                        ->on('am.modelo_id', '=', 'm.idModelo');
                })->orOn(function ($query) {
                    // Para otros tipos: usar el modelo directo
                    $query->where('a.idTipoArticulo', '!=', 2)
                        ->on('a.idModelo', '=', 'm.idModelo');
                });
            })

            ->leftJoin('categoria as c', 'm.idCategoria', '=', 'c.idCategoria')
            ->leftJoin('custodias as cust', 'rua.custodia_id', '=', 'cust.id')
            ->leftJoin('modelo as m_cust', 'cust.idModelo', '=', 'm_cust.idModelo')
            ->leftJoin('categoria as c_cust', 'm_cust.idCategoria', '=', 'c_cust.idCategoria')
            ->leftJoin('marca as mar_cust', 'cust.idMarca', '=', 'mar_cust.idMarca')
            ->leftJoin('tickets as t_cust', 'cust.numero_ticket', '=', 't_cust.numero_ticket')
            ->leftJoin('clientegeneral as cg_cust', 't_cust.idClienteGeneral', '=', 'cg_cust.idClienteGeneral')
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
                'rua.idRackUbicacionArticulo',
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

                // ✅ Información del modelo
                'a.idModelo as articulo_modelo_id', // Solo válido para NO repuestos
                'am.modelo_id as repuesto_modelo_id', // Solo válido para repuestos
                'm.idModelo as modelo_id_final',
                'm.nombre as modelo_nombre',

                DB::raw('COALESCE(c.nombre, "Sin categoría") as categoria'),
                'rua.cantidad',
                'rua.custodia_id',
                'rua.cliente_general_id',
                'cg.descripcion as cliente_general_nombre',
                'cust.codigocustodias',
                'cust.serie',
                'cust.idMarca',
                'cust.idModelo',
                'cust.numero_ticket',
                'c_cust.nombre as categoria_custodia',
                'mar_cust.nombre as marca_nombre',
                'm_cust.nombre as modelo_nombre',
                'cg_cust.idClienteGeneral as cliente_general_id_custodia',
                'cg_cust.descripcion as cliente_general_nombre_custodia'
            )
            ->where('r.nombre', $rack)
            ->where('r.sede', $sede)
            ->where('r.estado', 'activo')
            ->orderBy('ru.nivel', 'desc')
            ->orderBy('ru.posicion')
            ->get();

        // ✅ Obtener TODOS los modelos para los repuestos encontrados
        $repuestosIds = $rackData->where('idTipoArticulo', 2)->pluck('idArticulos')->unique();
        $todosModelosRepuestos = [];

        if ($repuestosIds->isNotEmpty()) {
            $modelosData = DB::table('articulo_modelo as am')
                ->join('modelo as m', 'am.modelo_id', '=', 'm.idModelo')
                ->leftJoin('marca as mar', 'm.idMarca', '=', 'mar.idMarca')
                ->leftJoin('categoria as cat', 'm.idCategoria', '=', 'cat.idCategoria')
                ->whereIn('am.articulo_id', $repuestosIds)
                ->select(
                    'am.articulo_id',
                    'm.idModelo',
                    'm.nombre as modelo_nombre',
                    'mar.nombre as marca_nombre',
                    'cat.nombre as categoria_nombre'
                )
                ->orderBy('m.nombre')
                ->get();

            // Organizar por artículo_id
            foreach ($modelosData as $modelo) {
                $todosModelosRepuestos[$modelo->articulo_id][] = [
                    'id' => $modelo->idModelo,
                    'nombre' => $modelo->modelo_nombre,
                    'marca' => $modelo->marca_nombre,
                    'categoria' => $modelo->categoria_nombre
                ];
            }
        }

        // Si no existe el rack, redirigir
        if ($rackData->isEmpty()) {
            return redirect()->route('almacen.vista')->with('error', "Rack '{$rack}' no encontrado en la sede '{$sede}'");
        }

        $rackId = $rackData->first()->idRack;
        $ubicacionesIds = $rackData->pluck('idRackUbicacion')->unique();

        // Obtener historial de movimientos - MODIFICADO PARA LIMPIAR OBSERVACIONES Y NORMALIZAR TIPOS
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

            // Procesar movimientos para cada ubicación - MODIFICADO CON NORMALIZACIÓN DE TIPOS
            foreach ($movimientos as $mov) {
                // ✅ NORMALIZAR TIPOS DE MOVIMIENTO
                $tipoNormalizado = match (strtolower($mov->tipo_movimiento)) {
                    'entrada', 'ingreso' => 'ingreso',
                    'salida' => 'salida',
                    'reubicacion', 'reubicación' => 'reubicacion',
                    'reubicacion_custodia', 'reubicación custodia', 'reubicación_custodia' => 'reubicacion_custodia',
                    'ajuste' => 'ajuste',
                    default => strtolower($mov->tipo_movimiento)
                };

                // ✅ LIMPIAR OBSERVACIONES: Eliminar referencias a Producto/Artículo con ID
                $observacionesLimpias = $mov->observaciones;
                if ($mov->observaciones) {
                    $observacionesLimpias = preg_replace('/Producto:\s*\d+\s*-?\s*/', '', $mov->observaciones);
                    $observacionesLimpias = preg_replace('/Artículo:\s*\d+\s*-?\s*/', '', $mov->observaciones);
                    $observacionesLimpias = preg_replace('/\s*-\s*$/', '', $observacionesLimpias);
                    $observacionesLimpias = preg_replace('/^\s*-\s*/', '', $observacionesLimpias);
                    $observacionesLimpias = trim($observacionesLimpias);
                }

                if ($mov->ubicacion_origen_id && in_array($mov->ubicacion_origen_id, $ubicacionesIds->toArray())) {
                    $historialPorUbicacion[$mov->ubicacion_origen_id][] = [
                        'fecha' => $mov->created_at,
                        'producto' => 'Artículo Movido',
                        'cantidad' => $mov->cantidad,
                        'tipo' => $tipoNormalizado,
                        'desde' => $mov->codigo_ubicacion_origen,
                        'hacia' => $mov->codigo_ubicacion_destino,
                        'rack_origen' => $mov->nombre_rack_origen,
                        'rack_destino' => $mov->nombre_rack_destino,
                        'observaciones' => $observacionesLimpias
                    ];
                }

                if ($mov->ubicacion_destino_id && in_array($mov->ubicacion_destino_id, $ubicacionesIds->toArray())) {
                    $historialPorUbicacion[$mov->ubicacion_destino_id][] = [
                        'fecha' => $mov->created_at,
                        'producto' => 'Artículo Movido',
                        'cantidad' => $mov->cantidad,
                        'tipo' => $tipoNormalizado,
                        'desde' => $mov->codigo_ubicacion_origen,
                        'hacia' => $mov->codigo_ubicacion_destino,
                        'rack_origen' => $mov->nombre_rack_origen,
                        'rack_destino' => $mov->nombre_rack_destino,
                        'observaciones' => $observacionesLimpias
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
                            'tipo' => $tipoNormalizado,
                            'desde' => $mov->nombre_rack_origen,
                            'hacia' => $mov->nombre_rack_destino,
                            'rack_origen' => $mov->nombre_rack_origen,
                            'rack_destino' => $mov->nombre_rack_destino,
                            'observaciones' => $observacionesLimpias
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

                // ✅ CORREGIDO: Procesar CADA ARTÍCULO INDIVIDUALMENTE sin agrupar
                $productosAgrupados = collect();

                // ✅ FILTRAR SOLO ARTÍCULOS VÁLIDOS (que tengan idArticulos o custodia_id)
                $articulosValidos = $articulos->filter(function ($art) {
                    return $art->idArticulos !== null || $art->custodia_id !== null;
                });

                if ($articulosValidos->isNotEmpty()) {
                    foreach ($articulosValidos as $art) {
                        // ✅ SI ES CUSTODIA
                        if ($art->custodia_id) {
                            $productosAgrupados->push([
                                'id' => $art->idArticulos,
                                'idRackUbicacionArticulo' => $art->idRackUbicacionArticulo,
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
                            ]);
                        } else {
                            // ✅ SI ES PRODUCTO NORMAL
                            $mostrandoCodigoRepuesto = ($art->idTipoArticulo == 2 && !empty($art->codigo_repuesto));

                            // ✅ Obtener modelos según el tipo de artículo
                            $modelosDelArticulo = [];

                            if ($art->idTipoArticulo == 2) {
                                // Para repuestos: obtener todos los modelos de la tabla articulo_modelo
                                $modelosDelArticulo = $todosModelosRepuestos[$art->idArticulos] ?? [];
                            } else {
                                // Para otros tipos: usar el modelo directo (si existe)
                                if ($art->modelo_nombre) {
                                    $modelosDelArticulo = [[
                                        'id' => $art->modelo_id_final,
                                        'nombre' => $art->modelo_nombre,
                                        'marca' => null,
                                        'categoria' => $art->categoria
                                    ]];
                                }
                            }

                            $productosAgrupados->push([
                                'id' => $art->idArticulos,
                                'idRackUbicacionArticulo' => $art->idRackUbicacionArticulo,
                                'nombre' => $art->producto,
                                'nombre_original' => $art->nombre_original,
                                'codigo_repuesto' => $art->codigo_repuesto,
                                'cantidad' => $art->cantidad,
                                'stock_total' => $art->stock_total,
                                'tipo_articulo' => $art->tipo_articulo,
                                'idTipoArticulo' => $art->idTipoArticulo,
                                'categoria' => $art->categoria,

                                // ✅ Información del modelo
                                'modelo_id' => $art->idTipoArticulo != 2 ? $art->articulo_modelo_id : null,
                                'modelo_nombre' => $art->idTipoArticulo != 2 ? $art->modelo_nombre : null,

                                // ✅ Para repuestos: todos los modelos
                                'modelos' => $modelosDelArticulo,
                                'tiene_multiple_modelos' => $art->idTipoArticulo == 2 && count($modelosDelArticulo) > 1,

                                'custodia_id' => null,
                                'es_repuesto' => $art->idTipoArticulo == 2,
                                'mostrando_codigo_repuesto' => $mostrandoCodigoRepuesto,
                                'cliente_general_id' => $art->cliente_general_id,
                                'cliente_general_nombre' => $art->cliente_general_nombre ?: 'Sin cliente'
                            ]);
                        }
                    }
                }

                // ✅ CALCULAR CANTIDAD TOTAL
                $cantidadTotal = $productosAgrupados->isNotEmpty() ? $productosAgrupados->sum('cantidad') : 0;

                // ✅ NUEVO: CALCULAR ESTADO BASADO EN RANGOS FIJOS DE CAPACIDAD
                $estado = $this->calcularEstadoPorCapacidad($cantidadTotal);

                // Acumular categorías y tipos
                $categoriasUnicas = $productosAgrupados->pluck('categoria')->filter()->unique();
                $tiposUnicos = $productosAgrupados->pluck('tipo_articulo')->filter()->unique();
                $clientesUnicos = $productosAgrupados->pluck('cliente_general_nombre')
                    ->filter(fn($cliente) => $cliente && $cliente !== 'Sin cliente')
                    ->unique();

                // ✅ CORREGIDO: Mostrar información de múltiples productos
                $productoDisplay = $productosAgrupados->isNotEmpty() ?
                    ($productosAgrupados->count() === 1 ?
                        $productosAgrupados->first()['nombre'] :
                        $productosAgrupados->first()['nombre'] . ' +' . ($productosAgrupados->count() - 1) . ' más'
                    ) : null;

                // ✅ CORREGIDO: Usar tipos acumulados en lugar del primero
                $tipoArticuloDisplay = $tiposUnicos->isNotEmpty() ? $tiposUnicos->join(', ') : null;

                // ✅ CORREGIDO: Usar categorías acumuladas en lugar de la primera
                $categoriaDisplay = $categoriasUnicas->isNotEmpty() ? $categoriasUnicas->join(', ') : null;

                $ubicacionesEstructuradas[] = [
                    'id' => $primerArticulo->idRackUbicacion,
                    'codigo' => $primerArticulo->codigo_unico ?? $primerArticulo->codigo,
                    'productos' => $productosAgrupados->toArray(),
                    'producto' => $productoDisplay,
                    'cantidad' => $cantidadTotal,
                    'cantidad_total' => $cantidadTotal,
                    'stock_total' => $productosAgrupados->isNotEmpty() ? $productosAgrupados->first()['stock_total'] : null,
                    'tipo_articulo' => $tipoArticuloDisplay,
                    'categoria' => $categoriaDisplay,
                    'capacidad' => $primerArticulo->capacidad_maxima,
                    'estado' => $estado,
                    'nivel' => $primerArticulo->nivel,
                    'fecha' => $primerArticulo->updated_at,
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

    /**
     * ✅ NUEVO: Calcular estado basado en rangos fijos de capacidad
     */
    private function calcularEstadoPorCapacidad($cantidadTotal)
    {
        if ($cantidadTotal == 0) {
            return 'vacio';
        } elseif ($cantidadTotal <= 100) {
            return 'bajo';
        } elseif ($cantidadTotal <= 500) {
            return 'medio';
        } elseif ($cantidadTotal <= 1000) {
            return 'alto';
        } else {
            return 'muy_alto';
        }
    }


    public function detalleRackPanel($rack)
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

        // Obtener información completa del rack incluyendo tipo
        $rackInfo = DB::table('racks')
            ->where('nombre', $rack)
            ->where('sede', $sede)
            ->where('estado', 'activo')
            ->first();

        if (!$rackInfo) {
            return redirect()->route('almacen.vista')->with('error', "Rack '{$rack}' no encontrado en la sede '{$sede}'");
        }

        // ✅ CONSULTA CORREGIDA: Manejo de modelos para repuestos (idTipoArticulo = 2)
        $rackData = DB::table('racks as r')
            ->join('rack_ubicaciones as ru', 'r.idRack', '=', 'ru.rack_id')
            ->leftJoin('rack_ubicacion_articulos as rua', 'ru.idRackUbicacion', '=', 'rua.rack_ubicacion_id')
            ->leftJoin('articulos as a', 'rua.articulo_id', '=', 'a.idArticulos')
            ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')

            // ✅ IMPORTANTE: Para repuestos, usar articulo_modelo. Para otros, usar idModelo directo
            ->leftJoin('articulo_modelo as am', function ($join) {
                $join->on('a.idArticulos', '=', 'am.articulo_id')
                    ->where('a.idTipoArticulo', '=', 2); // Solo para repuestos
            })

            // ✅ Para repuestos: modelo desde articulo_modelo
            // ✅ Para otros: modelo desde articulos.idModelo
            ->leftJoin('modelo as m', function ($join) {
                $join->on(function ($query) {
                    // Para repuestos: usar el modelo de articulo_modelo
                    $query->where('a.idTipoArticulo', '=', 2)
                        ->on('am.modelo_id', '=', 'm.idModelo');
                })->orOn(function ($query) {
                    // Para otros tipos: usar el modelo directo
                    $query->where('a.idTipoArticulo', '!=', 2)
                        ->on('a.idModelo', '=', 'm.idModelo');
                });
            })

            ->leftJoin('categoria as c', 'm.idCategoria', '=', 'c.idCategoria')
            ->leftJoin('custodias as cust', 'rua.custodia_id', '=', 'cust.id')
            ->leftJoin('modelo as m_cust', 'cust.idModelo', '=', 'm_cust.idModelo')
            ->leftJoin('categoria as c_cust', 'm_cust.idCategoria', '=', 'c_cust.idCategoria')
            ->leftJoin('marca as mar_cust', 'cust.idMarca', '=', 'mar_cust.idMarca')
            ->leftJoin('tickets as t_cust', 'cust.numero_ticket', '=', 't_cust.numero_ticket')
            ->leftJoin('clientegeneral as cg_cust', 't_cust.idClienteGeneral', '=', 'cg_cust.idClienteGeneral')
            ->leftJoin('clientegeneral as cg', 'rua.cliente_general_id', '=', 'cg.idClienteGeneral')
            ->select(
                'r.idRack',
                'r.nombre as rack_nombre',
                'r.sede',
                'r.tipo_rack',
                'ru.idRackUbicacion',
                'ru.codigo',
                'ru.codigo_unico',
                'ru.nivel',
                'ru.posicion',
                'ru.estado_ocupacion',
                'ru.updated_at',
                'rua.idRackUbicacionArticulo',
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

                // ✅ Información del modelo
                'a.idModelo as articulo_modelo_id', // Solo válido para NO repuestos
                'am.modelo_id as repuesto_modelo_id', // Solo válido para repuestos
                'm.idModelo as modelo_id_final',
                'm.nombre as modelo_nombre',

                DB::raw('COALESCE(c.nombre, "Sin categoría") as categoria'),
                'rua.cantidad',
                'rua.custodia_id',
                'rua.cliente_general_id',
                'cg.descripcion as cliente_general_nombre',
                'cust.codigocustodias',
                'cust.serie',
                'cust.idMarca',
                'cust.idModelo',
                'cust.numero_ticket',
                'c_cust.nombre as categoria_custodia',
                'mar_cust.nombre as marca_nombre',
                'm_cust.nombre as modelo_nombre',
                'cg_cust.idClienteGeneral as cliente_general_id_custodia',
                'cg_cust.descripcion as cliente_general_nombre_custodia'
            )
            ->where('r.nombre', $rack)
            ->where('r.sede', $sede)
            ->where('r.estado', 'activo')
            ->orderBy('ru.nivel', 'desc')
            ->orderBy('ru.posicion')
            ->get();

        // ✅ Obtener TODOS los modelos para los repuestos encontrados
        $repuestosIds = $rackData->where('idTipoArticulo', 2)->pluck('idArticulos')->unique();
        $todosModelosRepuestos = [];

        if ($repuestosIds->isNotEmpty()) {
            $modelosData = DB::table('articulo_modelo as am')
                ->join('modelo as m', 'am.modelo_id', '=', 'm.idModelo')
                ->leftJoin('marca as mar', 'm.idMarca', '=', 'mar.idMarca')
                ->leftJoin('categoria as cat', 'm.idCategoria', '=', 'cat.idCategoria')
                ->whereIn('am.articulo_id', $repuestosIds)
                ->select(
                    'am.articulo_id',
                    'm.idModelo',
                    'm.nombre as modelo_nombre',
                    'mar.nombre as marca_nombre',
                    'cat.nombre as categoria_nombre'
                )
                ->orderBy('m.nombre')
                ->get();

            // Organizar por artículo_id
            foreach ($modelosData as $modelo) {
                $todosModelosRepuestos[$modelo->articulo_id][] = [
                    'id' => $modelo->idModelo,
                    'nombre' => $modelo->modelo_nombre,
                    'marca' => $modelo->marca_nombre,
                    'categoria' => $modelo->categoria_nombre
                ];
            }
        }

        // ✅ CONSULTA SIMPLE para cajas con su artículo
        $cajasData = DB::table('cajas as cj')
            ->join('rack_ubicaciones as ru', 'cj.idubicaciones_rack', '=', 'ru.idRackUbicacion')
            ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
            ->leftJoin('articulos as a', 'cj.idArticulo', '=', 'a.idArticulos')
            ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')

            // ✅ IMPORTANTE: Para repuestos en cajas, también necesitamos articulo_modelo
            ->leftJoin('articulo_modelo as am_caja', function ($join) {
                $join->on('a.idArticulos', '=', 'am_caja.articulo_id')
                    ->where('a.idTipoArticulo', '=', 2); // Solo para repuestos
            })

            // ✅ Para repuestos: modelo desde articulo_modelo
            // ✅ Para otros: modelo desde articulos.idModelo
            ->leftJoin('modelo as m', function ($join) {
                $join->on(function ($query) {
                    // Para repuestos: usar el modelo de articulo_modelo
                    $query->where('a.idTipoArticulo', '=', 2)
                        ->on('am_caja.modelo_id', '=', 'm.idModelo');
                })->orOn(function ($query) {
                    // Para otros tipos: usar el modelo directo
                    $query->where('a.idTipoArticulo', '!=', 2)
                        ->on('a.idModelo', '=', 'm.idModelo');
                });
            })

            ->leftJoin('categoria as c', 'm.idCategoria', '=', 'c.idCategoria')
            ->select(
                'cj.idCaja',
                'cj.nombre',
                'cj.cantidad_actual',
                'cj.capacidad',
                'cj.estado',
                'cj.es_custodia',
                'cj.orden_en_pallet',
                'cj.fecha_entrada',
                'cj.idubicaciones_rack as idRackUbicacion',
                'a.idArticulos',
                'a.nombre as articulo_nombre',
                'a.codigo_barras',
                'a.codigo_repuesto',
                'a.stock_total',
                'a.idTipoArticulo',
                'ta.nombre as tipo_articulo',
                DB::raw('COALESCE(c.nombre, "Sin categoría") as categoria_articulo')
            )
            ->where('r.nombre', $rack)
            ->where('r.sede', $sede)
            ->where('r.estado', 'activo')
            ->get();

        // ✅ Obtener TODOS los modelos para los repuestos dentro de cajas
        $repuestosEnCajasIds = $cajasData->where('idTipoArticulo', 2)->pluck('idArticulos')->unique();
        $todosModelosRepuestosEnCajas = [];

        if ($repuestosEnCajasIds->isNotEmpty()) {
            $modelosDataCajas = DB::table('articulo_modelo as am')
                ->join('modelo as m', 'am.modelo_id', '=', 'm.idModelo')
                ->leftJoin('marca as mar', 'm.idMarca', '=', 'mar.idMarca')
                ->leftJoin('categoria as cat', 'm.idCategoria', '=', 'cat.idCategoria')
                ->whereIn('am.articulo_id', $repuestosEnCajasIds)
                ->select(
                    'am.articulo_id',
                    'm.idModelo',
                    'm.nombre as modelo_nombre',
                    'mar.nombre as marca_nombre',
                    'cat.nombre as categoria_nombre'
                )
                ->orderBy('m.nombre')
                ->get();

            // Organizar por artículo_id
            foreach ($modelosDataCajas as $modelo) {
                $todosModelosRepuestosEnCajas[$modelo->articulo_id][] = [
                    'id' => $modelo->idModelo,
                    'nombre' => $modelo->modelo_nombre,
                    'marca' => $modelo->marca_nombre,
                    'categoria' => $modelo->categoria_nombre
                ];
            }
        }

        // Si no existe el rack, redirigir
        if ($rackData->isEmpty() && $cajasData->isEmpty()) {
            return redirect()->route('almacen.vista')->with('error', "Rack '{$rack}' no encontrado en la sede '{$sede}'");
        }

        $rackId = !$rackData->isEmpty() ? $rackData->first()->idRack : ($cajasData->isEmpty() ? null : $cajasData->first()->idRack);
        $ubicacionesIds = $rackData->pluck('idRackUbicacion')->unique();

        // Agregar IDs de ubicaciones de cajas
        $ubicacionesCajasIds = $cajasData->pluck('idRackUbicacion')->unique();
        $ubicacionesIds = $ubicacionesIds->merge($ubicacionesCajasIds)->unique();

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

            foreach ($movimientos as $mov) {
                $tipoNormalizado = match (strtolower($mov->tipo_movimiento)) {
                    'entrada', 'ingreso' => 'ingreso',
                    'salida' => 'salida',
                    'reubicacion', 'reubicación' => 'reubicacion',
                    'reubicacion_custodia', 'reubicación custodia', 'reubicación_custodia' => 'reubicacion_custodia',
                    'ajuste' => 'ajuste',
                    default => strtolower($mov->tipo_movimiento)
                };

                $observacionesLimpias = $mov->observaciones;
                if ($mov->observaciones) {
                    $observacionesLimpias = preg_replace('/Producto:\s*\d+\s*-?\s*/', '', $mov->observaciones);
                    $observacionesLimpias = preg_replace('/Artículo:\s*\d+\s*-?\s*/', '', $mov->observaciones);
                    $observacionesLimpias = preg_replace('/\s*-\s*$/', '', $observacionesLimpias);
                    $observacionesLimpias = preg_replace('/^\s*-\s*/', '', $observacionesLimpias);
                    $observacionesLimpias = trim($observacionesLimpias);
                }

                if ($mov->ubicacion_origen_id && in_array($mov->ubicacion_origen_id, $ubicacionesIds->toArray())) {
                    $historialPorUbicacion[$mov->ubicacion_origen_id][] = [
                        'fecha' => $mov->created_at,
                        'producto' => 'Artículo Movido',
                        'cantidad' => $mov->cantidad,
                        'tipo' => $tipoNormalizado,
                        'desde' => $mov->codigo_ubicacion_origen,
                        'hacia' => $mov->codigo_ubicacion_destino,
                        'rack_origen' => $mov->nombre_rack_origen,
                        'rack_destino' => $mov->nombre_rack_destino,
                        'observaciones' => $observacionesLimpias
                    ];
                }

                if ($mov->ubicacion_destino_id && in_array($mov->ubicacion_destino_id, $ubicacionesIds->toArray())) {
                    $historialPorUbicacion[$mov->ubicacion_destino_id][] = [
                        'fecha' => $mov->created_at,
                        'producto' => 'Artículo Movido',
                        'cantidad' => $mov->cantidad,
                        'tipo' => $tipoNormalizado,
                        'desde' => $mov->codigo_ubicacion_origen,
                        'hacia' => $mov->codigo_ubicacion_destino,
                        'rack_origen' => $mov->nombre_rack_origen,
                        'rack_destino' => $mov->nombre_rack_destino,
                        'observaciones' => $observacionesLimpias
                    ];
                }
            }
        }

        // Estructurar datos para la vista
        $rackEstructurado = [
            'nombre' => $rackInfo->nombre,
            'sede' => $rackInfo->sede,
            'tipo_rack' => $rackInfo->tipo_rack,
            'filas' => $rackInfo->filas,
            'columnas' => $rackInfo->columnas,
            'niveles' => []
        ];

        // Agrupar por niveles y ubicaciones
        $niveles = $rackData->groupBy('nivel');

        // Procesar ubicaciones regulares (rack_ubicacion_articulos)
        foreach ($niveles as $nivelNum => $ubicacionesNivel) {
            $ubicacionesEstructuradas = [];

            // ✅ Obtener ubicaciones ÚNICAS ordenadas por posición
            $ubicacionesUnicas = $ubicacionesNivel->unique('idRackUbicacion')->sortBy('posicion');

            foreach ($ubicacionesUnicas as $ubicacion) {
                // Obtener TODOS los artículos de esta ubicación (incluyendo múltiples)
                $productosUbicacion = $rackData->where('idRackUbicacion', $ubicacion->idRackUbicacion)
                    ->filter(function ($item) {
                        return $item->idArticulos !== null || $item->custodia_id !== null;
                    });

                // ✅ Obtener CAJAS de esta ubicación (pueden ser varias)
                $cajasUbicacion = $cajasData->where('idRackUbicacion', $ubicacion->idRackUbicacion);

                $productosAgrupados = collect();
                $cajasAgrupadas = collect(); // ✅ Colección separada para cajas
                $cantidadTotal = 0;

                // ✅ PROCESAR ARTÍCULOS NORMALES (no en cajas)
                if ($productosUbicacion->isNotEmpty()) {
                    foreach ($productosUbicacion as $art) {
                        if ($art->custodia_id) {
                            $productosAgrupados->push([
                                'id' => $art->idArticulos,
                                'idRackUbicacionArticulo' => $art->idRackUbicacionArticulo,
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
                                'cliente_general_nombre' => $art->cliente_general_nombre_custodia ?: 'Sin cliente',
                                'es_caja' => false,
                                'es_contenido_caja' => false
                            ]);
                        } else {
                            $mostrandoCodigoRepuesto = ($art->idTipoArticulo == 2 && !empty($art->codigo_repuesto));

                            // ✅ Obtener modelos según el tipo de artículo
                            $modelosDelArticulo = [];

                            if ($art->idTipoArticulo == 2) {
                                // Para repuestos: obtener todos los modelos de la tabla articulo_modelo
                                $modelosDelArticulo = $todosModelosRepuestos[$art->idArticulos] ?? [];
                            } else {
                                // Para otros tipos: usar el modelo directo (si existe)
                                if ($art->modelo_nombre) {
                                    $modelosDelArticulo = [[
                                        'id' => $art->modelo_id_final,
                                        'nombre' => $art->modelo_nombre,
                                        'marca' => null,
                                        'categoria' => $art->categoria
                                    ]];
                                }
                            }

                            $productosAgrupados->push([
                                'id' => $art->idArticulos,
                                'idRackUbicacionArticulo' => $art->idRackUbicacionArticulo,
                                'nombre' => $art->producto,
                                'nombre_original' => $art->nombre_original,
                                'codigo_repuesto' => $art->codigo_repuesto,
                                'cantidad' => $art->cantidad,
                                'stock_total' => $art->stock_total,
                                'tipo_articulo' => $art->tipo_articulo,
                                'idTipoArticulo' => $art->idTipoArticulo,
                                'categoria' => $art->categoria,

                                // ✅ Información del modelo
                                'modelo_id' => $art->idTipoArticulo != 2 ? $art->articulo_modelo_id : null,
                                'modelo_nombre' => $art->idTipoArticulo != 2 ? $art->modelo_nombre : null,

                                // ✅ Para repuestos: todos los modelos
                                'modelos' => $modelosDelArticulo,
                                'tiene_multiple_modelos' => $art->idTipoArticulo == 2 && count($modelosDelArticulo) > 1,

                                'custodia_id' => null,
                                'es_repuesto' => $art->idTipoArticulo == 2,
                                'mostrando_codigo_repuesto' => $mostrandoCodigoRepuesto,
                                'cliente_general_id' => $art->cliente_general_id,
                                'cliente_general_nombre' => $art->cliente_general_nombre ?: 'Sin cliente',
                                'es_caja' => false,
                                'es_contenido_caja' => false
                            ]);
                        }
                    }
                }

                // ✅ PROCESAR CAJAS (MEJORADO CON MODELOS)
                $categoriasDeArticulosEnCajas = collect();
                $tiposDeArticulosEnCajas = collect();
                $capacidadTotalCajas = 0;

                if ($cajasUbicacion->isNotEmpty()) {
                    foreach ($cajasUbicacion as $caja) {
                        // ✅ CREAR el artículo que está DENTRO de la caja (CON MODELOS)
                        $articuloEnCaja = null;
                        if ($caja->idArticulos) {
                            // ✅ Obtener modelos para repuestos dentro de cajas
                            $modelosArticuloEnCaja = [];

                            if ($caja->idTipoArticulo == 2) {
                                // Para repuestos: obtener todos los modelos de la tabla articulo_modelo
                                $modelosArticuloEnCaja = $todosModelosRepuestosEnCajas[$caja->idArticulos] ?? [];
                            } else {
                                // Para otros tipos: el modelo ya viene en la consulta
                                if ($caja->categoria_articulo && $caja->categoria_articulo !== 'Sin categoría') {
                                    $modelosArticuloEnCaja = [[
                                        'nombre' => null, // No tenemos el nombre del modelo en esta consulta
                                        'categoria' => $caja->categoria_articulo
                                    ]];
                                }
                            }

                            $articuloEnCaja = [
                                'id' => $caja->idArticulos,
                                'nombre' => $caja->articulo_nombre,
                                'codigo_barras' => $caja->codigo_barras,
                                'codigo_repuesto' => $caja->codigo_repuesto,
                                'cantidad' => $caja->cantidad_actual,
                                'stock_total' => $caja->stock_total,
                                'tipo_articulo' => $caja->tipo_articulo,
                                'idTipoArticulo' => $caja->idTipoArticulo,
                                'categoria' => $caja->categoria_articulo,
                                'modelos' => $modelosArticuloEnCaja,
                                'es_repuesto' => $caja->idTipoArticulo == 2,
                                'es_contenido_caja' => true,
                                'cajaPadre' => [
                                    'idCaja' => $caja->idCaja,
                                    'nombre' => $caja->nombre,
                                    'cantidad' => $caja->cantidad_actual,
                                    'capacidad' => $caja->capacidad,
                                    'estado' => $caja->estado
                                ]
                            ];

                            // ✅ AÑADIR CATEGORÍA
                            if ($caja->categoria_articulo && $caja->categoria_articulo !== 'Sin categoría') {
                                $categoriasDeArticulosEnCajas->push($caja->categoria_articulo);
                            }

                            // ✅ AÑADIR TIPO
                            if ($caja->tipo_articulo) {
                                $tiposDeArticulosEnCajas->push($caja->tipo_articulo);
                            }
                        }

                        $capacidadTotalCajas += $caja->capacidad;

                        // ✅ Agregar información de la CAJA
                        $cajasAgrupadas->push([
                            'id' => $caja->idCaja,
                            'idCaja' => $caja->idCaja,
                            'nombre' => $caja->nombre ?: 'Caja',
                            'nombre_caja' => $caja->nombre,
                            'cantidad' => $caja->cantidad_actual,
                            'cantidad_articulos' => $caja->cantidad_actual,
                            'capacidad_caja' => $caja->capacidad,
                            'estado_caja' => $caja->estado,
                            'tipo_articulo' => 'CAJA',
                            'categoria' => 'Caja',
                            'es_custodia' => $caja->es_custodia,
                            'contenido' => $caja->articulo_nombre ?: 'Sin contenido específico',
                            'tipo_contenido' => $caja->tipo_articulo ?: 'General',
                            'fecha_entrada' => $caja->fecha_entrada,
                            'orden_en_pallet' => $caja->orden_en_pallet,
                            'es_caja' => true,
                            'articulo_en_caja' => $articuloEnCaja,
                        ]);

                        $cantidadTotal += $caja->cantidad_actual;
                    }
                }

                // ✅ COMBINAR: primero cajas, luego artículos sueltos
                $todosLosProductos = $cajasAgrupadas->merge($productosAgrupados);

                // ✅ Estado de ocupación
                $estado = $cantidadTotal > 0 ? 'ocupado' : 'vacio';

                // ✅ Contar cajas
                $cantidadCajas = $cajasAgrupadas->count();
                $cantidadArticulosSueltos = $productosAgrupados->count();

                // ✅ ACUMULAR CATEGORÍAS (¡AHORA INCLUYENDO ARTÍCULOS EN CAJAS!)
                $todasLasCategorias = $productosAgrupados->pluck('categoria')
                    ->merge($categoriasDeArticulosEnCajas)
                    ->filter(fn($cat) => $cat && $cat !== 'Sin categoría')
                    ->unique()
                    ->sort();

                $clientesUnicos = $productosAgrupados->pluck('cliente_general_nombre')
                    ->filter(fn($cliente) => $cliente && $cliente !== 'Sin cliente')
                    ->unique();

                $todosLosTipos = $productosAgrupados->pluck('tipo_articulo')
                    ->merge($tiposDeArticulosEnCajas)
                    ->filter(fn($tipo) => $tipo && trim($tipo) !== '')
                    ->unique()
                    ->sort();

                // ✅ Información para mostrar
                $totalItems = $cantidadCajas + $cantidadArticulosSueltos;
                $primerItem = $todosLosProductos->first();

                $productoDisplay = $totalItems > 0 ?
                    ($totalItems === 1 ?
                        $primerItem['nombre'] :
                        $primerItem['nombre'] . ' +' . ($totalItems - 1) . ' más'
                    ) : 'Vacío';

                $tipoArticuloDisplay = $cantidadCajas > 0 ?
                    ($cantidadCajas . ' Caja' . ($cantidadCajas > 1 ? 's' : '') .
                        ($cantidadArticulosSueltos > 0 ? ', ' . $cantidadArticulosSueltos . ' Artículo' . ($cantidadArticulosSueltos > 1 ? 's' : '') : ''))
                    : ($todosLosTipos->isNotEmpty() ? $todosLosTipos->join(', ') : 'Sin tipo');

                // ✅ AHORA SÍ MUESTRA CATEGORÍAS DE ARTÍCULOS EN CAJAS TAMBIÉN
                $categoriaDisplay = $todasLasCategorias->isNotEmpty() ?
                    $todasLasCategorias->join(', ') :
                    'Sin categoría';

                $ubicacionesEstructuradas[] = [
                    'id' => $ubicacion->idRackUbicacion,
                    'codigo' => $ubicacion->codigo_unico ?? $ubicacion->codigo,
                    'productos' => $todosLosProductos->toArray(),
                    'cajas' => $cajasAgrupadas->toArray(),
                    'articulos_sueltos' => $productosAgrupados->toArray(),
                    'producto' => $productoDisplay,
                    'cantidad' => $cantidadTotal,
                    'cantidad_total' => $cantidadTotal,
                    'capacidad_total_cajas' => $capacidadTotalCajas,
                    'cantidad_cajas' => $cantidadCajas,
                    'cantidad_articulos_sueltos' => $cantidadArticulosSueltos,
                    'stock_total' => $productosAgrupados->isNotEmpty() ? $productosAgrupados->first()['stock_total'] : null,
                    'tipo_articulo' => $tipoArticuloDisplay,
                    'categoria' => $categoriaDisplay,
                    'estado' => $estado,
                    'nivel' => $ubicacion->nivel,
                    'fecha' => $ubicacion->updated_at,
                    'categorias_acumuladas' => $todasLasCategorias->isNotEmpty() ? $todasLasCategorias->join(', ') : 'Sin categoría',
                    'tipos_acumulados' => $todosLosTipos->isNotEmpty() ? $todosLosTipos->join(', ') : 'Sin tipo',
                    'clientes_acumulados' => $clientesUnicos->isNotEmpty() ? $clientesUnicos->join(', ') : 'Sin cliente',
                    'tiene_cajas' => $cantidadCajas > 0,
                    'tiene_articulos_sueltos' => $cantidadArticulosSueltos > 0,
                    'historial' => $historialPorUbicacion[$ubicacion->idRackUbicacion] ?? []
                ];
            }

            // ✅ ORDENAR por código para asegurar correlativo
            usort($ubicacionesEstructuradas, function ($a, $b) {
                return strcmp($a['codigo'], $b['codigo']);
            });

            $rackEstructurado['niveles'][] = [
                'numero' => $nivelNum,
                'ubicaciones' => $ubicacionesEstructuradas
            ];
        }

        // Ordenar niveles
        usort($rackEstructurado['niveles'], function ($a, $b) {
            return $b['numero'] <=> $a['numero'];
        });

        // Ordenar ubicaciones dentro de cada nivel
        foreach ($rackEstructurado['niveles'] as &$nivel) {
            usort($nivel['ubicaciones'], function ($a, $b) {
                return strcmp($a['codigo'], $b['codigo']);
            });
        }

        // Obtener lista de todos los racks para navegación
        $todosRacks = DB::table('racks')
            ->where('sede', $sede)
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->pluck('nombre')
            ->toArray();

        return view('almacen.ubicaciones.detalle-rack-panel', [
            'rack' => $rackEstructurado,
            'todosRacks' => $todosRacks,
            'rackActual' => $rack,
            'sedeActual' => $sede,
            'rackNombre' => $rack
        ]);
    }


    public function getUbicacionesParaMovimientoPanel(Request $request)
    {
        $request->validate([
            'ubicacion_origen_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
            'tipo_articulo'       => 'required|in:articulo,custodia,caja',
            'articulo_id'         => 'nullable|integer|exists:articulos,idArticulos',
            'caja_id'             => 'nullable|integer|exists:cajas,idCaja',
            'rack_id'             => 'nullable|integer|exists:racks,idRack',
        ]);

        // 1. OBTENER UBICACIÓN ORIGEN (MÁS RÁPIDO)
        $ubicacionOrigen = DB::table('rack_ubicaciones')
            ->select('rack_ubicaciones.*', 'racks.nombre as rack_nombre', 'racks.sede', 'racks.tipo_rack')
            ->join('racks', 'rack_ubicaciones.rack_id', '=', 'racks.idRack')
            ->where('rack_ubicaciones.idRackUbicacion', $request->ubicacion_origen_id)
            ->first();

        if (!$ubicacionOrigen) {
            return response()->json(['success' => false, 'message' => 'Ubicación origen no encontrada'], 404);
        }

        // 2. OBTENER INFO DEL ARTÍCULO (MEJORADO)
        $articuloInfo = null;
        if ($request->articulo_id) {
            $articuloInfo = DB::table('articulos')
                ->select(
                    'articulos.idArticulos',
                    'articulos.codigo_barras',
                    'articulos.codigo_repuesto',
                    'articulos.nombre',
                    'articulos.sku',
                    'articulos.stock_total',
                    'tipoarticulos.nombre as tipo_articulo',
                    DB::raw('COALESCE(categoria.nombre, "Sin categoría") as categoria')
                )
                ->leftJoin('tipoarticulos', 'articulos.idTipoArticulo', '=', 'tipoarticulos.idTipoArticulo')
                ->leftJoin('articulo_modelo', 'articulos.idArticulos', '=', 'articulo_modelo.articulo_id')
                ->leftJoin('modelo', function ($join) {
                    $join->on('articulo_modelo.modelo_id', '=', 'modelo.idModelo')
                        ->orOn('articulos.idModelo', '=', 'modelo.idModelo');
                })
                ->leftJoin('categoria', 'modelo.idCategoria', '=', 'categoria.idCategoria')
                ->where('articulos.idArticulos', $request->articulo_id)
                ->first();
        }

        // 3. CONSULTA OPTIMIZADA - SEPARAR EN 2 PARTES
        // Parte A: Obtener ubicaciones base (sin cálculos pesados)
        $ubicacionesBase = DB::table('rack_ubicaciones as ru')
            ->select(
                'ru.idRackUbicacion',
                'ru.codigo',
                'ru.codigo_unico',
                'ru.nivel',
                'ru.posicion',
                'ru.estado_ocupacion',
                'r.nombre as rack_nombre',
                'r.sede',
                'r.tipo_rack'
            )
            ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
            ->where('r.sede', $ubicacionOrigen->sede)
            ->where('r.estado', 'activo')
            ->where('r.tipo_rack', 'panel')
            ->where('ru.idRackUbicacion', '!=', $ubicacionOrigen->idRackUbicacion);

        if ($request->rack_id) {
            $ubicacionesBase->where('r.idRack', $request->rack_id);
        }

        $ubicaciones = $ubicacionesBase->get();

        if ($ubicaciones->isEmpty()) {
            return response()->json([
                'success' => true,
                'articulo_info' => $articuloInfo,
                'ubicacion_origen' => $ubicacionOrigen,
                'ubicaciones_disponibles' => [],
                'total_disponibles' => 0,
            ]);
        }

        // Parte B: Obtener métricas de cajas POR LOTE (mucho más eficiente)
        $idsUbicaciones = $ubicaciones->pluck('idRackUbicacion')->toArray();

        // Consulta optimizada para métricas de cajas
        $metricasCajas = DB::table('cajas')
            ->select(
                'idubicaciones_rack as ubicacion_id',
                DB::raw('COUNT(*) as total_cajas'),
                DB::raw('COALESCE(SUM(cantidad_actual), 0) as total_articulos'),
                DB::raw('COALESCE(SUM(capacidad), 0) as capacidad_total'),
                DB::raw('MAX(CASE WHEN idArticulo = ? THEN 1 ELSE 0 END) as tiene_mismo_articulo')
            )
            ->addBinding($request->articulo_id ?: 0, 'select')
            ->whereIn('idubicaciones_rack', $idsUbicaciones)
            ->groupBy('idubicaciones_rack')
            ->get()
            ->keyBy('ubicacion_id');

        // 4. POST-PROCESAMIENTO EN PHP (más rápido que hacerlo en SQL)
        $ubicacionesProcesadas = $ubicaciones->map(function ($ubicacion) use ($metricasCajas, $request) {
            $metricas = $metricasCajas->get($ubicacion->idRackUbicacion);

            // Si no hay métricas, la ubicación está vacía
            if (!$metricas) {
                $cantidadOcupada = 0;
                $capacidadMaxima = 0;
                $tieneMismoArticulo = false;
            } else {
                $cantidadOcupada = (int) $metricas->total_articulos;
                $capacidadMaxima = (int) $metricas->capacidad_total;
                $tieneMismoArticulo = (bool) $metricas->tiene_mismo_articulo;
            }

            $espacioDisponible = max($capacidadMaxima - $cantidadOcupada, 0);

            // Determinar estado visual
            if ($cantidadOcupada == 0) {
                $claseCss = 'bg-green-100 text-green-800';
                $estadoVisual = 'Vacía';
            } elseif ($tieneMismoArticulo && $request->articulo_id) {
                $claseCss = 'bg-blue-100 text-blue-800';
                $estadoVisual = 'Mismo artículo';
            } else {
                $claseCss = 'bg-yellow-100 text-yellow-800';
                $estadoVisual = 'Ocupada';
            }

            return (object) [
                'idRackUbicacion' => $ubicacion->idRackUbicacion,
                'codigo' => $ubicacion->codigo,
                'codigo_unico' => $ubicacion->codigo_unico,
                'nivel' => $ubicacion->nivel,
                'posicion' => $ubicacion->posicion,
                'estado_ocupacion' => $ubicacion->estado_ocupacion,
                'rack_nombre' => $ubicacion->rack_nombre,
                'sede' => $ubicacion->sede,
                'tipo_rack' => $ubicacion->tipo_rack,

                // Métricas
                'cantidad_ocupada' => $cantidadOcupada,
                'capacidad_maxima' => $capacidadMaxima,
                'espacio_disponible' => $espacioDisponible,
                'tiene_mismo_articulo' => $tieneMismoArticulo,

                // Para frontend
                'clase_css' => $claseCss,
                'estado_visual' => $estadoVisual,
            ];
        });

        // 5. ORDENAR (si es necesario) - Hacerlo en PHP es más eficiente para pocos resultados
        $ubicacionesOrdenadas = $ubicacionesProcesadas->sortBy([
            ['cantidad_ocupada', 'asc'],  // Primero las vacías
            ['nivel', 'asc'],             // Luego por nivel
            ['codigo_unico', 'asc']       // Finalmente por código
        ])->values();

        return response()->json([
            'success' => true,
            'tipo_operacion' => $request->tipo_articulo === 'caja' ? 'mover_caja' : 'mover_articulo',
            'articulo_info' => $articuloInfo,
            'ubicacion_origen' => [
                'id' => $ubicacionOrigen->idRackUbicacion,
                'codigo' => $ubicacionOrigen->codigo,
                'codigo_unico' => $ubicacionOrigen->codigo_unico,
                'rack_nombre' => $ubicacionOrigen->rack_nombre,
                'sede' => $ubicacionOrigen->sede,
                'tipo_rack' => $ubicacionOrigen->tipo_rack,
            ],
            'ubicaciones_disponibles' => $ubicacionesOrdenadas,
            'total_disponibles' => $ubicacionesOrdenadas->count(),
        ]);
    }

    public function moverCajaPanel(Request $request)
    {
        $request->validate([
            'ubicacion_origen_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
            'ubicacion_destino_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
            'caja_id' => 'required|integer|exists:cajas,idCaja',
            'cantidad' => 'required|integer|min:1',
            'observaciones' => 'nullable|string|max:500',
            'tipo_movimiento' => 'required|in:total,parcial'
        ]);

        DB::beginTransaction();

        try {
            $ubicacionOrigenId = $request->input('ubicacion_origen_id');
            $ubicacionDestinoId = $request->input('ubicacion_destino_id');
            $cajaId = $request->input('caja_id');
            $cantidad = $request->input('cantidad');
            $observaciones = $request->input('observaciones', 'Reubicación manual de caja');
            $tipoMovimiento = $request->input('tipo_movimiento', 'total');

            // 1. Obtener información de la CAJA origen
            $cajaOrigen = DB::table('cajas as cj')
                ->leftJoin('articulos as a', 'cj.idArticulo', '=', 'a.idArticulos')
                ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')
                ->select(
                    'cj.*',
                    'a.idArticulos',
                    'a.nombre as articulo_nombre',
                    'a.codigo_barras',
                    'a.codigo_repuesto',
                    'a.stock_total',
                    'ta.nombre as tipo_articulo'
                )
                ->where('cj.idCaja', $cajaId)
                ->first();

            if (!$cajaOrigen) {
                throw new \Exception('Caja no encontrada');
            }

            // 2. Verificar que la cantidad a mover sea válida
            if ($cantidad > $cajaOrigen->cantidad_actual) {
                throw new \Exception('La cantidad a mover no puede ser mayor a la cantidad disponible en la caja');
            }

            // 3. Obtener información de las ubicaciones
            $ubicacionOrigen = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->where('ru.idRackUbicacion', $ubicacionOrigenId)
                ->select('ru.*', 'r.nombre as rack_nombre')
                ->first();

            $ubicacionDestino = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->where('ru.idRackUbicacion', $ubicacionDestinoId)
                ->select('ru.*', 'r.nombre as rack_nombre')
                ->first();

            // 4. Verificar si ya existe una caja con el mismo artículo en la ubicación destino
            $cajaExistenteDestino = DB::table('cajas')
                ->where('idubicaciones_rack', $ubicacionDestinoId)
                ->where('idArticulo', $cajaOrigen->idArticulo)
                ->where('idTipoArticulo', $cajaOrigen->idTipoArticulo)
                ->where('es_custodia', $cajaOrigen->es_custodia)
                ->first();

            // ✅ FUNCIÓN PARA GENERAR NOMBRE ALEATORIO DE 4 DÍGITOS
            function generarNombreCajaAleatorio()
            {
                $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $codigo = '';
                for ($i = 0; $i < 4; $i++) {
                    $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
                }
                return 'CAJA-' . $codigo . '-' . date('YmdHis');
            }

            // 5. Procesar según el tipo de movimiento
            if ($tipoMovimiento === 'total') {
                // MOVER TODA LA CAJA a nueva ubicación

                // Actualizar ubicación de la caja existente
                DB::table('cajas')
                    ->where('idCaja', $cajaId)
                    ->update([
                        'idubicaciones_rack' => $ubicacionDestinoId
                    ]);

                // Si hay caja existente en destino, fusionar
                if ($cajaExistenteDestino) {
                    // Sumar cantidades
                    DB::table('cajas')
                        ->where('idCaja', $cajaExistenteDestino->idCaja)
                        ->update([
                            'cantidad_actual' => $cajaExistenteDestino->cantidad_actual + $cajaOrigen->cantidad_actual
                        ]);

                    // Eliminar la caja original (ya fusionada)
                    DB::table('cajas')
                        ->where('idCaja', $cajaId)
                        ->delete();

                    $nombreCajaResultado = $cajaExistenteDestino->nombre;
                } else {
                    $nombreCajaResultado = $cajaOrigen->nombre;
                }
            } else {
                // MOVER PARCIALMENTE (solo cierta cantidad)

                // Reducir cantidad en caja origen
                $nuevaCantidadOrigen = $cajaOrigen->cantidad_actual - $cantidad;

                if ($nuevaCantidadOrigen > 0) {
                    DB::table('cajas')
                        ->where('idCaja', $cajaId)
                        ->update([
                            'cantidad_actual' => $nuevaCantidadOrigen
                        ]);
                    $nombreCajaOrigen = $cajaOrigen->nombre;
                } else {
                    // Si se mueve toda la cantidad, marcar como cerrada
                    DB::table('cajas')
                        ->where('idCaja', $cajaId)
                        ->update([
                            'cantidad_actual' => 0,
                            'estado' => 'cerrada'
                        ]);
                    $nombreCajaOrigen = $cajaOrigen->nombre . ' (Vacía)';
                }

                // Crear o actualizar caja en destino
                if ($cajaExistenteDestino) {
                    // Si ya existe, sumar la cantidad
                    DB::table('cajas')
                        ->where('idCaja', $cajaExistenteDestino->idCaja)
                        ->update([
                            'cantidad_actual' => $cajaExistenteDestino->cantidad_actual + $cantidad
                        ]);
                    $nombreCajaDestino = $cajaExistenteDestino->nombre;
                } else {
                    // ✅ GENERAR NOMBRE ALEATORIO PARA NUEVA CAJA
                    $nombreNuevaCaja = generarNombreCajaAleatorio();

                    // Crear nueva caja en destino
                    $nuevaCajaId = DB::table('cajas')->insertGetId([
                        'idModeloCaja' => $cajaOrigen->idModeloCaja,
                        'idArticulo' => $cajaOrigen->idArticulo,
                        'idTipoArticulo' => $cajaOrigen->idTipoArticulo,
                        'cantidad_actual' => $cantidad,
                        'capacidad' => $cajaOrigen->capacidad,
                        'estado' => $cajaOrigen->estado,
                        'es_custodia' => $cajaOrigen->es_custodia,
                        'idAsignacion_rack' => $cajaOrigen->idAsignacion_rack,
                        'idubicaciones_rack' => $ubicacionDestinoId,
                        'orden_en_pallet' => $cajaOrigen->orden_en_pallet,
                        'fecha_entrada' => now(),
                        'nombre' => $nombreNuevaCaja
                    ]);
                    $nombreCajaDestino = $nombreNuevaCaja;
                }

                $nombreCajaResultado = $nombreCajaDestino;
            }

            // 6. Actualizar registros en rack_ubicacion_articulos
            $this->actualizarRackUbicacionArticulosPorCaja(
                $ubicacionOrigenId,
                $ubicacionDestinoId,
                $cajaOrigen->idArticulo,
                $cajaOrigen->es_custodia ? $cajaOrigen->idArticulo : null,
                $cantidad
            );

            // 7. Actualizar estados de ocupación de las ubicaciones
            $this->actualizarEstadoUbicacionPanel($ubicacionOrigenId);
            $this->actualizarEstadoUbicacionPanel($ubicacionDestinoId);

            // 8. Registrar el movimiento en el historial
            $movimientoId = DB::table('rack_movimientos')->insertGetId([
                'rack_origen_id' => $ubicacionOrigen->rack_id,
                'rack_destino_id' => $ubicacionDestino->rack_id,
                'ubicacion_origen_id' => $ubicacionOrigenId,
                'ubicacion_destino_id' => $ubicacionDestinoId,
                'articulo_id' => $cajaOrigen->idArticulo,
                'custodia_id' => $cajaOrigen->es_custodia ? $cajaOrigen->idArticulo : null,
                'cantidad' => $cantidad,
                'tipo_movimiento' => 'reubicacion',
                'observaciones' => $observaciones . ' - Caja: ' . ($tipoMovimiento === 'total' ? $cajaOrigen->nombre : $nombreCajaResultado) . ' (' . $cajaOrigen->articulo_nombre . ')',
                'codigo_ubicacion_origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                'codigo_ubicacion_destino' => $ubicacionDestino->codigo_unico ?? $ubicacionDestino->codigo,
                'nombre_rack_origen' => $ubicacionOrigen->rack_nombre,
                'nombre_rack_destino' => $ubicacionDestino->rack_nombre,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Caja movida exitosamente',
                'data' => [
                    'movimiento_id' => $movimientoId,
                    'caja' => $nombreCajaResultado,
                    'articulo' => $cajaOrigen->articulo_nombre,
                    'cantidad' => $cantidad,
                    'ubicacion_origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                    'ubicacion_destino' => $ubicacionDestino->codigo_unico ?? $ubicacionDestino->codigo,
                    'rack_origen' => $ubicacionOrigen->rack_nombre,
                    'rack_destino' => $ubicacionDestino->rack_nombre,
                    'fecha' => now()->format('d/m/Y H:i:s'),
                    'tipo_movimiento' => $tipoMovimiento,
                    'nueva_caja' => isset($nombreNuevaCaja) ? $nombreNuevaCaja : null
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al mover caja: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al mover la caja: ' . $e->getMessage()
            ], 500);
        }
    }



    public function moverArticuloEnCajaPanel(Request $request)
    {
        $request->validate([
            'ubicacion_origen_id'  => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
            'ubicacion_destino_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
            'caja_id'              => 'required|integer|exists:cajas,idCaja',
            'articulo_id'          => 'required|integer|exists:articulos,idArticulos',
            'cantidad'             => 'required|integer|min:1',
            'observaciones'        => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();

        try {
            $ubicacionOrigenId  = $request->ubicacion_origen_id;
            $ubicacionDestinoId = $request->ubicacion_destino_id;
            $cajaId             = $request->caja_id;
            $articuloId         = $request->articulo_id;
            $cantidad           = $request->cantidad;

            /* ================= CAJA ORIGEN ================= */
            $caja = DB::table('cajas')->where('idCaja', $cajaId)->first();

            if (!$caja || $caja->idubicaciones_rack != $ubicacionOrigenId) {
                throw new \Exception('La caja no se encuentra en la ubicación origen');
            }

            if ($caja->idArticulo != $articuloId) {
                throw new \Exception('El artículo no se encuentra en esta caja');
            }

            if ($cantidad > $caja->cantidad_actual) {
                throw new \Exception('Cantidad insuficiente en la caja');
            }

            /* ================= UBICACIONES ================= */
            $ubicacionOrigen = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->where('ru.idRackUbicacion', $ubicacionOrigenId)
                ->select('ru.*', 'r.nombre as rack_nombre')
                ->first();

            $ubicacionDestino = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->where('ru.idRackUbicacion', $ubicacionDestinoId)
                ->select('ru.*', 'r.nombre as rack_nombre')
                ->first();

            /* ================= RESTAR CAJA ORIGEN ================= */
            $nuevaCantidadCaja = $caja->cantidad_actual - $cantidad;

            if ($nuevaCantidadCaja > 0) {
                DB::table('cajas')->where('idCaja', $cajaId)
                    ->update(['cantidad_actual' => $nuevaCantidadCaja]);
            } else {
                DB::table('cajas')->where('idCaja', $cajaId)
                    ->update([
                        'cantidad_actual' => 0,
                        'estado' => 'cerrada',
                        'idubicaciones_rack' => null
                    ]);
            }

            /* ================= CAJA DESTINO ================= */
            $cajaDestino = DB::table('cajas')
                ->where('idubicaciones_rack', $ubicacionDestinoId)
                ->where('idArticulo', $articuloId)
                ->where('idTipoArticulo', $caja->idTipoArticulo)
                ->where('es_custodia', $caja->es_custodia)
                ->first();

            $capacidad = $caja->capacidad;
            $nombreNuevaCaja = null;

            if ($cajaDestino) {

                $disponible = $capacidad - $cajaDestino->cantidad_actual;
                $cantidadAEntrar = min($cantidad, $disponible);
                $sobrante = $cantidad - $cantidadAEntrar;

                if ($cantidadAEntrar > 0) {
                    DB::table('cajas')->where('idCaja', $cajaDestino->idCaja)
                        ->update([
                            'cantidad_actual' => $cajaDestino->cantidad_actual + $cantidadAEntrar
                        ]);
                }
            } else {
                $sobrante = $cantidad;
            }

            /* ================= NUEVA CAJA SI SOBRA ================= */
            if ($sobrante > 0) {

                do {
                    $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $nombreNuevaCaja = '';
                    for ($i = 0; $i < 4; $i++) {
                        $nombreNuevaCaja .= $chars[random_int(0, strlen($chars) - 1)];
                    }
                } while (DB::table('cajas')->where('nombre', $nombreNuevaCaja)->exists());

                DB::table('cajas')->insert([
                    'idModeloCaja'       => $caja->idModeloCaja,
                    'idArticulo'         => $articuloId,
                    'idTipoArticulo'     => $caja->idTipoArticulo,
                    'cantidad_actual'    => $sobrante,
                    'capacidad'          => $capacidad,
                    'estado'             => 'abierta',
                    'es_custodia'        => $caja->es_custodia,
                    'idAsignacion_rack'  => $caja->idAsignacion_rack,
                    'idubicaciones_rack' => $ubicacionDestinoId,
                    'orden_en_pallet'    => $caja->orden_en_pallet,
                    'fecha_entrada'      => now(),
                    'nombre'             => $nombreNuevaCaja
                ]);
            }

            /* ================= STOCK POR UBICACIÓN ================= */
            $this->actualizarRackUbicacionArticulosPorCaja(
                $ubicacionOrigenId,
                $ubicacionDestinoId,
                $articuloId,
                $caja->es_custodia ? $caja->idCaja : null,
                $cantidad
            );

            $this->actualizarEstadoUbicacionPanel($ubicacionOrigenId);
            $this->actualizarEstadoUbicacionPanel($ubicacionDestinoId);

            /* ================= MOVIMIENTO ================= */
            $articuloInfo = DB::table('articulos')->where('idArticulos', $articuloId)->first();

            DB::table('rack_movimientos')->insert([
                'rack_origen_id' => $ubicacionOrigen->rack_id,
                'rack_destino_id' => $ubicacionDestino->rack_id,
                'ubicacion_origen_id' => $ubicacionOrigenId,
                'ubicacion_destino_id' => $ubicacionDestinoId,
                'articulo_id' => $articuloId,
                'cantidad' => $cantidad,
                'tipo_movimiento' => 'reubicacion',
                'observaciones' => trim(
                    ($request->observaciones ?? '') .
                        ' - Artículo desde caja: ' . ($articuloInfo->nombre ?? 'Artículo') .
                        ($nombreNuevaCaja ? ' (Nueva caja: ' . $nombreNuevaCaja . ')' : '')
                ),
                'codigo_ubicacion_origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                'codigo_ubicacion_destino' => $ubicacionDestino->codigo_unico ?? $ubicacionDestino->codigo,
                'nombre_rack_origen' => $ubicacionOrigen->rack_nombre,
                'nombre_rack_destino' => $ubicacionDestino->rack_nombre,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    private function actualizarRackUbicacionArticulosPorCaja($ubicacionOrigenId, $ubicacionDestinoId, $articuloId, $custodiaId, $cantidad)
    {
        try {
            // 1. Reducir en ubicación origen
            $registroOrigen = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $ubicacionOrigenId)
                ->where('articulo_id', $articuloId);

            // Solo aplicar filtro por custodia_id si no es null
            if ($custodiaId !== null) {
                $registroOrigen = $registroOrigen->where('custodia_id', $custodiaId);
            }

            $registroOrigen = $registroOrigen->first();

            if ($registroOrigen) {
                $nuevaCantidadOrigen = $registroOrigen->cantidad - $cantidad;

                if ($nuevaCantidadOrigen > 0) {
                    DB::table('rack_ubicacion_articulos')
                        ->where('idRackUbicacionArticulo', $registroOrigen->idRackUbicacionArticulo)
                        ->update([
                            'cantidad' => $nuevaCantidadOrigen,
                            'updated_at' => now()
                        ]);
                } else {
                    DB::table('rack_ubicacion_articulos')
                        ->where('idRackUbicacionArticulo', $registroOrigen->idRackUbicacionArticulo)
                        ->delete();
                }
            }

            // 2. Aumentar en ubicación destino
            $registroDestino = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $ubicacionDestinoId)
                ->where('articulo_id', $articuloId);

            // Solo aplicar filtro por custodia_id si no es null
            if ($custodiaId !== null) {
                $registroDestino = $registroDestino->where('custodia_id', $custodiaId);
            }

            $registroDestino = $registroDestino->first();

            if ($registroDestino) {
                DB::table('rack_ubicacion_articulos')
                    ->where('idRackUbicacionArticulo', $registroDestino->idRackUbicacionArticulo)
                    ->update([
                        'cantidad' => $registroDestino->cantidad + $cantidad,
                        'updated_at' => now()
                    ]);
            } else {
                DB::table('rack_ubicacion_articulos')->insert([
                    'rack_ubicacion_id' => $ubicacionDestinoId,
                    'articulo_id' => $articuloId,
                    'custodia_id' => $custodiaId,
                    'cliente_general_id' => null,
                    'cantidad' => $cantidad,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'ubicacion2' => 0 // Campo adicional según tu tabla
                ]);
            }

            Log::info('rack_ubicacion_articulos actualizado', [
                'ubicacion_origen_id' => $ubicacionOrigenId,
                'ubicacion_destino_id' => $ubicacionDestinoId,
                'articulo_id' => $articuloId,
                'custodia_id' => $custodiaId,
                'cantidad' => $cantidad
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error en actualizarRackUbicacionArticulosPorCaja', [
                'error' => $e->getMessage(),
                'params' => func_get_args()
            ]);
            throw $e;
        }
    }


    public function moverProductoPanel(Request $request)
    {
        $request->validate([
            'ubicacion_origen_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
            'ubicacion_destino_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
            'rack_ubicacion_articulo_id' => 'required|integer|exists:rack_ubicacion_articulos,idRackUbicacionArticulo',
            'cantidad' => 'required|integer|min:1',
            'observaciones' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $ubicacionOrigenId = $request->input('ubicacion_origen_id');
            $ubicacionDestinoId = $request->input('ubicacion_destino_id');
            $rackUbicacionArticuloId = $request->input('rack_ubicacion_articulo_id');
            $cantidad = $request->input('cantidad');
            $observaciones = $request->input('observaciones', 'Reubicación manual');

            // 1. Obtener el registro del ARTÍCULO en la ubicación origen
            $articuloOrigen = DB::table('rack_ubicacion_articulos as rua')
                ->leftJoin('articulos as a', 'rua.articulo_id', '=', 'a.idArticulos')
                ->leftJoin('custodias as c', 'rua.custodia_id', '=', 'c.id')
                ->select(
                    'rua.*',
                    'a.nombre as nombre_articulo',
                    'a.codigo_repuesto',
                    'c.serie',
                    'c.codigocustodias'
                )
                ->where('rua.idRackUbicacionArticulo', $rackUbicacionArticuloId)
                ->first();

            if (!$articuloOrigen) {
                throw new \Exception('Artículo no encontrado en la ubicación origen');
            }

            // 2. Verificar que la cantidad a mover sea válida
            if ($cantidad > $articuloOrigen->cantidad) {
                throw new \Exception('La cantidad a mover no puede ser mayor a la cantidad disponible');
            }

            // 3. Obtener información de las ubicaciones
            $ubicacionOrigen = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->where('ru.idRackUbicacion', $ubicacionOrigenId)
                ->select('ru.*', 'r.nombre as rack_nombre')
                ->first();

            $ubicacionDestino = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->where('ru.idRackUbicacion', $ubicacionDestinoId)
                ->select('ru.*', 'r.nombre as rack_nombre')
                ->first();

            // 4. Actualizar la cantidad en la ubicación origen
            $nuevaCantidadOrigen = $articuloOrigen->cantidad - $cantidad;

            if ($nuevaCantidadOrigen > 0) {
                // Reducir la cantidad
                DB::table('rack_ubicacion_articulos')
                    ->where('idRackUbicacionArticulo', $rackUbicacionArticuloId)
                    ->update(['cantidad' => $nuevaCantidadOrigen]);
            } else {
                // Eliminar el registro si la cantidad llega a 0
                DB::table('rack_ubicacion_articulos')
                    ->where('idRackUbicacionArticulo', $rackUbicacionArticuloId)
                    ->delete();
            }

            // 5. Crear o actualizar el registro en la ubicación destino
            $articuloDestino = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $ubicacionDestinoId)
                ->where('articulo_id', $articuloOrigen->articulo_id)
                ->where('custodia_id', $articuloOrigen->custodia_id)
                ->where('cliente_general_id', $articuloOrigen->cliente_general_id)
                ->first();

            if ($articuloDestino) {
                // Si ya existe, sumar la cantidad
                DB::table('rack_ubicacion_articulos')
                    ->where('idRackUbicacionArticulo', $articuloDestino->idRackUbicacionArticulo)
                    ->update([
                        'cantidad' => $articuloDestino->cantidad + $cantidad,
                        'updated_at' => now()
                    ]);
            } else {
                // Si no existe, crear nuevo registro
                DB::table('rack_ubicacion_articulos')->insert([
                    'rack_ubicacion_id' => $ubicacionDestinoId,
                    'articulo_id' => $articuloOrigen->articulo_id,
                    'custodia_id' => $articuloOrigen->custodia_id,
                    'cliente_general_id' => $articuloOrigen->cliente_general_id,
                    'cantidad' => $cantidad,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // 6. Actualizar estados de ocupación de las ubicaciones
            $this->actualizarEstadoUbicacionPanel($ubicacionOrigenId);
            $this->actualizarEstadoUbicacionPanel($ubicacionDestinoId);

            // 7. Registrar el movimiento en el historial
            $movimientoId = DB::table('rack_movimientos')->insertGetId([
                'rack_origen_id' => $ubicacionOrigen->rack_id,
                'rack_destino_id' => $ubicacionDestino->rack_id,
                'ubicacion_origen_id' => $ubicacionOrigenId,
                'ubicacion_destino_id' => $ubicacionDestinoId,
                'articulo_id' => $articuloOrigen->articulo_id,
                'custodia_id' => $articuloOrigen->custodia_id,
                'cantidad' => $cantidad,
                'tipo_movimiento' => 'reubicacion',
                'observaciones' => $observaciones . ' - Artículo: ' . ($articuloOrigen->articulo_id ?: $articuloOrigen->custodia_id),
                'codigo_ubicacion_origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                'codigo_ubicacion_destino' => $ubicacionDestino->codigo_unico ?? $ubicacionDestino->codigo,
                'nombre_rack_origen' => $ubicacionOrigen->rack_nombre,
                'nombre_rack_destino' => $ubicacionDestino->rack_nombre,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 8. Obtener información del ARTÍCULO para la respuesta
            $nombreArticulo = $articuloOrigen->nombre_articulo ?: ($articuloOrigen->codigo_repuesto ?: ($articuloOrigen->serie ?: ($articuloOrigen->codigocustodias ?: 'Artículo ' . ($articuloOrigen->articulo_id ?: $articuloOrigen->custodia_id))));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Artículo movido exitosamente',
                'data' => [
                    'movimiento_id' => $movimientoId,
                    'articulo' => $nombreArticulo,
                    'cantidad' => $cantidad,
                    'ubicacion_origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                    'ubicacion_destino' => $ubicacionDestino->codigo_unico ?? $ubicacionDestino->codigo,
                    'rack_origen' => $ubicacionOrigen->rack_nombre,
                    'rack_destino' => $ubicacionDestino->rack_nombre,
                    'fecha' => now()->format('d/m/Y H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al mover artículo: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al mover el artículo: ' . $e->getMessage()
            ], 500);
        }
    }



    private function actualizarEstadoUbicacionPanel($ubicacionId)
    {
        // Calcular la cantidad total de productos en la ubicación
        $cantidadTotal = DB::table('rack_ubicacion_articulos')
            ->where('rack_ubicacion_id', $ubicacionId)
            ->sum('cantidad');

        // Determinar el estado - SOLO USAR VALORES DEL ENUM!
        // 'ocupado' NO existe, usa 'medio' en su lugar
        $estado = $cantidadTotal > 0 ? 'medio' : 'vacio';

        // Actualizar la ubicación
        DB::table('rack_ubicaciones')
            ->where('idRackUbicacion', $ubicacionId)
            ->update([
                'estado_ocupacion' => $estado,
                'cantidad_actual' => $cantidadTotal, // También actualiza este campo
                'updated_at' => now()
            ]);

        return $estado;
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

    public function iniciarReubicacionMultiple(Request $request)
    {
        DB::beginTransaction();
        try {
            Log::info('🚀 ========== INICIO iniciarReubicacionMultiple ==========');
            Log::debug('📥 DATOS RECIBIDOS para reubicación múltiple:', $request->all());

            // Validar datos
            $validator = Validator::make($request->all(), [
                'ubicacion_origen_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $ubicacionOrigenId = (int) $request->ubicacion_origen_id;

            // Obtener información de la ubicación origen
            $ubicacionOrigen = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->select('ru.*', 'r.nombre as rack_nombre')
                ->where('ru.idRackUbicacion', $ubicacionOrigenId)
                ->first();

            if (!$ubicacionOrigen) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Ubicación origen no encontrada'
                ], 404);
            }

            // Obtener TODOS los productos de la ubicación (normales y custodias)
            $productosEnUbicacion = DB::table('rack_ubicacion_articulos as rua')
                ->leftJoin('articulos as a', 'rua.articulo_id', '=', 'a.idArticulos')
                ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')
                ->leftJoin('custodias as cust', 'rua.custodia_id', '=', 'cust.id')
                ->leftJoin('clientegeneral as cg_articulo', 'rua.cliente_general_id', '=', 'cg_articulo.idClienteGeneral')
                ->leftJoin('tickets as t_cust', 'cust.numero_ticket', '=', 't_cust.numero_ticket')
                ->leftJoin('clientegeneral as cg_custodia', 't_cust.idClienteGeneral', '=', 'cg_custodia.idClienteGeneral')
                ->where('rua.rack_ubicacion_id', $ubicacionOrigenId)
                ->select(
                    'rua.idRackUbicacionArticulo',
                    'rua.articulo_id',
                    'rua.custodia_id',
                    'rua.cliente_general_id',
                    'rua.cantidad',
                    'a.nombre as nombre_articulo',
                    'a.codigo_repuesto',
                    'ta.nombre as tipo_articulo',
                    'ta.idTipoArticulo',
                    'cust.serie as serie_custodia',
                    'cust.codigocustodias as codigo_custodia',
                    'cg_articulo.descripcion as cliente_articulo',
                    'cg_custodia.descripcion as cliente_custodia'
                )
                ->get();

            if ($productosEnUbicacion->isEmpty()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No hay productos para reubicar en esta ubicación'
                ], 404);
            }

            // Preparar datos para la respuesta
            $productosAgrupados = [];
            $cantidadTotal = 0;

            foreach ($productosEnUbicacion as $producto) {
                $nombreProducto = '';
                $clienteNombre = 'Sin cliente';
                $esCustodia = !is_null($producto->custodia_id);

                if ($esCustodia) {
                    // Es una custodia
                    $nombreProducto = $producto->serie_custodia ?: $producto->codigo_custodia ?: 'Custodia ' . $producto->custodia_id;
                    $clienteNombre = $producto->cliente_custodia ?: 'Sin cliente';
                } else {
                    // Es un producto normal
                    $mostrandoCodigoRepuesto = ($producto->idTipoArticulo == 2 && !empty($producto->codigo_repuesto));
                    $nombreProducto = $mostrandoCodigoRepuesto ? $producto->codigo_repuesto : $producto->nombre_articulo;
                    $clienteNombre = $producto->cliente_articulo ?: 'Sin cliente';
                }

                $productosAgrupados[] = [
                    'articulo_id' => $producto->articulo_id,
                    'custodia_id' => $producto->custodia_id,
                    'cliente_general_id' => $producto->cliente_general_id,
                    'nombre' => $nombreProducto,
                    'cliente_nombre' => $clienteNombre,
                    'cantidad' => $producto->cantidad,
                    'es_custodia' => $esCustodia,
                    'tipo_articulo' => $esCustodia ? 'CUSTODIA' : $producto->tipo_articulo
                ];

                $cantidadTotal += $producto->cantidad;
            }

            $responseData = [
                'ubicacion_origen' => [
                    'id' => $ubicacionOrigen->idRackUbicacion,
                    'codigo' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                    'rack_nombre' => $ubicacionOrigen->rack_nombre,
                    'productos' => $productosAgrupados,
                    'cantidad_total' => $cantidadTotal,
                    'total_productos' => count($productosAgrupados)
                ]
            ];

            Log::info('🎉 REUBICACIÓN MÚLTIPLE INICIADA EXITOSAMENTE:', [
                'ubicacion_origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                'total_productos' => count($productosAgrupados),
                'cantidad_total' => $cantidadTotal
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Modo reubicación múltiple activado',
                'data' => $responseData
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('💥 ERROR en iniciarReubicacionMultiple:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function confirmarReubicacionMultiple(Request $request)
    {
        DB::beginTransaction();
        try {
            Log::info('🚀 ========== INICIO confirmarReubicacionMultiple ==========');
            Log::debug('📥 DATOS RECIBIDOS para confirmar reubicación múltiple:', $request->all());

            // Validar datos
            $validator = Validator::make($request->all(), [
                'ubicacion_origen_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
                'ubicacion_destino_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $ubicacionOrigenId = (int) $request->ubicacion_origen_id;
            $ubicacionDestinoId = (int) $request->ubicacion_destino_id;

            // Verificar que las ubicaciones sean diferentes
            if ($ubicacionOrigenId == $ubicacionDestinoId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes reubicar a la misma ubicación'
                ], 422);
            }

            // Obtener información de ambas ubicaciones
            $ubicacionOrigen = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->select('ru.*', 'r.nombre as rack_nombre', 'r.idRack as rack_id')
                ->where('ru.idRackUbicacion', $ubicacionOrigenId)
                ->first();

            $ubicacionDestino = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->select('ru.*', 'r.nombre as rack_nombre', 'r.idRack as rack_id')
                ->where('ru.idRackUbicacion', $ubicacionDestinoId)
                ->first();

            // Verificar si la ubicación destino está vacía
            $productosEnDestino = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $ubicacionDestinoId)
                ->exists();

            if ($productosEnDestino) {
                return response()->json([
                    'success' => false,
                    'message' => 'La ubicación destino ya está ocupada'
                ], 422);
            }

            // Obtener todos los productos de la ubicación origen
            $productosEnOrigen = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $ubicacionOrigenId)
                ->get();

            if ($productosEnOrigen->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay productos para reubicar en la ubicación origen'
                ], 404);
            }

            $productosMovidos = [];
            $cantidadTotalMovida = 0;

            // Mover cada producto individualmente
            foreach ($productosEnOrigen as $producto) {
                if ($producto->custodia_id) {
                    // Mover custodia
                    DB::table('rack_ubicacion_articulos')
                        ->where('rack_ubicacion_id', $ubicacionOrigenId)
                        ->where('custodia_id', $producto->custodia_id)
                        ->update([
                            'rack_ubicacion_id' => $ubicacionDestinoId,
                            'updated_at' => now()
                        ]);

                    // ✅ CORREGIDO: Usar 'reubicacion_custodia' que ya existe en el ENUM
                    DB::table('rack_movimientos')->insert([
                        'articulo_id' => null,
                        'custodia_id' => $producto->custodia_id,
                        'ubicacion_origen_id' => $ubicacionOrigenId,
                        'ubicacion_destino_id' => $ubicacionDestinoId,
                        'rack_origen_id' => $ubicacionOrigen->rack_id,
                        'rack_destino_id' => $ubicacionDestino->rack_id,
                        'cantidad' => 1,
                        'tipo_movimiento' => 'reubicacion_custodia', // ✅ TIPO EXISTENTE
                        'usuario_id' => auth()->id() ?? 1,
                        'observaciones' => 'Reubicación múltiple - Custodia: ' . ($producto->custodia_id),
                        'codigo_ubicacion_origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                        'codigo_ubicacion_destino' => $ubicacionDestino->codigo_unico ?? $ubicacionDestino->codigo,
                        'nombre_rack_origen' => $ubicacionOrigen->rack_nombre,
                        'nombre_rack_destino' => $ubicacionDestino->rack_nombre,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $productosMovidos[] = [
                        'tipo' => 'custodia',
                        'custodia_id' => $producto->custodia_id,
                        'cantidad' => 1
                    ];
                    $cantidadTotalMovida += 1;
                } else {
                    // Mover producto normal
                    DB::table('rack_ubicacion_articulos')
                        ->where('rack_ubicacion_id', $ubicacionOrigenId)
                        ->where('articulo_id', $producto->articulo_id)
                        ->where('cliente_general_id', $producto->cliente_general_id)
                        ->update([
                            'rack_ubicacion_id' => $ubicacionDestinoId,
                            'updated_at' => now()
                        ]);

                    // Obtener nombre del cliente para el historial
                    $clienteInfo = DB::table('clientegeneral')
                        ->where('idClienteGeneral', $producto->cliente_general_id)
                        ->first();

                    // ✅ CORREGIDO: Usar 'reubicacion' que ya existe en el ENUM
                    DB::table('rack_movimientos')->insert([
                        'articulo_id' => $producto->articulo_id,
                        'custodia_id' => null,
                        'ubicacion_origen_id' => $ubicacionOrigenId,
                        'ubicacion_destino_id' => $ubicacionDestinoId,
                        'rack_origen_id' => $ubicacionOrigen->rack_id,
                        'rack_destino_id' => $ubicacionDestino->rack_id,
                        'cantidad' => $producto->cantidad,
                        'tipo_movimiento' => 'reubicacion', // ✅ TIPO EXISTENTE
                        'usuario_id' => auth()->id() ?? 1,
                        'observaciones' => 'Reubicación múltiple - Producto: ' . ($producto->articulo_id ?? 'N/A') . ' - Cliente: ' . ($clienteInfo->descripcion ?? 'Sin cliente'),
                        'codigo_ubicacion_origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                        'codigo_ubicacion_destino' => $ubicacionDestino->codigo_unico ?? $ubicacionDestino->codigo,
                        'nombre_rack_origen' => $ubicacionOrigen->rack_nombre,
                        'nombre_rack_destino' => $ubicacionDestino->rack_nombre,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $productosMovidos[] = [
                        'tipo' => 'producto',
                        'articulo_id' => $producto->articulo_id,
                        'cliente_general_id' => $producto->cliente_general_id,
                        'cantidad' => $producto->cantidad
                    ];
                    $cantidadTotalMovida += $producto->cantidad;
                }
            }

            // Actualizar estados de ocupación
            $this->actualizarEstadoOcupacion($ubicacionOrigenId);
            $this->actualizarEstadoOcupacion($ubicacionDestinoId);

            // Obtener datos actualizados
            $ubicacionOrigenActualizada = $this->obtenerUbicacionConProductos($ubicacionOrigenId);
            $ubicacionDestinoActualizada = $this->obtenerUbicacionConProductos($ubicacionDestinoId);

            DB::commit();

            Log::info('🎉 REUBICACIÓN MÚLTIPLE COMPLETADA EXITOSAMENTE:', [
                'productos_movidos' => count($productosMovidos),
                'cantidad_total_movida' => $cantidadTotalMovida
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reubicación múltiple completada exitosamente',
                'data' => [
                    'origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                    'destino' => $ubicacionDestino->codigo_unico ?? $ubicacionDestino->codigo,
                    'productos_movidos' => $productosMovidos,
                    'cantidad_total' => $cantidadTotalMovida,
                    'total_productos' => count($productosMovidos),
                    'ubicaciones_actualizadas' => [
                        'origen' => $ubicacionOrigenActualizada,
                        'destino' => $ubicacionDestinoActualizada
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('💥 ERROR en confirmarReubicacionMultiple:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function confirmarReubicacionEntreRacks(Request $request)
    {
        DB::beginTransaction();
        try {
            Log::info('🚀 ========== INICIO confirmarReubicacionEntreRacks ==========');
            Log::debug('📥 DATOS RECIBIDOS para reubicación entre racks:', $request->all());

            // Validar datos
            $validator = Validator::make($request->all(), [
                'ubicacion_origen_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
                'ubicacion_destino_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
                'producto' => 'required|string|min:1|max:255',
                'cantidad' => 'required|integer|min:1',
                'tipo_reubicacion' => 'required|in:otro_rack'
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $ubicacionOrigenId = (int) $request->ubicacion_origen_id;
            $ubicacionDestinoId = (int) $request->ubicacion_destino_id;

            // Obtener información de ambas ubicaciones
            $ubicacionOrigen = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->select('ru.*', 'r.nombre as rack_nombre', 'r.idRack as rack_id')
                ->where('ru.idRackUbicacion', $ubicacionOrigenId)
                ->first();

            $ubicacionDestino = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->select('ru.*', 'r.nombre as rack_nombre', 'r.idRack as rack_id')
                ->where('ru.idRackUbicacion', $ubicacionDestinoId)
                ->first();

            if (!$ubicacionOrigen || !$ubicacionDestino) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Una de las ubicaciones no existe'
                ], 404);
            }

            // Verificar que la ubicación destino esté vacía
            $productosEnDestino = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $ubicacionDestinoId)
                ->exists();

            if ($productosEnDestino) {
                return response()->json([
                    'success' => false,
                    'message' => 'La ubicación destino ya está ocupada'
                ], 422);
            }

            // Mover todos los productos de la ubicación origen a la destino
            $productosMovidos = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $ubicacionOrigenId)
                ->update([
                    'rack_ubicacion_id' => $ubicacionDestinoId,
                    'updated_at' => now()
                ]);

            if ($productosMovidos === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay productos para mover en la ubicación origen'
                ], 404);
            }

            // ✅ CORREGIDO: Usar un valor válido del enum
            DB::table('rack_movimientos')->insert([
                'articulo_id' => null,
                'custodia_id' => null,
                'ubicacion_origen_id' => $ubicacionOrigenId,
                'ubicacion_destino_id' => $ubicacionDestinoId,
                'rack_origen_id' => $ubicacionOrigen->rack_id,
                'rack_destino_id' => $ubicacionDestino->rack_id,
                'cantidad' => $request->cantidad,
                'tipo_movimiento' => 'reubicacion', // ✅ VALOR VÁLIDO DEL ENUM
                'usuario_id' => auth()->id() ?? 1,
                'observaciones' => 'Reubicación completa entre racks - Artículo: ' . $request->producto, // ✅ Cambié "Producto" por "Artículo"
                'codigo_ubicacion_origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                'codigo_ubicacion_destino' => $ubicacionDestino->codigo_unico ?? $ubicacionDestino->codigo,
                'nombre_rack_origen' => $ubicacionOrigen->rack_nombre,
                'nombre_rack_destino' => $ubicacionDestino->rack_nombre,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Actualizar estados de ocupación
            $this->actualizarEstadoOcupacion($ubicacionOrigenId);
            $this->actualizarEstadoOcupacion($ubicacionDestinoId);

            DB::commit();

            Log::info('🎉 REUBICACIÓN ENTRE RACKS COMPLETADA EXITOSAMENTE');

            return response()->json([
                'success' => true,
                'message' => 'Reubicación entre racks completada exitosamente',
                'data' => [
                    'productos_movidos' => $productosMovidos,
                    'origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                    'destino' => $ubicacionDestino->codigo_unico ?? $ubicacionDestino->codigo
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('💥 ERROR en confirmarReubicacionEntreRacks:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }


    public function obtenerRacksDisponibles()
    {
        try {
            $racks = DB::table('racks as r')
                ->select('r.idRack as id', 'r.nombre', 'r.sede')
                ->where('r.estado', 'activo')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $racks
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar racks: ' . $e->getMessage()
            ], 500);
        }
    }

    public function obtenerUbicacionesVacias($rackId)
    {
        try {
            $ubicaciones = DB::table('rack_ubicaciones as ru')
                ->leftJoin('rack_ubicacion_articulos as rua', 'ru.idRackUbicacion', '=', 'rua.rack_ubicacion_id')
                ->select(
                    'ru.idRackUbicacion as id',
                    'ru.codigo_unico as codigo',
                    'ru.capacidad_maxima as capacidad_maxima',
                    DB::raw('COALESCE(SUM(rua.cantidad), 0) as cantidad_total_articulos'),
                    DB::raw('(ru.capacidad_maxima - COALESCE(SUM(rua.cantidad), 0)) as espacio_disponible')
                )
                ->where('ru.rack_id', $rackId)
                ->groupBy('ru.idRackUbicacion', 'ru.codigo_unico', 'ru.capacidad_maxima')
                ->get()
                ->filter(function ($ubicacion) {
                    // Filtrar solo ubicaciones que tienen espacio disponible
                    return $ubicacion->espacio_disponible > 0;
                })
                ->values();

            return response()->json([
                'success' => true,
                'data' => $ubicaciones
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar ubicaciones: ' . $e->getMessage()
            ], 500);
        }
    }


    // En el controlador
    public function obtenerArticulosUbicacion($ubicacionId)
    {
        try {
            $articulos = DB::table('rack_ubicacion_articulos as rua')
                ->leftJoin('articulos as a', 'rua.articulo_id', '=', 'a.idArticulos')
                ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')
                ->leftJoin('clientegeneral as cg', 'rua.cliente_general_id', '=', 'cg.idClienteGeneral')
                ->leftJoin('custodias as c', 'rua.custodia_id', '=', 'c.id')
                ->where('rua.rack_ubicacion_id', $ubicacionId)
                ->select(
                    'rua.idRackUbicacionArticulo as id',
                    'rua.articulo_id',
                    'rua.custodia_id',
                    'rua.cliente_general_id',
                    'rua.cantidad',
                    'a.nombre',
                    'a.codigo_repuesto',
                    'ta.nombre as tipo_articulo',
                    'cg.descripcion as cliente_nombre',
                    'c.serie as serie_custodia',
                    'c.codigocustodias as codigo_custodia',
                    DB::raw('CASE 
                    WHEN rua.custodia_id IS NOT NULL THEN 
                        COALESCE(c.serie, c.codigocustodias, CONCAT("Custodia ", c.id))
                    WHEN a.codigo_repuesto IS NOT NULL AND a.codigo_repuesto != "" THEN 
                        a.codigo_repuesto
                    WHEN a.nombre IS NOT NULL AND a.nombre != "" THEN 
                        a.nombre
                    ELSE 
                        CONCAT("Artículo ", a.idArticulos)
                END as nombre_mostrar')
                )
                ->get();

            return response()->json([
                'success' => true,
                'data' => $articulos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar artículos: ' . $e->getMessage()
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

            // ✅ NUEVO: CALCULAR ESTADO BASADO EN RANGOS FIJOS DE CAPACIDAD
            $estado = $this->calcularEstadoPorCapacidad($cantidadTotal);

            // Acumular categorías y tipos - USAR ARRAYS
            $categoriasUnicas = array_unique(array_filter(array_column($todosLosProductos, 'categoria')));
            $tiposUnicos = array_unique(array_filter(array_column($todosLosProductos, 'tipo_articulo')));

            $clientesNombres = array_filter(
                array_column($todosLosProductos, 'cliente_general_nombre'),
                fn($cliente) => $cliente && $cliente !== 'Sin cliente'
            );
            $clientesUnicos = array_unique($clientesNombres);

            // ✅ CORREGIDO: Mostrar información de múltiples productos
            $productoDisplay = !empty($todosLosProductos) ?
                (count($todosLosProductos) === 1 ?
                    $todosLosProductos[0]['nombre'] :
                    $todosLosProductos[0]['nombre'] . ' +' . (count($todosLosProductos) - 1) . ' más'
                ) : null;

            // ✅ CORREGIDO: Usar tipos acumulados en lugar del primero
            $tipoArticuloDisplay = !empty($tiposUnicos) ? implode(', ', $tiposUnicos) : null;

            // ✅ CORREGIDO: Usar categorías acumuladas en lugar de la primera
            $categoriaDisplay = !empty($categoriasUnicas) ? implode(', ', $categoriasUnicas) : null;

            Log::debug("✅ Ubicación {$ubicacionId} procesada:", [
                'productos_count' => count($todosLosProductos),
                'productos_nombres' => array_column($todosLosProductos, 'nombre'),
                'producto_display' => $productoDisplay,
                'tipos_display' => $tipoArticuloDisplay,
                'categorias_display' => $categoriaDisplay,
                'cantidad_total' => $cantidadTotal,
                'estado' => $estado
            ]);

            return [
                'id' => $ubicacionBase->id,
                'codigo' => $ubicacionBase->codigo_unico ?? $ubicacionBase->codigo,
                'productos' => $todosLosProductos,

                // ✅ CORREGIDO: Usar las variables corregidas
                'producto' => $productoDisplay,
                'cantidad' => $cantidadTotal,
                'cantidad_total' => $cantidadTotal,
                'stock_total' => !empty($todosLosProductos) ? ($todosLosProductos[0]['stock_total'] ?? null) : null,
                'tipo_articulo' => $tipoArticuloDisplay,
                'categoria' => $categoriaDisplay,

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
        // ✅ NUEVO: Usar rangos fijos en lugar de porcentajes
        if ($cantidad == 0) return 'vacio';
        if ($cantidad <= 100) return 'bajo';
        if ($cantidad <= 500) return 'medio';
        if ($cantidad <= 1000) return 'alto';
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
                'tipo_rack' => 'required|in:panel,spark', // NUEVO: Validación del tipo
                'filas' => 'required|integer|min:1|max:12',
                'columnas' => 'required|integer|min:1|max:24',
                'estado' => 'required|in:activo,inactivo'
                // ✅ ELIMINADO: capacidad_maxima
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
                'tipo_rack' => $request->tipo_rack, // NUEVO: Guardar tipo de rack
                'filas' => $request->filas,
                'columnas' => $request->columnas,
                'estado' => $request->estado,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $rack = DB::table('racks')->where('idRack', $rackId)->first();

            // ✅ ELIMINADO: $capacidadMaxima ya no se necesita
            // Generar ubicaciones automáticamente
            $this->generarUbicacionesAutomaticas($rack);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rack creado exitosamente con ' . ($request->filas * $request->columnas) . ' ubicaciones generadas',
                'data' => [
                    'id' => $rackId,
                    'total_ubicaciones' => $request->filas * $request->columnas,
                    'tipo_rack' => $request->tipo_rack // NUEVO: Incluir tipo en respuesta
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
            Log::debug('=== INICIO actualizarDimensionesRack ===', [
                'rack_id' => $rackId,
                'request_data' => $request->all()
            ]);

            $validator = Validator::make($request->all(), [
                'filas' => 'required|integer|min:1|max:12',
                'columnas' => 'required|integer|min:1|max:24',
                'tipo_rack' => 'required|in:panel,spark',
                // ✅ ELIMINADO: capacidad_maxima
            ]);

            if ($validator->fails()) {
                Log::warning('Validación fallida:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Obtener el rack actual
            $rack = DB::table('racks')->where('idRack', $rackId)->first();

            if (!$rack) {
                Log::warning('Rack no encontrado:', ['rack_id' => $rackId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Rack no encontrado'
                ], 404);
            }

            Log::debug('Rack actual:', [
                'nombre' => $rack->nombre,
                'tipo_rack_actual' => $rack->tipo_rack,
                'tipo_rack_nuevo' => $request->tipo_rack
            ]);

            // Verificar si se intenta cambiar el tipo de rack y si tiene productos
            if ($request->tipo_rack != $rack->tipo_rack) {
                Log::debug('Intentando cambiar tipo de rack, verificando productos...');

                $tieneProductos = DB::table('rack_ubicaciones as ru')
                    ->join('rack_ubicacion_articulos as rua', 'ru.idRackUbicacion', '=', 'rua.rack_ubicacion_id')
                    ->where('ru.rack_id', $rackId)
                    ->exists();

                Log::debug('Resultado verificación productos:', ['tiene_productos' => $tieneProductos]);

                if ($tieneProductos) {
                    Log::warning('No se puede cambiar tipo - rack tiene productos');
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede cambiar el tipo de rack porque contiene productos'
                    ], 422);
                }
            }

            // Verificar dimensiones
            $intentaDisminuirFilas = $request->filas < $rack->filas;
            $intentaDisminuirColumnas = $request->columnas < $rack->columnas;

            Log::debug('Verificando dimensiones:', [
                'filas_actual' => $rack->filas,
                'filas_nueva' => $request->filas,
                'columnas_actual' => $rack->columnas,
                'columnas_nueva' => $request->columnas,
                'intenta_disminuir_filas' => $intentaDisminuirFilas,
                'intenta_disminuir_columnas' => $intentaDisminuirColumnas
            ]);

            if ($intentaDisminuirFilas || $intentaDisminuirColumnas) {
                $ubicacionesConProductos = $this->verificarUbicacionesConProductos($rackId, $request->filas, $request->columnas);

                if ($ubicacionesConProductos) {
                    Log::warning('No se pueden disminuir dimensiones - hay productos');
                    return response()->json([
                        'success' => false,
                        'message' => 'No se pueden disminuir las dimensiones porque hay ubicaciones con productos que serían eliminadas',
                        'data' => [
                            'ubicaciones_afectadas' => $ubicacionesConProductos
                        ]
                    ], 422);
                }
            }

            // Actualizar rack
            DB::table('racks')
                ->where('idRack', $rackId)
                ->update([
                    'filas' => $request->filas,
                    'columnas' => $request->columnas,
                    'tipo_rack' => $request->tipo_rack,
                    'updated_at' => now()
                ]);

            Log::debug('Rack actualizado correctamente');

            // Actualizar ubicaciones existentes
            $ubicacionesExistentes = DB::table('rack_ubicaciones')
                ->where('rack_id', $rackId)
                ->get();

            Log::debug('Ubicaciones existentes:', ['count' => $ubicacionesExistentes->count()]);

            // ✅ ELIMINADO: Actualización de capacidad máxima
            // Ya no se actualiza la capacidad máxima

            $rackActualizado = DB::table('racks')->where('idRack', $rackId)->first();

            // ✅ CORREGIDO: Quitar el tercer parámetro de capacidad
            $resultadoSincronizacion = $this->sincronizarUbicaciones($rackActualizado, $ubicacionesExistentes);

            // ✅ DEBUG: Verificar qué devuelve
            Log::debug('RESULTADO SINCRONIZACION:', [
                'resultado' => $resultadoSincronizacion,
                'resultado_type' => gettype($resultadoSincronizacion),
                'is_array' => is_array($resultadoSincronizacion),
                'has_creadas' => isset($resultadoSincronizacion['creadas']),
                'has_eliminadas' => isset($resultadoSincronizacion['eliminadas'])
            ]);

            // ✅ FORZAR tipo array si es necesario
            if (!is_array($resultadoSincronizacion)) {
                Log::warning('Resultado no es array, forzando conversión');
                $resultadoSincronizacion = ['creadas' => 0, 'eliminadas' => 0];
            }

            // ✅ Asegurar que existen las claves
            if (!isset($resultadoSincronizacion['creadas'])) {
                $resultadoSincronizacion['creadas'] = 0;
            }
            if (!isset($resultadoSincronizacion['eliminadas'])) {
                $resultadoSincronizacion['eliminadas'] = 0;
            }

            DB::commit();

            $mensaje = 'Dimensiones actualizadas exitosamente. ';

            if ($request->tipo_rack != $rack->tipo_rack) {
                $mensaje .= 'Tipo de rack cambiado de ' . $rack->tipo_rack . ' a ' . $request->tipo_rack . '. ';
            }

            // ✅ CORREGIDO: Acceder correctamente a las claves del array
            if ($resultadoSincronizacion['creadas'] > 0) {
                $mensaje .= $resultadoSincronizacion['creadas'] . ' nuevas ubicaciones generadas. ';
            }

            if ($resultadoSincronizacion['eliminadas'] > 0) {
                $mensaje .= $resultadoSincronizacion['eliminadas'] . ' ubicaciones eliminadas. ';
            }

            Log::debug('=== ACTUALIZACIÓN COMPLETADA ===', [
                'mensaje' => $mensaje,
                'resultado_sincronizacion' => $resultadoSincronizacion
            ]);

            return response()->json([
                'success' => true,
                'message' => trim($mensaje),
                'data' => [
                    'rack_id' => $rackId,
                    'nuevas_ubicaciones' => $resultadoSincronizacion['creadas'],
                    'ubicaciones_eliminadas' => $resultadoSincronizacion['eliminadas'],
                    'tipo_rack_anterior' => $rack->tipo_rack,
                    'tipo_rack_nuevo' => $request->tipo_rack,
                    'total_filas' => $request->filas,
                    'total_columnas' => $request->columnas
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar dimensiones del rack: ' . $e->getMessage(), [
                'rack_id' => $rackId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Verifica si hay productos en ubicaciones que serían eliminadas al disminuir dimensiones
     */
    private function verificarUbicacionesConProductos($rackId, $nuevasFilas, $nuevasColumnas)
    {
        try {
            $ubicacionesActuales = DB::table('rack_ubicaciones')
                ->where('rack_id', $rackId)
                ->get();

            $ubicacionesFueraDeLimites = $ubicacionesActuales->filter(function ($ubicacion) use ($nuevasFilas, $nuevasColumnas) {
                return $ubicacion->nivel > $nuevasFilas || $ubicacion->posicion > $nuevasColumnas;
            });

            if ($ubicacionesFueraDeLimites->isEmpty()) {
                return false;
            }

            $ubicacionesIds = $ubicacionesFueraDeLimites->pluck('idRackUbicacion')->toArray();

            $tieneProductos = DB::table('rack_ubicacion_articulos')
                ->whereIn('rack_ubicacion_id', $ubicacionesIds)
                ->exists();

            // ✅ Devolver boolean siempre
            return $tieneProductos ? true : false;
        } catch (\Exception $e) {
            Log::error('Error en verificarUbicacionesConProductos: ' . $e->getMessage());
            return false;
        }
    }

    private function sincronizarUbicaciones($rack, $ubicacionesExistentes)
    {
        try {
            Log::debug('=== INICIO sincronizarUbicaciones DEBUG ===', [
                'rack_id' => $rack->idRack ?? 'null',
                'rack_type' => gettype($rack),
                'ubicaciones_existentes_type' => gettype($ubicacionesExistentes),
                'ubicaciones_existentes_count' => is_countable($ubicacionesExistentes) ? count($ubicacionesExistentes) : 'not countable'
            ]);

            // Si $rack no es un objeto, lanzar error
            if (!is_object($rack)) {
                Log::error('$rack no es un objeto:', ['rack' => $rack]);
                return ['creadas' => 0, 'eliminadas' => 0];
            }

            $nuevasUbicaciones = [];
            $now = now();
            $ubicacionesCreadas = 0;
            $ubicacionesEliminadas = 0;

            // Crear un mapa de ubicaciones existentes
            $mapaExistente = [];
            if (is_countable($ubicacionesExistentes)) {
                foreach ($ubicacionesExistentes as $ubicacion) {
                    $clave = "{$ubicacion->nivel}-{$ubicacion->posicion}";
                    $mapaExistente[$clave] = $ubicacion;
                }
            }

            // Identificar ubicaciones que deben ELIMINARSE (fuera de los nuevos límites)
            $ubicacionesAEliminar = [];
            if (is_countable($ubicacionesExistentes)) {
                foreach ($ubicacionesExistentes as $ubicacion) {
                    if ($ubicacion->nivel > $rack->filas || $ubicacion->posicion > $rack->columnas) {
                        $ubicacionesAEliminar[] = $ubicacion->idRackUbicacion;
                    }
                }
            }

            // Eliminar ubicaciones sobrantes (solo si no tienen productos)
            if (!empty($ubicacionesAEliminar)) {
                $ubicacionesConProductos = DB::table('rack_ubicacion_articulos')
                    ->whereIn('rack_ubicacion_id', $ubicacionesAEliminar)
                    ->exists();

                if (!$ubicacionesConProductos) {
                    $ubicacionesEliminadas = DB::table('rack_ubicaciones')
                        ->whereIn('idRackUbicacion', $ubicacionesAEliminar)
                        ->delete();
                } else {
                    throw new \Exception('No se pueden eliminar ubicaciones porque algunas contienen productos');
                }
            }

            // Generar nuevas ubicaciones si no existen
            for ($nivel = 1; $nivel <= $rack->filas; $nivel++) {
                for ($posicion = 1; $posicion <= $rack->columnas; $posicion++) {
                    $clave = "{$nivel}-{$posicion}";

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
                            'capacidad_maxima' => 0, // ✅ CAMBIADO: De $capacidadMaxima a 0
                            'created_at' => $now,
                            'updated_at' => $now
                        ];

                        $ubicacionesCreadas++;
                    }
                }
            }

            if (!empty($nuevasUbicaciones)) {
                DB::table('rack_ubicaciones')->insert($nuevasUbicaciones);
            }

            Log::debug('=== FIN sincronizarUbicaciones DEBUG ===', [
                'creadas' => $ubicacionesCreadas,
                'eliminadas' => $ubicacionesEliminadas,
                'return_type' => 'array'
            ]);

            // ✅ FORZAR a devolver array siempre
            return [
                'creadas' => (int)$ubicacionesCreadas,
                'eliminadas' => (int)$ubicacionesEliminadas
            ];
        } catch (\Exception $e) {
            Log::error('Error en sincronizarUbicaciones: ' . $e->getMessage());
            // ✅ Aún en error, devolver array
            return ['creadas' => 0, 'eliminadas' => 0];
        }
    }


    /**
     * Genera ubicaciones automáticamente para un rack
     */
    private function generarUbicacionesAutomaticas($rack)
    {
        $ubicaciones = [];
        $now = now();

        // Generar ubicaciones basadas en filas y columnas
        for ($nivel = 1; $nivel <= $rack->filas; $nivel++) {
            for ($posicion = 1; $posicion <= $rack->columnas; $posicion++) {
                $codigo = $this->generarCodigoUbicacion($rack->nombre, $nivel, $posicion);
                $codigoUnico = $rack->nombre . '-' . $codigo;

                // ✅ CORREGIDO: Quitar capacidad_maxima
                $ubicaciones[] = [
                    'rack_id' => $rack->idRack,
                    'codigo' => $codigo,
                    'codigo_unico' => $codigoUnico,
                    'nivel' => $nivel,
                    'posicion' => $posicion,
                    'estado_ocupacion' => 'vacio',
                    'capacidad_maxima' => 0, // ✅ O puedes poner null o eliminar el campo
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
            'columnas' => $rack->columnas
            // ✅ ELIMINADO: capacidad_maxima
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
    // En métodos como listarRacks, obtenerInfoRack, etc.
    public function listarRacks()
    {
        try {
            $racks = DB::table('racks')
                ->where('estado', 'activo')
                ->select('idRack', 'nombre', 'sede', 'tipo_rack', 'filas', 'columnas', 'created_at')
                ->orderBy('created_at', 'asc') // ✅ Ordenar por fecha de creación
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
                ->select('idRack', 'nombre', 'sede', 'tipo_rack', 'filas', 'columnas') // NUEVO: agregar tipo_rack
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

    // NUEVO: Método para verificar si el rack tiene productos
    public function verificarProductosEnRack($rackId)
    {
        try {
            Log::debug("Verificando productos en rack ID: {$rackId}");

            // Verificar si existe el rack
            $rack = DB::table('racks')->where('idRack', $rackId)->first();

            if (!$rack) {
                Log::warning("Rack no encontrado: {$rackId}");
                return response()->json([
                    'success' => false,
                    'message' => 'Rack no encontrado'
                ], 404);
            }

            // Verificar si hay productos en cualquier ubicación de este rack
            $tieneProductos = DB::table('rack_ubicaciones as ru')
                ->join('rack_ubicacion_articulos as rua', 'ru.idRackUbicacion', '=', 'rua.rack_ubicacion_id')
                ->where('ru.rack_id', $rackId)
                ->exists();

            Log::debug("Resultado verificación productos - Rack: {$rackId}, Tiene productos: " . ($tieneProductos ? 'SI' : 'NO'));

            return response()->json([
                'success' => true,
                'data' => [
                    'tiene_productos' => $tieneProductos,
                    'rack_id' => (int)$rackId,
                    'rack_nombre' => $rack->nombre
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error al verificar productos en rack: ' . $e->getMessage(), [
                'rack_id' => $rackId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar productos del rack: ' . $e->getMessage()
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
