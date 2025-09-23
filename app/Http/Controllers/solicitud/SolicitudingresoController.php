<?php

namespace App\Http\Controllers\solicitud;

use App\Http\Controllers\Controller;
use App\Models\ArticuloUbicacion;
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
                'si.observaciones',
                'a.nombre as articulo',
                'a.idArticulos as articulo_id'
            )
            ->leftJoin('articulos as a', 'si.articulo_id', '=', 'a.idArticulos')
            ->where('si.compra_id', $compraId)
            ->orderBy('si.idSolicitudIngreso')
            ->get();

        // Obtener las ubicaciones de cada artículo
        foreach ($solicitudes as $solicitud) {
            $solicitud->ubicaciones = DB::table('articulo_ubicaciones as au')
                ->select(
                    'au.cantidad',
                    'u.nombre as ubicacion_nombre',
                    'u.idUbicacion'
                )
                ->leftJoin('ubicacion as u', 'au.ubicacion_id', '=', 'u.idUbicacion')
                ->where('au.articulo_id', $solicitud->articulo_id)
                ->get();
        }

        return response()->json(['solicitudes' => $solicitudes]);
    }



    public function procesar(Request $request)
    {
        try {
            $data = $request->validate([
                'id' => 'required|integer|exists:solicitud_ingreso,idSolicitudIngreso',
                'articulo_id' => 'required|integer|exists:articulos,idArticulos',
                'estado' => 'required|in:pendiente,recibido,ubicado',
                'ubicaciones' => 'required|array|min:1',
                'ubicaciones.*.idUbicacion' => 'required|integer|exists:ubicacion,idUbicacion',
                'ubicaciones.*.cantidad' => 'required|integer|min:1',
                'observaciones' => 'nullable|string'
            ]);

            DB::beginTransaction();

            // 1. Actualizar el estado de la solicitud
            $updateData = [
                'estado' => $data['estado'],
                'observaciones' => $data['observaciones'],
                'updated_at' => now()
            ];

            if ($data['estado'] === 'ubicado') {
                $updateData['fecha_ubicado'] = now();
            } elseif ($data['estado'] === 'recibido') {
                $updateData['fecha_recibido'] = now();
            }

            DB::table('solicitud_ingreso')
                ->where('idSolicitudIngreso', $data['id'])
                ->update($updateData);

            // 2. Guardar las ubicaciones en la nueva tabla
            if ($data['estado'] === 'ubicado') {
                // Eliminar ubicaciones anteriores del artículo
                DB::table('articulo_ubicaciones')
                    ->where('articulo_id', $data['articulo_id'])
                    ->delete();

                // Insertar nuevas ubicaciones
                foreach ($data['ubicaciones'] as $ubicacion) {
                    ArticuloUbicacion::create([
                        'articulo_id' => $data['articulo_id'],
                        'ubicacion_id' => $ubicacion['idUbicacion'],
                        'cantidad' => $ubicacion['cantidad']
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Solicitud actualizada correctamente'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ]);
        }
    }


     // Método para obtener las ubicaciones de un artículo
    public function obtenerUbicacionesArticulo($articuloId)
    {
        $ubicaciones = DB::table('articulo_ubicaciones as au')
            ->select(
                'au.idArticuloUbicacion',
                'au.cantidad',
                'u.idUbicacion',
                'u.nombre as ubicacion_nombre'
            )
            ->leftJoin('ubicacion as u', 'au.ubicacion_id', '=', 'u.idUbicacion')
            ->where('au.articulo_id', $articuloId)
            ->get();

        return response()->json(['ubicaciones' => $ubicaciones]);
    }
}