<?php
namespace App\Http\Controllers\entradasproveedores;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EntradasproveedoresController extends Controller 
{
    public function index() {
        // Obtener todos los clientes generales activos
        $clientesGenerales = DB::table('clientegeneral')
            ->where('estado', 1)
            ->get();

        return view('almacen.entradasproveedores.index', compact('clientesGenerales'));
    }


    
public function buscarProductoEntrada(Request $request)
{
    $query = $request->get('q', '');
    
    Log::info('Búsqueda de producto entrada iniciada', ['query' => $query]);
    
    if (empty($query)) {
        return response()->json([
            'success' => false,
            'message' => 'Parámetro de búsqueda requerido',
            'productos' => []
        ]);
    }

    try {
        $productos = DB::table('articulos as a')
            ->leftJoin('modelo as m', 'a.idModelo', '=', 'm.idModelo') // LEFT JOIN
            ->leftJoin('marca as mr', 'm.idMarca', '=', 'mr.idMarca')  // LEFT JOIN
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->select(
                'a.idArticulos as id',
                'a.codigo_barras',
                'a.nombre',
                'a.stock_total',
                'a.precio_compra',
                'a.precio_venta',
                'a.sku',
                'a.codigo_repuesto',
                'a.idsubcategoria',
                'm.nombre as modelo',
                'mr.nombre as marca',
                'sc.nombre as subcategoria'
            )
            ->where('a.estado', 1)
            ->where(function($queryBuilder) use ($query) {
                $queryBuilder->where('a.nombre', 'LIKE', "%{$query}%")
                           ->orWhere('m.nombre', 'LIKE', "%{$query}%")
                           ->orWhere('mr.nombre', 'LIKE', "%{$query}%")
                           ->orWhere('a.codigo_barras', 'LIKE', "%{$query}%")
                           ->orWhere('a.sku', 'LIKE', "%{$query}%")
                           ->orWhere('a.codigo_repuesto', 'LIKE', "%{$query}%")
                           ->orWhere('sc.nombre', 'LIKE', "%{$query}%");
                
                if (is_numeric($query)) {
                    $queryBuilder->orWhere('a.idsubcategoria', '=', (int)$query);
                }
            })
            ->orderBy('a.nombre')
            ->limit(50)
            ->get();

        Log::info('Resultados de la búsqueda después de LEFT JOIN', [
            'total_resultados' => count($productos)
        ]);

        return response()->json([
            'success' => true,
            'productos' => $productos,
            'total' => count($productos)
        ]);

    } catch (\Exception $e) {
        Log::error('Error al buscar productos', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Error al buscar productos: ' . $e->getMessage(),
            'productos' => []
        ]);
    }
}


public function guardarEntradaProveedor(Request $request)
{
    try {
        DB::beginTransaction();

        // Validar datos básicos
        $request->validate([
            'tipo_entrada' => 'required|string',
            'fecha_ingreso' => 'required|date',
            'productos' => 'required|string',
            'cliente_general_id' => 'nullable|integer'
        ]);

        $productos = json_decode($request->productos, true);

        if (empty($productos)) {
            throw new \Exception('No se encontraron productos para procesar');
        }

        // Log de inicio
        Log::info('=== INICIO GUARDAR ENTRADA PROVEEDOR ===');
        Log::info('Datos recibidos:', [
            'tipo_entrada' => $request->tipo_entrada,
            'fecha_ingreso' => $request->fecha_ingreso,
            'cliente_general_id' => $request->cliente_general_id,
            'productos' => $productos
        ]);

        // Obtener usuario autenticado
        $usuario = auth()->user();
        if (!$usuario) {
            throw new \Exception('Usuario no autenticado');
        }

        // Insertar entrada principal
        $entradaId = DB::table('entradas_proveedores')->insertGetId([
            'tipo_entrada' => $request->tipo_entrada,
            'fecha_ingreso' => $request->fecha_ingreso,
            'cliente_general_id' => $request->cliente_general_id ?: null,
            'observaciones' => $request->observaciones ?? null,
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Log::info("Entrada_proveedor creada con ID: {$entradaId}");

        foreach ($productos as $producto) {
            Log::info("Procesando producto:", $producto);

            $articuloId = $producto['id'];
            $cantidad = $producto['cantidad'] ?? 0;

            // Insertar detalle
            $detalleId = DB::table('entradas_proveedores_detalle')->insertGetId([
                'entrada_id' => $entradaId,
                'articulo_id' => $articuloId,
                'cantidad' => $cantidad,
                'precio_unitario' => $producto['precio_unitario'] ?? 0,
                'subtotal' => $producto['subtotal'] ?? 0,
                'ubicacion' => $producto['ubicacion'] ?? null,
                'lote' => $producto['lote'] ?? null,
                'fecha_vencimiento' => $producto['fecha_vencimiento'] ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            Log::info("Detalle de entrada registrado detalleId: {$detalleId}");

            // Actualizar stock del artículo
            DB::table('articulos')
                ->where('idArticulos', $articuloId)
                ->increment('stock_total', $cantidad);

            Log::info("Stock incrementado para articuloId: {$articuloId}, cantidad: {$cantidad}");

            // Insertar en inventario_ingresos_clientes
            DB::table('inventario_ingresos_clientes')->insert([
                'compra_id' => $entradaId,
                'articulo_id' => $articuloId,
                'tipo_ingreso' => 'entrada_proveedor',
                'ingreso_id' => $detalleId,
                'cliente_general_id' => $request->cliente_general_id ?: null,
                'cantidad' => $cantidad,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            Log::info("Inventario_ingresos_clientes insertado articuloID: {$articuloId}, cliente_general_id: " . ($request->cliente_general_id ?: 'null'));
            // === CREAR SOLICITUD DE INGRESO SIMPLIFICADA ===
            try {
                // Generar código de solicitud (EP-001, EP-002, etc.)
                $ultimaSolicitud = DB::table('solicitud_ingreso')
                    ->orderBy('idSolicitudIngreso', 'desc')
                    ->first();
                
                $numeroSiguiente = $ultimaSolicitud ? 
                    intval(substr($ultimaSolicitud->codigo_solicitud, 3)) + 1 : 1;
                $codigoSolicitud = 'EP-' . str_pad($numeroSiguiente, 3, '0', STR_PAD_LEFT);

                // Obtener nombre del artículo
                $articulo = DB::table('articulos')->where('idArticulos', $articuloId)->first();
                $nombreArticulo = $articulo ? $articulo->nombre : 'Artículo ID: ' . $articuloId;

                // Crear la solicitud de ingreso simplificada
                $solicitudData = [
                    'compra_id' => null, // No hay compra asociada
                    'entrada_id' => $entradaId, // ← NUEVO CAMPO para entrada_proveedor
                    'codigo_solicitud' => $codigoSolicitud,
                    'articulo_id' => $articuloId,
                    'cantidad' => $cantidad,
                    'precio_compra' => $producto['precio_unitario'] ?? 0,
                    'numero_factura' => null,
                    'serie_factura' => null,
                    'fecha_compra' => null,
                    'fecha_esperada_ingreso' => $request->fecha_ingreso,
                    'proveedor_id' => null,
                    'cliente_general_id' => $request->cliente_general_id ?: null,
                    'estado' => 'pendiente',
                    'ubicacion' => $producto['ubicacion'] ?? null,
                    'observaciones' => "Entrada Proveedor: {$request->tipo_entrada} - Artículo: {$nombreArticulo}" . 
                                    ($request->observaciones ? " - Obs: {$request->observaciones}" : ""),
                    'fecha_recibido' => null,
                    'fecha_ubicado' => null,
                    'usuario_id' => $usuario->idUsuario,
                    'tipo_origen' => 'entrada_proveedor',
                    'origen_id' => $entradaId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                $solicitudId = DB::table('solicitud_ingreso')->insertGetId($solicitudData);
                Log::info("✅ SOLICITUD DE INGRESO CREADA - ID: {$solicitudId}, Código: {$codigoSolicitud}, Entrada ID: {$entradaId}");

            } catch (Exception $e) {
                Log::error("❌ ERROR al crear solicitud de ingreso: " . $e->getMessage());
            }

            // === Crear o actualizar en KARDEX ===
            $fechaIngreso = Carbon::parse($request->fecha_ingreso);
            $mes = $fechaIngreso->format('m');
            $anio = $fechaIngreso->format('Y');
            $clienteId = $request->cliente_general_id ?: null;

            // Ver si ya existe kardex para este artículo + mes + cliente
            $kardexExistente = DB::table('kardex')
                ->where('idArticulo', $articuloId)
                ->where('cliente_general_id', $clienteId)
                ->whereMonth('fecha', $mes)
                ->whereYear('fecha', $anio)
                ->first();

            if ($kardexExistente) {
                // Actualizar registro existente
                $nuevoInventarioActual = $kardexExistente->inventario_actual + $cantidad;

                $updateData = [
                    'unidades_entrada' => $kardexExistente->unidades_entrada + $cantidad,
                    'inventario_actual' => $nuevoInventarioActual,
                    'updated_at' => now()
                ];

                DB::table('kardex')
                    ->where('id', $kardexExistente->id)
                    ->update($updateData);

                Log::info("🔄 KARDEX actualizado para articuloId: {$articuloId}, cliente_general_id: {$clienteId}", $updateData);
            } else {
                // Crear nuevo registro
                $ultimoKardex = DB::table('kardex')
                    ->where('idArticulo', $articuloId)
                    ->where('cliente_general_id', $clienteId)
                    ->orderBy('fecha', 'desc')
                    ->first();

                $inventarioInicial = $ultimoKardex ? $ultimoKardex->inventario_actual : 0;
                $costoInventarioPrevio = $ultimoKardex ? $ultimoKardex->costo_inventario : 0;

                $nuevoInventarioActual = $inventarioInicial + $cantidad;

                $kardexData = [
                    'fecha' => $fechaIngreso,
                    'idArticulo' => $articuloId,
                    'unidades_entrada' => $cantidad,
                    'costo_unitario_entrada' => $producto['precio_unitario'] ?? 0,
                    'unidades_salida' => 0,
                    'costo_unitario_salida' => 0,
                    'inventario_inicial' => $inventarioInicial,
                    'inventario_actual' => $nuevoInventarioActual,
                    'costo_inventario' => $costoInventarioPrevio + (($producto['precio_unitario'] ?? 0) * $cantidad),
                    'cliente_general_id' => $clienteId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                Log::info("📝 Insertando nuevo KARDEX para articuloId: {$articuloId}, cliente_general_id: {$clienteId}", $kardexData);

                $kardexId = DB::table('kardex')->insertGetId($kardexData);

                Log::info("✅ Nuevo KARDEX creado con ID: {$kardexId}");
            }
        }

        // Manejar archivo adjunto si existe
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $archivo->storeAs('entradas_proveedores', $nombreArchivo, 'public');

            DB::table('entradas_proveedores')
                ->where('id', $entradaId)
                ->update(['archivo_adjunto' => $nombreArchivo]);
            
            Log::info("Archivo adjunto guardado como: {$nombreArchivo}");
        }

        DB::commit();

        Log::info("✅ ENTRADA PROVEEDOR GUARDADA EXITOSAMENTE ID: {$entradaId}");

        return response()->json([
            'success' => true,
            'message' => 'Entrada guardada exitosamente',
            'entrada_id' => $entradaId
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        Log::error('❌ ERROR AL GUARDAR ENTRADA DE PROVEEDOR: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json([
            'success' => false,
            'message' => 'Error al guardar la entrada: ' . $e->getMessage()
        ], 500);
    }
}
}