<?php

namespace App\Http\Controllers\almacen\custodia;

use App\Http\Controllers\Controller;
use App\Models\Custodia;
use App\Models\CustodiaUbicacion;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class CustodiaController extends Controller
{

    public function index(Request $request)
    {
        $query = Custodia::with([
            'ticket',
            'ticket.cliente',
            'ticket.cliente.tipoDocumento',
            'ticket.marca',
            'ticket.modelo'
        ])
            ->where('estado', '!=', 'Rechazado');

        // Filtro de búsqueda
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('codigocustodias', 'like', "%{$search}%")
                    ->orWhereHas('ticket', function ($q2) use ($search) {
                        $q2->where('serie', 'like', "%{$search}%")
                            ->orWhere('numero_ticket', 'like', "%{$search}%")
                            ->orWhereHas('cliente', function ($q3) use ($search) {
                                $q3->where('nombre', 'like', "%{$search}%")
                                    ->orWhere('documento', 'like', "%{$search}%");
                            })
                            ->orWhereHas('marca', function ($q3) use ($search) {
                                $q3->where('nombre', 'like', "%{$search}%");
                            })
                            ->orWhereHas('modelo', function ($q3) use ($search) {
                                $q3->where('nombre', 'like', "%{$search}%");
                            });
                    });
            });
        }

        // Filtro por estado
        if ($request->has('estado') && $request->estado != 'Todos los estados') {
            $query->where('estado', $request->estado);
        }

        $custodias = $query->orderBy('created_at', 'desc')->paginate(9);

        // Estadísticas (siempre sin incluir rechazados)
        $totalSolicitudes = Custodia::where('estado', '!=', 'Rechazado')->count();
        $pendientes = Custodia::where('estado', 'Pendiente')->count();
        $enCustodia = Custodia::where('estado', 'Aprobado')->count();
        $devueltos = Custodia::where('estado', 'Devuelto')->count();

        // Mantener los filtros en la paginación
        if ($request->has('search')) {
            $custodias->appends(['search' => $request->search]);
        }
        if ($request->has('estado')) {
            $custodias->appends(['estado' => $request->estado]);
        }

        return view('solicitud.solicitudcustodia.index', compact(
            'custodias',
            'totalSolicitudes',
            'pendientes',
            'enCustodia',
            'devueltos'
        ));
    }


    public function actualizarCustodia(Request $request, $id)
    {
        $request->validate([
            'es_custodia' => 'required|boolean',
            'ubicacion_actual' => 'required_if:es_custodia,1|string|max:100',
            'erma' => 'nullable|string|max:50',
        ]);

        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Ticket no encontrado'], 404);
        }

        $nuevoEstado = (int) $request->input('es_custodia');
        $custodia = Custodia::where('id_ticket', $ticket->idTickets)->first();
        $erma = trim($request->input('erma'));

        // 🚨 Si no hay ERMA, automáticamente desactivar custodia
        if ($nuevoEstado === 1 && empty($erma)) {
            if ($custodia) {
                $custodia->estado = 'Rechazado';
                $custodia->save();
            }

            $ticket->es_custodia = 0;
            $ticket->save();

            return response()->json([
                'success' => false,
                'message' => '❌ No se puede activar custodia sin N. erma. Custodia desactivada automáticamente.',
                'es_custodia' => 0
            ], 400);
        }

        // ✅ PONER EN CUSTODIA
        if ($nuevoEstado === 1) {
            $ubicacion = trim($request->input('ubicacion_actual'));
            $fechaIngreso = $request->input('fecha_ingreso_custodia');

            if (!$custodia) {
                $custodia = Custodia::create([
                    'codigocustodias'        => strtoupper(Str::random(10)),
                    'id_ticket'              => $ticket->idTickets,
                    'estado'                 => 'Pendiente',
                    'fecha_ingreso_custodia' => $fechaIngreso ?? now()->toDateString(),
                    'ubicacion_actual'       => $ubicacion,
                    'responsable_entrega'    => null,
                    'id_responsable_recepcion'  => auth()->id(),
                    'observaciones'          => null,
                    'fecha_devolucion'       => null,
                ]);
            } else {
                $custodia->ubicacion_actual = $ubicacion;
                $custodia->fecha_ingreso_custodia = $fechaIngreso ?? $custodia->fecha_ingreso_custodia;

                if ($custodia->estado === 'Rechazado') {
                    $custodia->estado = 'Pendiente';
                }

                $custodia->save();
            }

            $ticket->es_custodia = 1;
            $ticket->erma = $erma; // ✅ Guardar erma si existe
            $ticket->save();

            return response()->json([
                'success' => true,
                'message' => '✅ Equipo puesto en custodia correctamente.',
                'es_custodia' => 1,
                'ubicacion_actual' => $custodia->ubicacion_actual,
                'fecha_ingreso_custodia' => $custodia->fecha_ingreso_custodia,
                'erma' => $ticket->erma
            ]);
        }

        // ✅ QUITAR DE CUSTODIA
        if ($nuevoEstado === 0) {
            if ($custodia) {
                if ($custodia->estado === 'Pendiente') {
                    $custodia->estado = 'Rechazado';
                    $custodia->save();
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede desmarcar la custodia porque está en estado: ' . $custodia->estado,
                    ], 403);
                }
            }

            $ticket->es_custodia = 0;
            $ticket->save();

            return response()->json([
                'success' => true,
                'message' => '✅ Custodia desactivada correctamente.',
                'es_custodia' => 0
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Acción no válida.'], 400);
    }





    public function verificarCustodia($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Ticket no encontrado'], 404);
        }

        return response()->json(['success' => true, 'es_custodia' => $ticket->es_custodia]);
    }

    public function opciones($id)
    {
        $custodia = Custodia::with([
            'ticket',
            'ticket.cliente',
            'ticket.cliente.tipoDocumento',
            'ticket.marca',
            'ticket.modelo',
            'responsableRecepcion'
        ])->findOrFail($id);

        $ubicaciones = Ubicacion::with('sucursal')->get();


        return view('solicitud.solicitudcustodia.opciones', compact('custodia', 'ubicaciones'));
    }

    public function update(Request $request, $id)
    {
        $custodia = Custodia::findOrFail($id);

        // Validar los datos
        $validated = $request->validate([
            'estado' => 'required|in:Pendiente,En revisión,Aprobado,Rechazado,Devuelto',
            'ubicacion_actual' => 'required_if:estado,Aprobado|max:100',
            'observaciones' => 'nullable|string',
            'idubicacion' => 'required_if:estado,Aprobado|exists:ubicacion,idUbicacion',
            'observacion_almacen' => 'nullable|string',
            'cantidad' => 'sometimes|integer|min:1'
        ]);

        // Desactivar verificaciones de FK temporalmente (SOLO DESARROLLO)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::beginTransaction();

        try {
            // Actualizar la custodia
            $custodia->update($validated);

            // Si el estado es Aprobado, guardar en custodia_ubicacion
            if ($request->estado === 'Aprobado') {
                $ubicacion = Ubicacion::find($request->idubicacion);

                // Usar updateOrCreate con el formato correcto
                CustodiaUbicacion::updateOrCreate(
                    ['idCustodia' => $custodia->id],
                    [
                        'idUbicacion' => $request->idubicacion,
                        'observacion' => $request->observacion_almacen,
                        'cantidad' => $request->cantidad ?? 1,
                        'updated_at' => now(),
                        'created_at' => now() // Asegurar created_at
                    ]
                );
            } else if ($request->estado !== 'Aprobado') {
                CustodiaUbicacion::where('idCustodia', $custodia->id)->delete();
            }

            DB::commit();

            // Reactivar verificaciones de FK
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return response()->json([
                'success' => true,
                'message' => 'Custodia actualizada correctamente',
                'estado_actualizado' => $custodia->estado
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la custodia: ' . $e->getMessage()
            ], 500);
        }
    }
}
