<?php

namespace App\Http\Controllers\Unity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SolicitudIngreso;

class UnityController extends Controller
{
    public function solicitud(int $id = null, Request $request)
    {
        $id = $id ?? (int)($request->query('idSolicitudIngreso') ?? $request->query('id'));
        abort_unless($id > 0, 404, 'Solicitud no especificada.');
        $solicitud = SolicitudIngreso::find($id);
        abort_unless($solicitud, 404, 'Solicitud no encontrada.');
        return view('unity.solicitud', ['idSolicitud' => $id]);
    }
    public function solicitudData(int $id)
    {
        $s = SolicitudIngreso::with('detalles')->findOrFail($id);
        return response()->json($s);
    }
    public function registrarAccion(int $id, Request $request)
    {
        \Log::info('Unity registrarAccion', ['id' => $id, 'payload' => $request->all()]);
        return response()->json(['ok' => true]);
    }

       public function racksModeloCreate()
    {
        // Si existe la vista, la carga; si no, devuelve HTML simple para pruebas
        if (view()->exists('unity.racks.modelo.create')) {
            return view('unity.racks.modelo.create');
        }
        return response('<h1>Crear modelo Rack</h1><p>Ruta de prueba activa (UnityController@racksModeloCreate).</p>', 200)
            ->header('Content-Type', 'text/html; charset=utf-8');
    }

    public function racksAsignarIndex()
    {
        if (view()->exists('unity.racks.asignar.index')) {
            return view('unity.racks.asignar.index');
        }
        return response('<h1>Asignar Rack</h1><p>Ruta de prueba activa (UnityController@racksAsignarIndex).</p>', 200)
            ->header('Content-Type', 'text/html; charset=utf-8');
    }

    public function cajasCreate()
    {
        if (view()->exists('unity.cajas.create')) {
            return view('unity.cajas.create');
        }
        return response('<h1>Creación de Cajas</h1><p>Ruta de prueba activa (UnityController@cajasCreate).</p>', 200)
            ->header('Content-Type', 'text/html; charset=utf-8');
    }
}
