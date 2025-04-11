<?php

namespace App\Http\Controllers\administracion\asistencias;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Asistencia;
use App\Models\Usuario;
use Yajra\DataTables\Facades\DataTables;

class AsistenciaController extends Controller
{
    public function index()
    {
        return view('administracion.asistencia.index');
    }

    public function getAsistencias(Request $request)
    {
        $usuarios = Usuario::whereHas('asistencias', function ($q) use ($request) {
            $q->where('idTipoHorario', 1);
            if ($request->startDate) {
                $q->whereDate('fechaHora', '>=', $request->startDate);
            }
            if ($request->endDate) {
                $q->whereDate('fechaHora', '<=', $request->endDate);
            }
        })->with(['asistencias' => function ($q) use ($request) {
            if ($request->startDate) {
                $q->whereDate('fechaHora', '>=', $request->startDate);
            }
            if ($request->endDate) {
                $q->whereDate('fechaHora', '<=', $request->endDate);
            }
        }]);

        return DataTables::of($usuarios)
            ->addColumn(
                'empleado',
                fn($u) =>
                trim("{$u->Nombre} {$u->apellidoPaterno} {$u->apellidoMaterno}")
            )


            ->addColumn('fecha', function ($u) {
                $entrada = $u->asistencias->where('idTipoHorario', 1)->first();
                return $entrada && $entrada->fechaHora
                    ? Carbon::parse($entrada->fechaHora)->format('Y-m-d')
                    : 'N/A';
            })

            ->addColumn('entrada', function ($u) {
                $entrada = $u->asistencias->where('idTipoHorario', 1)->first();
                return $entrada && $entrada->fechaHora
                    ? Carbon::parse($entrada->fechaHora)->format('h:i A')
                    : 'N/A';
            })

            ->addColumn('ubicacion_entrada', function ($u) {
                $entrada = $u->asistencias->where('idTipoHorario', 1)->first();
                return $entrada->ubicacion ?? 'N/A';
            })

            ->addColumn('inicio_break', function ($u) {
                $inicio = $u->asistencias->where('idTipoHorario', 2)->first();
                return $inicio && $inicio->fechaHora
                    ? Carbon::parse($inicio->fechaHora)->format('h:i A')
                    : 'N/A';
            })

            ->addColumn('fin_break', function ($u) {
                $fin = $u->asistencias->where('idTipoHorario', 3)->first();
                return $fin && $fin->fechaHora
                    ? Carbon::parse($fin->fechaHora)->format('h:i A')
                    : 'N/A';
            })

            ->addColumn('salida', function ($u) {
                $salida = $u->asistencias->where('idTipoHorario', 4)->first();
                return $salida && $salida->fechaHora
                    ? Carbon::parse($salida->fechaHora)->format('h:i A')
                    : 'N/A';
            })

            ->addColumn('ubicacion_salida', function ($u) {
                $salida = $u->asistencias->where('idTipoHorario', 4)->first();
                return $salida->ubicacion ?? 'N/A';
            })

            ->toJson();
    }
}
