<?php

namespace App\Http\Controllers\administracion\cotizacionesl;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class cotizacionlController extends Controller
{
 public function index()
{
    try {
        // Obtener tickets que tienen suministros pendientes por cotizar
        $ticketsConSuministros = DB::table('tickets')
            ->join('suministros', 'tickets.idTickets', '=', 'suministros.idTickets')
            ->join('visitas', 'suministros.idVisitas', '=', 'visitas.idVisitas')
            ->leftJoin('cliente', 'tickets.idCliente', '=', 'cliente.idCliente')
            ->leftJoin('clientegeneral', 'tickets.idClienteGeneral', '=', 'clientegeneral.idClienteGeneral')
            ->select(
                'tickets.idTickets',
                'tickets.numero_ticket',
                'tickets.fecha_creacion',
                'tickets.fallaReportada',
                'tickets.idEstadoots',
                'visitas.nombre as visita_nombre',
                'visitas.fecha_programada',
                DB::raw('COUNT(suministros.idSuministros) as total_suministros'),
                DB::raw('SUM(suministros.cantidad) as total_items'),
                DB::raw('COALESCE(cliente.nombre, clientegeneral.descripcion) as cliente_nombre')
            )
            ->groupBy(
                'tickets.idTickets',
                'tickets.numero_ticket', 
                'tickets.fecha_creacion',
                'tickets.fallaReportada',
                'tickets.idEstadoots',
                'visitas.nombre',
                'visitas.fecha_programada',
                'cliente.nombre',
                'clientegeneral.descripcion'
            )
            ->get();

        // Obtener todos los estados disponibles para el filtro
        $estados = [
            ['id' => 2, 'nombre' => 'Pendiente Cotización'],
            ['id' => 4, 'nombre' => 'En Proceso'],
            ['id' => 5, 'nombre' => 'Completado'],
            ['id' => 6, 'nombre' => 'Cancelado']
        ];

        return view('administracion.cotizacionesl.index', compact('ticketsConSuministros', 'estados'));
        
    } catch (\Exception $e) {
        Log::error('Error en index de cotizaciones: ' . $e->getMessage());
        
        // Versión de fallback
        $ticketsConSuministros = DB::table('tickets')
            ->join('suministros', 'tickets.idTickets', '=', 'suministros.idTickets')
            ->join('visitas', 'suministros.idVisitas', '=', 'visitas.idVisitas')
            ->select(
                'tickets.idTickets',
                'tickets.numero_ticket',
                'tickets.fecha_creacion',
                'tickets.fallaReportada',
                'tickets.idEstadoots',
                'visitas.nombre as visita_nombre',
                'visitas.fecha_programada',
                DB::raw('COUNT(suministros.idSuministros) as total_suministros'),
                DB::raw('SUM(suministros.cantidad) as total_items'),
                DB::raw("'Cliente General' as cliente_nombre")
            )
            ->groupBy(
                'tickets.idTickets',
                'tickets.numero_ticket', 
                'tickets.fecha_creacion',
                'tickets.fallaReportada',
                'tickets.idEstadoots',
                'visitas.nombre',
                'visitas.fecha_programada'
            )
            ->get();

        $estados = [
            ['id' => 2, 'nombre' => 'Pendiente Cotización'],
            ['id' => 4, 'nombre' => 'En Proceso'],
            ['id' => 5, 'nombre' => 'Completado'],
            ['id' => 6, 'nombre' => 'Cancelado']
        ];

        return view('administracion.cotizacionesl.index', compact('ticketsConSuministros', 'estados'));
    }
}

    

}
