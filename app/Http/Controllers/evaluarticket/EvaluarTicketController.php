<?php
// app/Http/Controllers/evaluarticket/EvaluarTicketController.php

namespace App\Http\Controllers\evaluarticket;

use App\Http\Controllers\Controller;
use App\Models\TicketClienteGeneral;
use App\Models\Categoria;
use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EvaluarTicketController extends Controller
{
    /**
     * Display a listing of the tickets for evaluation.
     */
    public function index()
    {
        // Renderizar la vista
        return view('evaluarticket.index');
    }

    /**
     * API endpoint para obtener los tickets (para el JS)
     */
   public function getTickets()
{
    try {
        // Obtener todos los tickets con sus relaciones
        $tickets = TicketClienteGeneral::with([
            'categoria',
            'modelo.marca',
            'tipoDocumento',
            'clienteGeneral' // Agregar relación con cliente general
        ])
        ->orderBy('fechaCreacion', 'desc')
        ->get();

        // Transformar los datos para el frontend
        $ticketsTransformados = $tickets->map(function ($ticket) {
            // Mapear los estados directamente
            $estadoTexto = 'evaluando'; // valor por defecto para estado=1
            
            if ($ticket->estado == 2) {
                $estadoTexto = 'gestionando';
            } elseif ($ticket->estado == 3) {
                $estadoTexto = 'finalizado';
            }

            // Procesar foto del cliente general si existe (BLOB)
            $fotoClienteBase64 = null;
            if ($ticket->clienteGeneral && $ticket->clienteGeneral->foto) {
                // Convertir BLOB a base64
                $fotoClienteBase64 = 'data:image/jpeg;base64,' . base64_encode($ticket->clienteGeneral->foto);
            }

            return [
                'id' => $ticket->idTicket,
                'numeroTicket' => $ticket->numero_ticket,
                
                // Datos cliente general
                'idClienteGeneral' => $ticket->idClienteGeneral,
                'clienteGeneral' => $ticket->clienteGeneral ? [
                    'id' => $ticket->clienteGeneral->idClienteGeneral,
                    'descripcion' => $ticket->clienteGeneral->descripcion,
                    'foto' => $fotoClienteBase64
                ] : null,
                
                // Datos cliente (persona que reporta)
                'nombreCompleto' => $ticket->nombreCompleto,
                'correoElectronico' => $ticket->correoElectronico,
                'telefonoCelular' => $ticket->telefonoCelular,
                'telefonoFijo' => $ticket->telefonoFijo,
                'tipoDocumento' => $ticket->tipoDocumento ? $ticket->tipoDocumento->nombre : 'N/A',
                'dni_ruc_ce' => $ticket->dni_ruc_ce,
                
                // DATOS DE DIRECCIÓN
                'direccionCompleta' => $ticket->direccionCompleta,
                'referenciaDomicilio' => $ticket->referenciaDomicilio,
                'departamento' => $ticket->departamento,
                'provincia' => $ticket->provincia,
                'distrito' => $ticket->distrito,
                'ubicacionGoogleMaps' => $ticket->ubicacionGoogleMaps,
                
                // Datos producto
                'tipoProducto' => $ticket->categoria ? $ticket->categoria->nombre : 'N/A',
                'marca' => $ticket->modelo && $ticket->modelo->marca ? $ticket->modelo->marca->nombre : 'N/A',
                'modelo' => $ticket->modelo ? $ticket->modelo->nombre : 'N/A',
                'serie' => $ticket->serieProducto,
                
                // Falla
                'detallesFalla' => $ticket->detallesFalla,
                
                // Fechas
                'fechaCreacion' => date('Y-m-d H:i', strtotime($ticket->fechaCreacion)),
                'fechaCompra' => $ticket->fechaCompra,
                'tiendaSedeCompra' => $ticket->tiendaSedeCompra,
                
                // EVIDENCIAS - FOTOS
                'fotoVideoFalla' => $ticket->fotoVideoFalla,
                'fotoBoletaFactura' => $ticket->fotoBoletaFactura,
                'fotoNumeroSerie' => $ticket->fotoNumeroSerie,
                
                // Estado
                'estado' => $estadoTexto,
                'estado_valor' => $ticket->estado
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $ticketsTransformados
        ], 200);

    } catch (\Exception $e) {
        Log::error('Error en EvaluarTicketController@getTickets: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener tickets para evaluación',
            'error' => $e->getMessage()
        ], 500);
    }
}
}