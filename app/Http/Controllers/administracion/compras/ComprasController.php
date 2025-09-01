<?php

namespace App\Http\Controllers\administracion\compras;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\Moneda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
    $q       = trim((string)$request->input('q',''));   // <<— Toma q del request

    // 2) Base query (incluye proveedor_id para resolver la relación)
    $query = Compra::with('proveedor:idProveedor,nombre')
        ->select([
            'idCompra','serie','nro','fechaEmision','total','proveedor_id','created_at'
        ]);

    // 3) Filtros por fecha
    if ($request->filled('fecha_inicio')) {
        $query->whereDate('fechaEmision','>=',$request->fecha_inicio);
    }
    if ($request->filled('fecha_fin')) {
        $query->whereDate('fechaEmision','<=',$request->fecha_fin);
    }

    // 4) Filtro de búsqueda (mínimo 3 chars)
    if (strlen($q) >= 3) {
        $query->where(function ($qq) use ($q) {
            if (ctype_digit($q)) {
                // si es número, prioriza nro
                $qq->where('nro', $q)
                   ->orWhere('nro','like',$q.'%');
            }
            // serie y proveedor por prefijo para usar índices
            $qq->orWhere('serie','like',$q.'%')
               ->orWhereHas('proveedor', fn($p)=>$p->where('nombre','like',$q.'%'));
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

        // Insertar la compra
        $compraData = [
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

            // Insertar detalle de compra con precio_venta
            $detalleData = [
                'idCompra' => $compraId,
                'idProducto' => $producto['id'],
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

            // Actualizar los precios Y EL PROVEEDOR en la tabla de artículos
            Log::info("Actualizando artículo ID: {$producto['id']}");
            
            $updateData = [
                'precio_compra' => $producto['precio'],
                'precio_venta' => $producto['precio_venta'] ?? 0,
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

            // Aumentar el stock en la tabla 'articulos'
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
        // Lógica para la página de detalles de compra
        $compra = Compra::findOrFail($id);
        return view('administracion.compras.detalles', compact('compra'));
    }

    public function factura($id)
    {
        // Lógica para la página de factura
        $compra = Compra::findOrFail($id);
        return view('administracion.compras.factura', compact('compra'));
    }

    public function ticket($id)
    {
        // Lógica para la página de ticket
        $compra = Compra::findOrFail($id);
        return view('administracion.compras.ticket', compact('compra'));
    }


    
}
