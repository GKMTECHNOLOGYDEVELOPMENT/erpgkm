<?php

namespace App\Http\Controllers\almacen\custodia;

use App\Http\Controllers\Controller;
use App\Models\Custodia;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class CustodiaController extends Controller
{

    public function index()
    {
        $custodias = Custodia::with([
            'ticket',
            'ticket.cliente',
            'ticket.cliente.tipoDocumento',
            'ticket.marca',
            'ticket.modelo'
        ])
            ->where('estado', '!=', 'Rechazado')   // ⬅️ ocultar rechazados en la lista
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        // Tarjetas (sin Rechazado en el total)
        $totalSolicitudes = Custodia::where('estado', '!=', 'Rechazado')->count(); // total sin rechazados
        $pendientes       = Custodia::where('estado', 'Pendiente')->count();
        $enCustodia       = Custodia::where('estado', 'Aprobado')->count();      // SOLO aprobados
        $devueltos        = Custodia::where('estado', 'Devuelto')->count();

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
        ]);

        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Ticket no encontrado'], 404);
        }

        $nuevoEstado = (int) $request->input('es_custodia');
        $custodia = Custodia::where('id_ticket', $ticket->idTickets)->first();

        // ✅ PONER EN CUSTODIA
        if ($nuevoEstado === 1) {
            $ubicacion = trim($request->input('ubicacion_actual'));

            if (!$custodia) {
                $custodia = Custodia::create([
                    'codigocustodias'        => strtoupper(Str::random(10)),
                    'id_ticket'              => $ticket->idTickets,
                    'estado'                 => 'Pendiente',
                    'fecha_ingreso_custodia' => now()->toDateString(),
                    'ubicacion_actual'       => $ubicacion,               // 👈 guarda ubicación
                    'responsable_entrega'    => null,
                    'responsable_recepcion'  => auth()->id(),
                    'observaciones'          => null,
                    'fecha_devolucion'       => null,
                ]);
            } else {
                // Si existía, actualiza ubicación (y revive si estaba Rechazado)
                $custodia->ubicacion_actual = $ubicacion;               // 👈 actualiza ubicación
                if ($custodia->estado === 'Rechazado') {
                    $custodia->estado = 'Pendiente';
                }
                if (empty($custodia->fecha_ingreso_custodia)) {
                    $custodia->fecha_ingreso_custodia = now()->toDateString();
                }
                $custodia->save();
            }

            $ticket->es_custodia = 1;
            $ticket->save();

            return response()->json([
                'success' => true,
                'es_custodia' => 1,
                'ubicacion_actual' => $custodia->ubicacion_actual,
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

            return response()->json(['success' => true, 'es_custodia' => 0]);
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

        return view('solicitud.solicitudcustodia.opciones', compact('custodia'));
    }



      public function update(Request $request, $id)
    {
        $custodia = Custodia::findOrFail($id);
        
        // Validar los datos
        $validated = $request->validate([
            'estado' => 'required|in:Pendiente,En revisión,Aprobado,Rechazado,Devuelto',
            'ubicacion_actual' => 'required_if:estado,Aprobado|max:100',
            'observaciones' => 'nullable|string',
        ]);
        
        // Si ya está aprobado, no permitir cambiar la ubicación
        if ($custodia->estado === 'Aprobado' && $request->estado !== 'Aprobado') {
            $validated['ubicacion_actual'] = $custodia->ubicacion_actual;
        }
        
        // Actualizar la custodia
        $custodia->update($validated);
        
        // Siempre devolver respuesta JSON
        return response()->json([
            'success' => true,
            'message' => 'Custodia actualizada correctamente',
            'estado_actualizado' => $custodia->estado
        ]);
    }

  
}
