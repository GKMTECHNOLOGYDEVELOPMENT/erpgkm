<?php

namespace App\Http\Controllers\administracion\compras;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
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
