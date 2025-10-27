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
}
