<?php

namespace App\Http\Controllers\almacen\devoluciones;

use App\Http\Controllers\Controller;
use App\Models\DevolucionCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DevolucionesController extends Controller
{
   public function index()
    {
        return view('administracion.devoluciones-compra.index');
    }

    public function data(Request $request)
    {
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
        $q       = trim((string)$request->input('q', ''));

        // Base query con relaciones
        $query = DevolucionCompra::with([
                'producto:idArticulos,nombre',
                'usuario:idUsuario,Nombre',
                'compra:idCompra,serie,nro'
            ])
            ->select([
                'idDevolucionCompra',
                'idCompra',
                'idProducto',
                'idUsuario',
                'cantidad',
                'precio_unitario',
                'total_devolucion',
                'motivo',
                'fecha_devolucion',
                'created_at'
            ]);

        // Filtros por fecha
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_devolucion', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_devolucion', '<=', $request->fecha_fin);
        }

        // Filtro de búsqueda (mínimo 3 chars)
        if (strlen($q) >= 3) {
            $query->where(function ($qq) use ($q) {
                $qq->whereHas('producto', function ($p) use ($q) {
                    $p->where('nombre', 'like', $q . '%');
                })
                ->orWhereHas('usuario', function ($u) use ($q) {
                    $u->where('Nombre', 'like', $q . '%');
                })
                ->orWhere('motivo', 'like', '%' . $q . '%');
            });
        }

        // Orden: devoluciones más recientes primero
        $devoluciones = $query->orderByDesc('fecha_devolucion')
            ->orderByDesc('idDevolucionCompra')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $devoluciones->items(),
            'pagination' => [
                'current_page' => $devoluciones->currentPage(),
                'last_page'    => $devoluciones->lastPage(),
                'per_page'     => $devoluciones->perPage(),
                'total'        => $devoluciones->total(),
                'from'         => $devoluciones->firstItem(),
                'to'           => $devoluciones->lastItem(),
            ]
        ]);
    }


  
}
