<?php

namespace App\Http\Controllers\solicitud;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudingresoController extends Controller
{
    public function index()
    {
        // Obtener las compras que TIENEN solicitudes de ingreso
        $comprasConSolicitudes = DB::table('compra as c')
            ->select(
                'c.idCompra',
                'c.codigocompra',
                'c.fechaEmision as fecha_compra',
                'c.total',
                'p.nombre as proveedor',
                DB::raw('COUNT(si.idSolicitudIngreso) as total_solicitudes'),
                DB::raw('SUM(CASE WHEN si.estado = "pendiente" THEN 1 ELSE 0 END) as pendientes'),
                DB::raw('SUM(CASE WHEN si.estado = "recibido" THEN 1 ELSE 0 END) as recibidos'),
                DB::raw('SUM(CASE WHEN si.estado = "ubicado" THEN 1 ELSE 0 END) as ubicados')
            )
            ->join('solicitud_ingreso as si', 'c.idCompra', '=', 'si.compra_id')
            ->leftJoin('proveedores as p', 'c.proveedor_id', '=', 'p.idProveedor')
            ->groupBy('c.idCompra', 'c.codigocompra', 'c.fechaEmision', 'c.total', 'p.nombre')
            ->having('total_solicitudes', '>', 0)
            ->orderBy('c.fechaEmision', 'desc')
            ->get();

        // Obtener las ubicaciones
        $ubicaciones = DB::table('ubicacion')
            ->select('idUbicacion', 'nombre')
            ->orderBy('nombre')
            ->get();

        return view('solicitud.solicitudingreso.index', compact('comprasConSolicitudes', 'ubicaciones'));
    }

    public function porCompra($compraId)
    {
        $solicitudes = DB::table('solicitud_ingreso as si')
            ->select(
                'si.idSolicitudIngreso as id',
                'si.compra_id',
                'si.codigo_solicitud',
                'si.estado',
                'si.cantidad',
                'si.ubicacion',
                'si.observaciones',
                'a.nombre as articulo',
                'a.idArticulos as articulo_id'
            )
            ->leftJoin('articulos as a', 'si.articulo_id', '=', 'a.idArticulos')
            ->where('si.compra_id', $compraId)
            ->orderBy('si.idSolicitudIngreso')
            ->get();

        return response()->json(['solicitudes' => $solicitudes]);
    }



    public function procesar(Request $request)
    {
        try {
            $data = $request->validate([
                'id' => 'required|integer|exists:solicitud_ingreso,idSolicitudIngreso',
                'estado' => 'required|in:pendiente,recibido,ubicado',
                'ubicacion_id' => 'nullable|integer|exists:ubicacion,idUbicacion',
                'ubicacion_nombre' => 'nullable|string|max:255',
                'observaciones' => 'nullable|string'
            ]);

            $updateData = [
                'estado' => $data['estado'],
                'observaciones' => $data['observaciones'],
                'updated_at' => now()
            ];

            if ($data['estado'] === 'ubicado') {
                $updateData['ubicacion'] = $data['ubicacion_nombre'];
                $updateData['fecha_ubicado'] = now();
            } elseif ($data['estado'] === 'recibido') {
                $updateData['fecha_recibido'] = now();
                $updateData['ubicacion'] = null;
            } else {
                $updateData['ubicacion'] = null;
            }

            DB::table('solicitud_ingreso')
                ->where('idSolicitudIngreso', $data['id'])
                ->update($updateData);

            return response()->json(['success' => true, 'message' => 'Solicitud actualizada correctamente']);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
        }
    }
}