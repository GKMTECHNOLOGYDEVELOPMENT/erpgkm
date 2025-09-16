<?php

namespace App\Http\Controllers\administracion\compras;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\DevolucionCompra;
use App\Models\Moneda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Str; // ← ESTA LÍNEA AGREGA


class ComprasController extends Controller
{
    public function index()
    {

        return view('administracion.compras.index');
    }


    public function data(Request $request)
    {
        // 1) Valida también 'q'
        $validator = Validator::make($request->all(), [
            'per_page'      => 'sometimes|integer|min:1|max:100',
            'page'          => 'sometimes|integer|min:1',
            'fecha_inicio'  => 'sometimes|nullable|date',
            'fecha_fin'     => 'sometimes|nullable|date',
            'q'             => 'sometimes|nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Parámetros inválidos',
                'details' => $validator->errors()
            ], 422);
        }

        $perPage = (int)($request->per_page ?? 10);
        $page    = (int)($request->page ?? 1);
        $q       = trim((string)$request->input('q', ''));   // <<— Toma q del request

        // 2) Base query (incluye proveedor_id para resolver la relación)
        $query = Compra::with('proveedor:idProveedor,nombre')
            ->select([
                'idCompra',
                'serie',
                'nro',
                'fechaEmision',
                'total',
                'proveedor_id',
                'created_at'
            ]);

        // 3) Filtros por fecha
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fechaEmision', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fechaEmision', '<=', $request->fecha_fin);
        }

        // 4) Filtro de búsqueda (mínimo 3 chars)
        if (strlen($q) >= 3) {
            $query->where(function ($qq) use ($q) {
                if (ctype_digit($q)) {
                    // si es número, prioriza nro
                    $qq->where('nro', $q)
                        ->orWhere('nro', 'like', $q . '%');
                }
                // serie y proveedor por prefijo para usar índices
                $qq->orWhere('serie', 'like', $q . '%')
                    ->orWhereHas('proveedor', fn($p) => $p->where('nombre', 'like', $q . '%'));
            });
        }

        // 5) Orden: últimos creados primero
        $compras = $query->orderByDesc('created_at')
            ->orderByDesc('idCompra')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $compras->items(), // incluye compra.proveedor.nombre
            'pagination' => [
                'current_page' => $compras->currentPage(),
                'last_page'    => $compras->lastPage(),
                'per_page'     => $compras->perPage(),
                'total'        => $compras->total(),
                'from'         => $compras->firstItem(),
                'to'           => $compras->lastItem(),
            ]
        ]);
    }



    public function create()
    {
        $documentos = DB::table('documento')->get();
        $monedas = Moneda::all();


        return view('administracion.compras.create', compact('documentos', 'monedas'));
    }

    // API para obtener monedas
    public function getMonedas()
    {
        try {
            $monedas = DB::table('monedas')
                ->select('idMonedas as id', 'nombre', 'simbolo')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $monedas
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar monedas: ' . $e->getMessage()
            ], 500);
        }
    }

    // API para obtener impuestos
    public function getImpuestos()
    {
        try {
            $impuestos = DB::table('impuesto')
                ->select('idImpuesto as id', 'nombre', 'monto')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $impuestos
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar impuestos: ' . $e->getMessage()
            ], 500);
        }
    }

    // API para obtener sujetos
    public function getSujetos()
    {
        try {
            $sujetos = DB::table('sujeto')
                ->select('idSujeto as id', 'nombre')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $sujetos
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar sujetos: ' . $e->getMessage()
            ], 500);
        }
    }

    // API para obtener condiciones de compra
    public function getCondicionesCompra()
    {
        try {
            $condiciones = DB::table('condicioncompra')
                ->select('idCondicionCompra as id', 'nombre')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $condiciones
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar condiciones de compra: ' . $e->getMessage()
            ], 500);
        }
    }

    // API para obtener tipos de pago
    public function getTiposPago()
    {
        try {
            $tiposPago = DB::table('tipopago')
                ->select('idTipoPago as id', 'nombre')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $tiposPago
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar tipos de pago: ' . $e->getMessage()
            ], 500);
        }
    }

    // En tu controlador, agrega estos métodos
    public function getUnidades()
    {
        try {
            $unidades = DB::table('unidad')
                ->select('idUnidad as id', 'nombre')
                ->orderBy('nombre')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $unidades
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar unidades: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getModelos()
    {
        try {
            $modelos = DB::table('modelo as m')
                ->select(
                    'm.idModelo as id',
                    'm.nombre as nombre_modelo',
                    'ma.nombre as marca',
                    'c.nombre as categoria'
                )
                ->leftJoin('marca as ma', 'm.idMarca', '=', 'ma.idMarca')
                ->leftJoin('categoria as c', 'm.idCategoria', '=', 'c.idCategoria')
                ->where('m.estado', 1)
                ->orderBy('ma.nombre')
                ->orderBy('m.nombre')
                ->get();

            // Formatear el nombre para mostrar marca y categoría
            $modelosFormateados = $modelos->map(function ($modelo) {
                return [
                    'id' => $modelo->id,
                    'nombre' => $modelo->nombre_modelo .
                        ($modelo->marca ? ' - ' . $modelo->marca : '') .
                        ($modelo->categoria ? ' (' . $modelo->categoria . ')' : '')
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $modelosFormateados
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar modelos: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para guardar la compra
    public function guardarCompra(Request $request)
    {
        try {
            DB::beginTransaction();

            // Log para verificar los datos recibidos
            Log::info('=== INICIO GUARDAR COMPRA ===');
            Log::info('Datos recibidos en guardarCompra:', $request->all());
            Log::info('Productos recibidos:', $request->productos);
            Log::info('Headers:', $request->headers->all());


            // OBTENER EL USUARIO AUTENTICADO - AGREGAR ESTAS LÍNEAS
            $usuario = auth()->user();

            if (!$usuario) {
                Log::error('❌ Usuario no autenticado');
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            Log::info('Usuario autenticado:', [
                'idUsuario' => $usuario->idUsuario,
                'Nombre' => $usuario->Nombre,
                'correo' => $usuario->correo
            ]);



            // Validar datos requeridos
            $request->validate([
                'serie' => 'required|string',
                'nro' => 'required|integer',
                'fecha' => 'required|date',
                'fecha_vencimiento' => 'required|date',
                'documento_id' => 'required|integer|exists:documento,idDocumento',
                'proveedor_id' => 'required|integer|exists:proveedores,idProveedor',
                'moneda_id' => 'required|integer|exists:monedas,idMonedas',
                'impuesto_id' => 'required|integer|exists:impuesto,idImpuesto',
                'sujeto_id' => 'required|integer|exists:sujeto,idSujeto',
                'condicion_compra_id' => 'required|integer|exists:condicioncompra,idCondicionCompra',
                'tipo_pago_id' => 'required|integer|exists:tipopago,idTipoPago',
                'productos' => 'required|array|min:1',
                'subtotal' => 'required|numeric|min:0',
                'igv' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0'
            ]);

            Log::info('Validación pasada correctamente');
            Log::info('ID Proveedor recibido: ' . $request->proveedor_id);

            do {
                $codigoCompra = strtoupper(Str::random(10));
            } while (DB::table('compra')->where('codigocompra', $codigoCompra)->exists());
            Log::info("Código de compra generado: {$codigoCompra}");
            // Insertar la compra
            $compraData = [
                'codigocompra' => $codigoCompra,
                'serie' => $request->serie,
                'nro' => $request->nro,
                'fechaEmision' => $request->fecha,
                'fechaVencimiento' => $request->fecha_vencimiento,
                'imagen' => null,
                'sujetoporcentaje' => $request->sujeto_porcentaje ?? 0,
                'proveedor_id' => $request->proveedor_id,
                'cantidad' => count($request->productos),
                'gravada' => $request->subtotal,
                'igv' => $request->igv,
                'total' => $request->total,
                'idMonedas' => $request->moneda_id,
                'idDocumento' => $request->documento_id,
                'idImpuesto' => $request->impuesto_id,
                'idSujeto' => $request->sujeto_id,
                'idUsuario' => $usuario->idUsuario, // ← AQUÍ AGREGAMOS EL ID DEL USUARIO
                'idCondicionCompra' => $request->condicion_compra_id,
                'idTipoPago' => $request->tipo_pago_id,
                'created_at' => now(),
                'updated_at' => now()
            ];

            Log::info('Datos de compra a insertar:', $compraData);

            $compraId = DB::table('compra')->insertGetId($compraData);
            Log::info("Compra insertada con ID: {$compraId}");

            // Procesar cada producto de la compra
            foreach ($request->productos as $index => $producto) {
                Log::info("=== PROCESANDO PRODUCTO {$index} ===");
                Log::info("Datos del producto:", $producto);

                $productoId = $producto['id'];
                $esProductoNuevo = false;

                // VERIFICAR SI ES UN PRODUCTO NUEVO (ID TEMPORAL)
                if (strpos($producto['id'], 'temp-') === 0) {
                    Log::info("🆕 PRODUCTO NUEVO DETECTADO - Creando artículo en BD");
                    $esProductoNuevo = true;

                    // Crear el nuevo artículo en la tabla articulos
                    $articuloData = [
                        'codigo_barras' => $producto['codigo_barras'],
                        'nombre' => $producto['nombre'],
                        'stock_total' => $producto['stock'], // Stock inicial
                        'stock_minimo' => $producto['datos_extra']['stock_minimo'] ?? 0,
                        'moneda_compra' => $producto['moneda_compra'] ?? 1, // ID de moneda compra (default: 1 = Soles)
                        'moneda_venta' => $producto['moneda_venta'] ?? 1,   // ID de moneda venta (default: 1 = Soles)
                        'precio_compra' => $producto['precio'],
                        'precio_venta' => $producto['precio_venta'],
                        'sku' => $producto['datos_extra']['sku'] ?? '',
                        'peso' => $producto['datos_extra']['peso'] ?? 0,
                        'garantia_fabrica' => $producto['datos_extra']['garantia'] ?? 0,
                        'unidad_tiempo_garantia' => $producto['datos_extra']['unidad_tiempo_garantia'] ?? 'meses',
                        'idUnidad' => $producto['datos_extra']['idUnidad'] ?? null,
                        'idModelo' => $producto['datos_extra']['idModelo'] ?? null,
                        'idProveedor' => $request->proveedor_id,
                        'estado' => 1,
                        'fecha_ingreso' => now(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    Log::info("Datos para nuevo artículo:", $articuloData);

                    // Insertar el nuevo artículo
                    $productoId = DB::table('articulos')->insertGetId($articuloData);
                    Log::info("✅ NUEVO ARTÍCULO CREADO - ID: {$productoId}");

                    // GENERAR CÓDIGOS DE BARRAS PARA EL NUEVO ARTÍCULO
                    Log::info("Generando códigos de barras para el nuevo artículo...");

                    // Generar código de barras para el código de barras
                    if (!empty($producto['codigo_barras'])) {
                        try {
                            $barcodeGenerator = new BarcodeGeneratorPNG();
                            $barcode = $barcodeGenerator->getBarcode($producto['codigo_barras'], BarcodeGeneratorPNG::TYPE_CODE_128);

                            DB::table('articulos')
                                ->where('idArticulos', $productoId)
                                ->update(['foto_codigobarras' => $barcode]);

                            Log::info("✅ Código de barras generado para: {$producto['codigo_barras']}");
                        } catch (Exception $e) {
                            Log::error("❌ Error al generar código de barras: " . $e->getMessage());
                        }
                    }

                    // Generar código de barras para el SKU
                    if (!empty($producto['datos_extra']['sku'])) {
                        try {
                            $barcodeGenerator = new BarcodeGeneratorPNG();
                            $barcode = $barcodeGenerator->getBarcode($producto['datos_extra']['sku'], BarcodeGeneratorPNG::TYPE_CODE_128);

                            DB::table('articulos')
                                ->where('idArticulos', $productoId)
                                ->update(['fotosku' => $barcode]);

                            Log::info("✅ Código de barras generado para SKU: {$producto['datos_extra']['sku']}");
                        } catch (Exception $e) {
                            Log::error("❌ Error al generar código de barras para SKU: " . $e->getMessage());
                        }
                    }

                    // Crear registro inicial en kardex para el nuevo artículo
                    $kardexData = [
                        'fecha' => $request->fecha,
                        'idArticulo' => $productoId,
                        'unidades_entrada' => $producto['cantidad'], // Cantidad comprada
                        'costo_unitario_entrada' => $producto['precio'],
                        'unidades_salida' => 0,
                        'costo_unitario_salida' => 0,
                        'inventario_inicial' => 0,
                        'inventario_actual' => $producto['cantidad'],
                        'costo_inventario' => $producto['cantidad'] * $producto['precio'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    DB::table('kardex')->insert($kardexData);
                    Log::info("✅ KARDEX INICIAL CREADO PARA ARTÍCULO NUEVO");
                } else {
                    // ES UN PRODUCTO EXISTENTE - MANTENER TU LÓGICA ORIGINAL
                    Log::info("📦 PRODUCTO EXISTENTE - ID: {$producto['id']}");

                    // Obtener el artículo actual ANTES de actualizar
                    $articuloActual = DB::table('articulos')->where('idArticulos', $producto['id'])->first();

                    if (!$articuloActual) {
                        Log::error("Artículo con ID {$producto['id']} no encontrado en la base de datos");
                        throw new Exception("Artículo con ID {$producto['id']} no existe");
                    }

                    Log::info("ARTÍCULO ACTUAL - ID: {$articuloActual->idArticulos}, Nombre: {$articuloActual->nombre}");
                    Log::info("ARTÍCULO ACTUAL - Precio compra: {$articuloActual->precio_compra}, Precio venta: {$articuloActual->precio_venta}");
                    Log::info("ARTÍCULO ACTUAL - Proveedor actual: {$articuloActual->idProveedor}");
                    Log::info("NUEVOS VALORES - Precio compra: {$producto['precio']}, Precio venta: " . ($producto['precio_venta'] ?? 'N/A'));
                    Log::info("NUEVO PROVEEDOR: {$request->proveedor_id}");

                    // Verificar si existe precio_venta
                    if (!isset($producto['precio_venta']) || $producto['precio_venta'] == 0) {
                        Log::warning("⚠️ Producto {$index} tiene precio_venta en 0 o no definido");
                    }

                    // Actualizar los precios Y EL PROVEEDOR en la tabla de artículos
                    Log::info("Actualizando artículo ID: {$producto['id']}");

                    $updateData = [
                        'precio_compra' => $producto['precio'],
                        'precio_venta' => $producto['precio_venta'] ?? 0,
                        'moneda_compra' => $producto['moneda_compra'] ?? 1, // Actualizar moneda compra
                        'moneda_venta' => $producto['moneda_venta'] ?? 1,   // Actualizar moneda venta
                        'idProveedor' => $request->proveedor_id, // ACTUALIZAR EL PROVEEDOR
                        'updated_at' => now()
                    ];

                    Log::info("Datos para actualizar articulos:", $updateData);

                    $actualizados = DB::table('articulos')
                        ->where('idArticulos', $producto['id'])
                        ->update($updateData);

                    Log::info("Filas actualizadas en articulos: {$actualizados}");

                    if ($actualizados === 0) {
                        Log::warning("⚠️ No se actualizó ninguna fila en articulos para ID: {$producto['id']}");
                    } else {
                        Log::info("✅ Artículo actualizado correctamente");
                    }

                    // Verificar el artículo DESPUÉS de actualizar
                    $articuloDespues = DB::table('articulos')->where('idArticulos', $producto['id'])->first();
                    Log::info("ARTÍCULO DESPUÉS - Precio compra: {$articuloDespues->precio_compra}, Precio venta: {$articuloDespues->precio_venta}");
                    Log::info("ARTÍCULO DESPUÉS - Nuevo proveedor: {$articuloDespues->idProveedor}");

                    // Verificar si los valores se actualizaron correctamente
                    if ($articuloDespues->precio_compra != $producto['precio']) {
                        Log::error("❌ ERROR: precio_compra no se actualizó correctamente");
                        Log::error("Esperado: {$producto['precio']}, Obtenido: {$articuloDespues->precio_compra}");
                    }

                    if (isset($producto['precio_venta']) && $articuloDespues->precio_venta != $producto['precio_venta']) {
                        Log::error("❌ ERROR: precio_venta no se actualizó correctamente");
                        Log::error("Esperado: {$producto['precio_venta']}, Obtenido: {$articuloDespues->precio_venta}");
                    }

                    if ($articuloDespues->idProveedor != $request->proveedor_id) {
                        Log::error("❌ ERROR: idProveedor no se actualizó correctamente");
                        Log::error("Esperado: {$request->proveedor_id}, Obtenido: {$articuloDespues->idProveedor}");
                    }

                    // Aumentar el stock en la tabla 'articulos' (SOLO PARA EXISTENTES)
                    Log::info("Incrementando stock en: {$producto['cantidad']}");
                    DB::table('articulos')->where('idArticulos', $producto['id'])->increment('stock_total', $producto['cantidad']);

                    $articuloConStock = DB::table('articulos')->where('idArticulos', $producto['id'])->first();
                    Log::info("Stock después del incremento: {$articuloConStock->stock_total}");

                    // Obtener el mes y año actual de la compra
                    $fechaCompra = Carbon::parse($request->fecha);
                    $mesCompra = $fechaCompra->format('m');
                    $anioCompra = $fechaCompra->format('Y');

                    Log::info("Fecha compra: {$fechaCompra}, Mes: {$mesCompra}, Año: {$anioCompra}");

                    // Buscar si existe un registro de kardex para este artículo en el mismo mes
                    $kardexExistente = DB::table('kardex')
                        ->where('idArticulo', $producto['id'])
                        ->whereMonth('fecha', $mesCompra)
                        ->whereYear('fecha', $anioCompra)
                        ->first();

                    if ($kardexExistente) {
                        Log::info("Kardex existente encontrado ID: {$kardexExistente->id}");

                        // Actualizar el registro existente del mes
                        $updateKardex = [
                            'unidades_entrada' => $kardexExistente->unidades_entrada + $producto['cantidad'],
                            'costo_unitario_entrada' => $producto['precio'],
                            'inventario_actual' => $kardexExistente->inventario_actual + $producto['cantidad'],
                            'costo_inventario' => $kardexExistente->costo_inventario + ($producto['precio'] * $producto['cantidad']),
                            'updated_at' => now()
                        ];

                        Log::info("Actualizando kardex existente:", $updateKardex);

                        DB::table('kardex')
                            ->where('id', $kardexExistente->id)
                            ->update($updateKardex);

                        Log::info("Kardex actualizado correctamente");
                    } else {
                        Log::info("No hay kardex existente para este mes, creando nuevo registro");

                        // Buscar el último registro de kardex para este artículo
                        $ultimoKardex = DB::table('kardex')
                            ->where('idArticulo', $producto['id'])
                            ->orderBy('fecha', 'desc')
                            ->first();

                        // Calcular valores iniciales
                        $inventarioInicial = $ultimoKardex ? $ultimoKardex->inventario_actual : $articuloActual->stock_total;
                        $inventarioActual = $inventarioInicial + $producto['cantidad'];
                        $costoInventario = ($ultimoKardex ? $ultimoKardex->costo_inventario : 0) + ($producto['precio'] * $producto['cantidad']);

                        Log::info("Inventario inicial: {$inventarioInicial}, Actual: {$inventarioActual}, Costo: {$costoInventario}");

                        // Crear nuevo registro de kardex para el nuevo mes
                        $nuevoKardex = [
                            'fecha' => $fechaCompra,
                            'idArticulo' => $producto['id'],
                            'unidades_entrada' => $producto['cantidad'],
                            'costo_unitario_entrada' => $producto['precio'],
                            'unidades_salida' => 0,
                            'costo_unitario_salida' => 0,
                            'inventario_inicial' => $inventarioInicial,
                            'inventario_actual' => $inventarioActual,
                            'costo_inventario' => $costoInventario,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];

                        Log::info("Insertando nuevo kardex:", $nuevoKardex);

                        $kardexId = DB::table('kardex')->insertGetId($nuevoKardex);
                        Log::info("Nuevo kardex creado con ID: {$kardexId}");
                    }
                }

                // INSERTAR DETALLE DE COMPRA (para ambos casos - existentes y nuevos)
                $detalleData = [
                    'idCompra' => $compraId,
                    'idProducto' => $productoId,
                    'cantidad' => $producto['cantidad'],
                    'precio' => $producto['precio'],
                    'precio_venta' => $producto['precio_venta'] ?? 0,
                    'subtotal' => $producto['subtotal'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                Log::info("Insertando detalle_compra:", $detalleData);

                $detalleId = DB::table('detalle_compra')->insertGetId($detalleData);
                Log::info("Detalle insertado con ID: {$detalleId}");

                // ✅ Insertar en inventario_ingresos_clientes
                DB::table('inventario_ingresos_clientes')->insert([
                    'compra_id' => $compraId,
                    'articulo_id' => $productoId,
                    'tipo_ingreso' => 'compra',
                    'ingreso_id' => $detalleId, // ← ESTE es el detalle de compra
                    'cliente_general_id' => 8,
                    'cantidad' => $producto['cantidad'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);


                Log::info("Registro de inventario_ingresos_clientes insertado con detalle_compra ID: {$detalleId}");

                Log::info("=== FIN PROCESAMIENTO PRODUCTO {$index} ===");

}

            DB::commit();
            Log::info("✅ COMPRA GUARDADA EXITOSAMENTE - ID: {$compraId}");
            Log::info("✅ PROVEEDOR ACTUALIZADO EN TODOS LOS ARTÍCULOS DE LA COMPRA");

            return response()->json([
                'success' => true,
                'message' => 'Compra guardada exitosamente',
                'compra_id' => $compraId,
                'proveedor_actualizado' => true
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('❌ ERROR AL GUARDAR LA COMPRA: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la compra: ' . $e->getMessage()
            ], 500);
        }
    }



    // En tu CompraController.php
    public function guardarNuevoArticuloDesdeCompra(Request $request)
    {
        DB::beginTransaction();

        try {
            Log::info('=== INICIO GUARDAR NUEVO ARTÍCULO DESDE COMPRA ===');
            Log::info('Datos recibidos:', $request->all());

            // Validación de datos
            $validatedData = $request->validate([
                'codigo_barras' => 'required|string|max:255|unique:articulos,codigo_barras',
                'sku' => 'nullable|string|max:255|unique:articulos,sku',
                'nombre' => 'required|string|max:255|unique:articulos,nombre',
                'stock_total' => 'required|integer|min:0',
                'stock_minimo' => 'required|integer|min:0',
                'precio_compra' => 'required|numeric|min:0',
                'precio_venta' => 'required|numeric|min:0',
                'peso' => 'nullable|numeric|min:0',
                'idUnidad' => 'required|integer|exists:unidad,idUnidad',
                'idModelo' => 'nullable|integer|exists:modelo,idModelo',
            ], [
                'codigo_barras.unique' => 'El código de barras ya existe en el sistema',
                'sku.unique' => 'El SKU ya existe en el sistema',
                'nombre.unique' => 'El nombre ya existe en el sistema',
                'stock_total.min' => 'El stock total no puede ser negativo',
                'stock_minimo.min' => 'El stock mínimo no puede ser negativo',
                'precio_compra.min' => 'El precio de compra no puede ser negativo',
                'precio_venta.min' => 'El precio de venta no puede ser negativo',
            ]);

            // Validación adicional: precio_venta debe ser mayor o igual a precio_compra
            if ($validatedData['precio_venta'] < $validatedData['precio_compra']) {
                throw new Exception('El precio de venta no puede ser menor al precio de compra');
            }

            // Preparar datos para insertar
            $dataArticulo = [
                'codigo_barras' => $validatedData['codigo_barras'],
                'sku' => $validatedData['sku'] ?? null,
                'nombre' => $validatedData['nombre'],
                'stock_total' => $validatedData['stock_total'],
                'stock_minimo' => $validatedData['stock_minimo'],
                'precio_compra' => $validatedData['precio_compra'],
                'precio_venta' => $validatedData['precio_venta'],
                'peso' => $validatedData['peso'] ?? 0,
                'idUnidad' => $validatedData['idUnidad'],
                'idModelo' => $validatedData['idModelo'] ?? null,
                'estado' => 1,
                'idTipoArticulo' => 1, // Tipo por defecto
                'fecha_ingreso' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ];

            Log::info('Datos preparados para articulo:', $dataArticulo);

            // Insertar el artículo
            $articuloId = DB::table('articulos')->insertGetId($dataArticulo);
            Log::info("Artículo insertado con ID: {$articuloId}");

            // Registrar movimiento inicial en el Kardex (solo si hay stock)
            if ($validatedData['stock_total'] > 0) {
                $kardexData = [
                    'fecha' => now(),
                    'idArticulo' => $articuloId,
                    'unidades_entrada' => $validatedData['stock_total'],
                    'costo_unitario_entrada' => $validatedData['precio_compra'],
                    'unidades_salida' => 0,
                    'costo_unitario_salida' => 0,
                    'inventario_inicial' => 0,
                    'inventario_actual' => $validatedData['stock_total'],
                    'costo_inventario' => $validatedData['stock_total'] * $validatedData['precio_compra'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                DB::table('kardex')->insert($kardexData);
                Log::info("Kardex creado para artículo ID: {$articuloId}");
            }

            DB::commit();
            Log::info("✅ ARTÍCULO GUARDADO EXITOSAMENTE - ID: {$articuloId}");

            return response()->json([
                'success' => true,
                'message' => 'Artículo guardado exitosamente',
                'articulo_id' => $articuloId,
                'articulo' => array_merge($dataArticulo, ['idArticulos' => $articuloId])
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('❌ ERROR DE VALIDACIÓN: ' . json_encode($e->errors()));

            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('❌ ERROR AL GUARDAR ARTÍCULO: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el artículo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verificarCodigoBarras(Request $request)
    {
        $codigo = $request->query('codigo');

        $exists = DB::table('articulos')
            ->where('codigo_barras', $codigo)
            ->exists();

        return response()->json(['exists' => $exists]);
    }


    public function detalles($id)
    {
        // Buscar la compra con relaciones
        $compra = Compra::with([
            'detalles.producto',
            'moneda',
            'proveedor',
            'usuario'
        ])->findOrFail($id);

        // Obtener devoluciones con join
        $devoluciones = DB::table('devoluciones_compra as dc')
            ->leftJoin('articulos as a', 'dc.idProducto', '=', 'a.idArticulos')
            ->leftJoin('usuarios as u', 'dc.idUsuario', '=', 'u.idUsuario')
            ->where('dc.idCompra', $id)
            ->select(
                'dc.*',
                'a.nombre as producto_nombre',
                'u.Nombre as usuario_nombre',
                'u.apellidoPaterno as usuario_apellido'
            )
            ->orderByDesc('dc.fecha_devolucion')
            ->get();

        return view('administracion.compras.detalles', compact('compra', 'devoluciones'));
    }

    public function factura($id)
    {
        // Lógica para la página de factura
        $compra = Compra::findOrFail($id);
        return view('administracion.compras.factura', compact('compra'));
    }


    public function ticket($id)
    {
        $compra = Compra::with('proveedor', 'moneda', 'usuario')->findOrFail($id);

        // Sumar devoluciones por PRODUCTO dentro de la compra
        $subDev = DB::table('devoluciones_compra')
            ->select('idProducto', DB::raw('SUM(cantidad) AS devueltas'))
            ->where('idCompra', $id)
            ->groupBy('idProducto');

        // Detalle de compra + artículo + devoluciones por producto
        $rows = DB::table('detalle_compra as dc')
            ->join('articulos as a', 'dc.idProducto', '=', 'a.idArticulos')
            ->leftJoinSub($subDev, 'dev', function ($join) {
                $join->on('dc.idProducto', '=', 'dev.idProducto');
            })
            ->where('dc.idCompra', $compra->idCompra)
            ->get([
                'dc.idDetalleCompra',
                'dc.idProducto',
                'dc.cantidad',
                'dc.precio',
                'a.nombre as articulo',
                DB::raw('COALESCE(dev.devueltas,0) AS devueltas'),
            ]);

        // Netear por línea y filtrar líneas que quedan en 0
        $detalles = $rows->map(function ($r) {
            $net = max(0, (int)$r->cantidad - (int)$r->devueltas);
            $r->cantidad_neta = $net;
            $r->subtotal_neto = $net * (float)$r->precio;
            return $r;
        })->filter(fn($r) => $r->cantidad_neta > 0)->values();

        // Totales netos
        $subtotal = round($detalles->sum('subtotal_neto'), 2);
        $igvPct   = (float)($compra->sujetoporcentaje ?? 0);
        $igv      = round($subtotal * $igvPct / 100, 2);
        $total    = round($subtotal + $igv, 2);

        // Código de barras
        $textBarcode = $compra->codigocompra;
        $barcodePng  = base64_encode(
            (new BarcodeGeneratorPNG())->getBarcode($textBarcode, BarcodeGeneratorPNG::TYPE_CODE_128)
        );

        return view('administracion.compras.ticket', [
            'compra'      => $compra,
            'detalles'    => $detalles,   // ahora el blade iterará sobre esto
            'subtotal'    => $subtotal,
            'igv'         => $igv,
            'total'       => $total,
            'barcode'     => $barcodePng,
            'barcodeText' => $textBarcode,
        ]);
    }

    public function ticketDevolucion($id)
    {
        $compra = Compra::with('proveedor', 'moneda', 'usuario')->findOrFail($id);

        $items = DB::table('devoluciones_compra as d')
            ->join('articulos as a', 'd.idProducto', '=', 'a.idArticulos')
            ->where('d.idCompra', $id)
            ->orderBy('d.fecha_devolucion')
            ->get([
                'd.fecha_devolucion',
                'd.cantidad',
                'd.precio_unitario',
                'a.nombre as articulo',
            ])
            ->map(function ($row) {
                return [
                    'fecha'    => \Carbon\Carbon::parse($row->fecha_devolucion)->format('d-m-Y'),
                    'nombre'   => $row->articulo,
                    'cantidad' => (int) $row->cantidad,
                    'precio'   => (float) $row->precio_unitario,
                    'total'    => $row->cantidad * $row->precio_unitario,
                ];
            });


        $subtotal = round($items->sum('total'), 2);
        $igvPct   = (float) ($compra->sujetoporcentaje ?? 0);
        $igv      = round($subtotal * $igvPct / 100, 2);
        $total    = round($subtotal + $igv, 2);

        $codigo = $compra->codigocompra;
        $barcodePng = base64_encode(
            (new \Picqer\Barcode\BarcodeGeneratorPNG())->getBarcode($codigo, \Picqer\Barcode\BarcodeGeneratorPNG::TYPE_CODE_128)
        );

        return view('administracion.compras.ticket-devolucion', [
            'compra'   => $compra,
            'items'    => $items,
            'subtotal' => $subtotal,
            'igv'      => $igv,
            'total'    => $total,
            'barcode'  => $barcodePng,
            'codigo'   => $codigo,
            'simbolo'  => $compra->moneda->simbolo ?? 'S/.',
            'porcIgv'  => $igvPct,
        ]);
    }

    public function facturaPdf($id)
    {
        $compra = Compra::with(['proveedor', 'usuario', 'moneda', 'detalles.producto'])->findOrFail($id);

        $html = view('administracion.compras.pdf.factura', compact('compra'))->render();

        return response(
            Browsershot::html($html)
                ->format('A4')
                ->margins(15, 10, 15, 10)
                ->waitUntilNetworkIdle()
                ->noSandbox()
                ->pdf()
        )->header('Content-Type', 'application/pdf');
    }

    public function procesarDevolucion(Request $request)
    {
        try {
            DB::beginTransaction();

            // Obtener datos de la solicitud
            $idDetalleCompra = $request->input('idDetalleCompra');
            $cantidad = $request->input('cantidad');
            $motivo = $request->input('motivo');

            // Obtener el detalle de compra
            $detalleCompra = DetalleCompra::findOrFail($idDetalleCompra);

            // Verificar que la cantidad a devolver sea válida
            if ($cantidad > $detalleCompra->cantidad) {
                return response()->json([
                    'success' => false,
                    'message' => 'La cantidad a devolver excede la cantidad comprada'
                ]);
            }

            // Registrar la devolución
            $devolucion = new DevolucionCompra();
            $devolucion->idCompra = $detalleCompra->idCompra;
            $devolucion->idProducto = $detalleCompra->idProducto;
            $devolucion->idUsuario = Auth::id();
            $devolucion->cantidad = $cantidad;
            $devolucion->precio_unitario = $detalleCompra->precio;
            $devolucion->total_devolucion = $cantidad * $detalleCompra->precio;
            $devolucion->motivo = $motivo;
            $devolucion->fecha_devolucion = now();
            $devolucion->save();

            // Actualizar el detalle de compra
            $detalleCompra->cantidad -= $cantidad;
            $detalleCompra->subtotal = $detalleCompra->cantidad * $detalleCompra->precio;
            $detalleCompra->save();

            // Actualizar el stock del producto
            $producto = Articulo::find($detalleCompra->idProducto);
            if ($producto) {
                $producto->stock_total -= $cantidad;
                $producto->save();
            }

            // Recalcular totales de la compra
            $compra = Compra::find($detalleCompra->idCompra);
            $nuevosTotales = $this->recalcularTotalesCompra($compra);

            DB::commit();

            return response()->json([
                'success' => true,
                'nuevos_totales' => $nuevosTotales,
                'fecha_devolucion' => $devolucion->fecha_devolucion->format('d/m/Y H:i'),
                'producto_nombre' => $producto->nombre ?? 'Producto',
                'precio_unitario' => number_format($devolucion->precio_unitario, 2),
                'total_devolucion' => number_format($devolucion->total_devolucion, 2),
                'usuario_nombre' => Auth::user()->Nombre . ' ' . Auth::user()->apellidoPaterno
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la devolución: ' . $e->getMessage()
            ]);
        }
    }

    private function recalcularTotalesCompra($compra)
    {
        // Recalcular subtotal
        $subtotal = $compra->detalles->sum('subtotal');

        // Recalcular IGV
        $igv = $subtotal * ($compra->sujetoporcentaje / 100);

        // Recalcular total
        $total = $subtotal + $igv;

        // Actualizar la compra
        $compra->gravada = $subtotal;
        $compra->igv = $igv;
        $compra->total = $total;
        $compra->save();

        return [
            'subtotal' => $subtotal,
            'igv' => $igv,
            'total' => $total
        ];
    }
}
