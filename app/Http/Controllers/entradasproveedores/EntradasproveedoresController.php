<?php
namespace App\Http\Controllers\entradasproveedores;

use App\Http\Controllers\Controller;
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
            'productos' => 'required|string'
        ]);

        $productos = json_decode($request->productos, true);
        
        if (empty($productos)) {
            throw new \Exception('No se encontraron productos para procesar');
        }

        // Insertar entrada principal
        $entradaId = DB::table('entradas_proveedores')->insertGetId([
            'tipo_entrada' => $request->tipo_entrada,
            'fecha_ingreso' => $request->fecha_ingreso,
            'cliente_general_id' => $request->cliente_general_id ?: null,
            'observaciones' => $request->observaciones,
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Insertar detalles de productos y actualizar stock
        foreach ($productos as $producto) {
            // Insertar detalle
            $detalleId = DB::table('entradas_proveedores_detalle')->insertGetId([
                'entrada_id' => $entradaId,
                'articulo_id' => $producto['id'],
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio_unitario'] ?? 0,
                'subtotal' => $producto['subtotal'] ?? 0,
                'ubicacion' => $producto['ubicacion'] ?? null,
                'lote' => $producto['lote'] ?? null,
                'fecha_vencimiento' => $producto['fecha_vencimiento'] ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Actualizar stock del artículo
            DB::table('articulos')
                ->where('idArticulos', $producto['id'])
                ->increment('stock_total', $producto['cantidad']);

            // ✅ Insertar en inventario_ingresos_clientes
            DB::table('inventario_ingresos_clientes')->insert([
                'compra_id' => $entradaId,
                'articulo_id' => $producto['id'],
                'tipo_ingreso' => 'entrada_proveedor',
                'ingreso_id' => $detalleId,
                'cliente_general_id' => $request->cliente_general_id ?: null,
                'cantidad' => $producto['cantidad'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Manejar archivo adjunto si existe
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $archivo->storeAs('entradas_proveedores', $nombreArchivo, 'public');
            
            DB::table('entradas_proveedores')
                ->where('id', $entradaId)
                ->update(['archivo_adjunto' => $nombreArchivo]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Entrada guardada exitosamente',
            'entrada_id' => $entradaId
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'success' => false,
            'message' => 'Error al guardar la entrada: ' . $e->getMessage()
        ], 500);
    }
}

}