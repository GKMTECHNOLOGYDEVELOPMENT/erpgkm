<?php

namespace App\Http\Controllers\administracion\asistencias;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Asistencia;
use App\Models\Usuario;

class AsistenciaController extends Controller
{
    public function index()
    {
        return view('administracion.asistencia.index');
    }


    public function getAsistencias(Request $request)
    {
        $asistencias = \App\Models\Asistencia::with('usuario')
            ->when($request->startDate, function ($query) use ($request) {
                $query->whereDate('fechaHora', '>=', $request->startDate);
            })
            ->when($request->endDate, function ($query) use ($request) {
                $query->whereDate('fechaHora', '<=', $request->endDate);
            })
            ->get()
            ->groupBy(function ($asistencia) {
                return $asistencia->idUsuario . '-' . \Carbon\Carbon::parse($asistencia->fechaHora)->format('Y-m-d');
            });


        $datos = $asistencias->map(function ($grupo) {
            $usuario = $grupo->first()->usuario;
            $fecha = \Carbon\Carbon::parse($grupo->first()->fechaHora)->format('Y-m-d');


            $entrada = $grupo->firstWhere('idTipoHorario', 1);
            $inicioBreak = $grupo->firstWhere('idTipoHorario', 2);
            $finBreak = $grupo->firstWhere('idTipoHorario', 3);
            $salida = $grupo->firstWhere('idTipoHorario', 4);

            return [
                'empleado' => $usuario->Nombre ?? 'Sin nombre',
                'fecha' => \Carbon\Carbon::parse($grupo->first()->fechaHora)->format('Y-m-d'),
                'entrada' => $entrada?->fechaHora ? \Carbon\Carbon::parse($entrada->fechaHora)->format('h:i A') : null,
                'ubicacion_entrada' => $entrada?->ubicacion,
                'inicio_break' => $inicioBreak?->fechaHora ? \Carbon\Carbon::parse($inicioBreak->fechaHora)->format('h:i A') : null,
                'fin_break' => $finBreak?->fechaHora ? \Carbon\Carbon::parse($finBreak->fechaHora)->format('h:i A') : null,
                'salida' => $salida?->fechaHora ? \Carbon\Carbon::parse($salida->fechaHora)->format('h:i A') : null,
                'ubicacion_salida' => $salida?->ubicacion,
            ];
        })->values();

        return response()->json(['data' => $datos]);
    }
}
