<?php

namespace App\Http\Controllers\almacen\custodia;

use App\Http\Controllers\Controller;
use App\Models\Custodia;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class CustodiaController extends Controller
{

   public function index()
{
    // Obtener las custodias con sus relaciones
    $custodias = Custodia::with([
            'ticket', 
            'ticket.cliente', 
            'ticket.cliente.tipoDocumento',
            'ticket.marca', 
            'ticket.modelo'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(9);
    
    // Contar por estados
    $totalCustodias = Custodia::count();
    $pendientes = Custodia::where('estado', 'Pendiente')->count();
    $enCustodia = Custodia::where('estado', 'Aprobado')->count();
    $devueltos = Custodia::where('estado', 'Devuelto')->count();

    return view('solicitud.solicitudcustodia.index', compact(
        'custodias', 
        'totalCustodias', 
        'pendientes', 
        'enCustodia', 
        'devueltos'
    ));
}

public function actualizarCustodia(Request $request, $id)
{
    $ticket = Ticket::find($id);

    if (!$ticket) {
        return response()->json(['success' => false, 'message' => 'Ticket no encontrado'], 404);
    }

    $nuevoEstado = $request->input('es_custodia') ? 1 : 0;

    $custodia = Custodia::where('id_ticket', $ticket->idTickets)->first();

    // ✅ MARCAR CUSTODIA
    if ($nuevoEstado === 1) {
        if (!$custodia) {
            // Generar código aleatorio de 10 caracteres
            $codigo = strtoupper(Str::random(10));

            // Crear nueva custodia
            Custodia::create([
                'codigocustodias' => $codigo,
                'id_ticket' => $ticket->idTickets,
                'estado' => 'Pendiente',
                'fecha_ingreso_custodia' => now()->toDateString(),
                'ubicacion_actual' => '',
                'responsable_entrega' => Auth::user()->name,
                'responsable_recepcion' => null,
                'observaciones' => null,
                'fecha_devolucion' => null,
            ]);
        } else {
            // Si ya existe y está en estado Rechazado → volver a Pendiente
            if ($custodia->estado === 'Rechazado') {
                $custodia->estado = 'Pendiente';
                $custodia->save();
            }
        }

        $ticket->es_custodia = 1;
        $ticket->save();

        return response()->json(['success' => true, 'es_custodia' => 1]);
    }

    // ✅ DESMARCAR CUSTODIA
    if ($nuevoEstado === 0) {
        if ($custodia) {
            if ($custodia->estado === 'Pendiente') {
                $ticket->es_custodia = 0;
                $ticket->save();

                $custodia->estado = 'Rechazado';
                $custodia->save();

                return response()->json(['success' => true, 'es_custodia' => 0]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede desmarcar la custodia porque está en estado: ' . $custodia->estado,
                ], 403);
            }
        } else {
            $ticket->es_custodia = 0;
            $ticket->save();

            return response()->json(['success' => true, 'es_custodia' => 0]);
        }
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


}