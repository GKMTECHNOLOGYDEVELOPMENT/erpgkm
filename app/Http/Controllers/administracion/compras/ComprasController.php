<?php

namespace App\Http\Controllers\administracion\compras;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class ComprasController extends Controller
{
    public function index()
    {
        
        return view('administracion.compras.index');
    }


     public function create()
    {
        $documentos = DB::table('documento')->get();
        
        return view('administracion.compras.create', compact('documentos'));
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

    // Método para guardar la compra
    public function guardarCompra(Request $request)
    {
        try {
            DB::beginTransaction();

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

            // Insertar la compra
            $compraId = DB::table('compra')->insertGetId([
                'serie' => $request->serie,
                'nro' => $request->nro,
                'fechaEmision' => $request->fecha,
                'fechaVencimiento' => $request->fecha_vencimiento,
                'imagen' => null, // Si tienes imagen, procésala aquí
                'sujetoporcentaje' => $request->sujeto_porcentaje ?? 0,
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
            ]);

            // Si tienes una tabla de detalle de compra, insertar los productos aquí
           foreach ($request->productos as $producto) {
                // Insertar detalle de compra
                DB::table('detalle_compra')->insert([
                    'idCompra' => $compraId,
                    'idProducto' => $producto['id'],
                    'cantidad' => $producto['cantidad'],
                    'precio' => $producto['precio'],
                    'subtotal' => $producto['subtotal'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Aumentar el stock en la tabla 'articulos'
                DB::table('articulos')->where('idArticulos', $producto['id'])->increment('stock_total', $producto['cantidad']);
            }


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Compra guardada exitosamente',
                'compra_id' => $compraId
            ]);

        } catch (Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la compra: ' . $e->getMessage()
            ], 500);
        }
    }
}