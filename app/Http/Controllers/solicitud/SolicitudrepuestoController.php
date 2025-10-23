<?php

namespace App\Http\Controllers\solicitud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudrepuestoController extends Controller
{
    public function index()
    {
        // Datos de ejemplo - luego los reemplazarás con tu modelo
        $estadisticas = [
            'pendientes' => 12,
            'aprobadas' => 8,
            'rechazadas' => 3,
            'total' => 23
        ];

        $solicitudes = [
            [
                'id' => 'SOL-001',
                'solicitante' => 'Juan Pérez',
                'departamento' => 'Taller Mecánico',
                'repuesto' => 'Filtro de Aceite',
                'cantidad' => 5,
                'fecha' => '15 Mar 2024',
                'estado' => 'pendiente'
            ],
            [
                'id' => 'SOL-002',
                'solicitante' => 'María García',
                'departamento' => 'Electricidad',
                'repuesto' => 'Bujías',
                'cantidad' => 12,
                'fecha' => '14 Mar 2024',
                'estado' => 'aprobado'
            ],
            [
                'id' => 'SOL-003',
                'solicitante' => 'Carlos López',
                'departamento' => 'Pintura',
                'repuesto' => 'Pastillas de Freno',
                'cantidad' => 4,
                'fecha' => '13 Mar 2024',
                'estado' => 'rechazado'
            ]
        ];

        return view("solicitud.solicitudrepuesto.index", compact('estadisticas', 'solicitudes'));
    }

public function create()
{
    $userId = auth()->id();
    
    $tickets = DB::table('tickets as t')
        ->select(
            't.idTickets',
            't.numero_ticket',
            't.idModelo',
            'm.nombre as modelo_nombre'
        )
        ->leftJoin('modelo as m', 't.idModelo', '=', 'm.idModelo')
        ->where('t.idTipotickets', 1)
        ->where(function($query) use ($userId) {
            if ($userId == 1) {
                return $query;
            } else {
                return $query->whereExists(function($subQuery) use ($userId) {
                    $subQuery->select(DB::raw(1))
                            ->from('visitas as v')
                            ->whereColumn('v.idTickets', 't.idTickets')
                            ->where('v.idUsuario', $userId)
                            ->where('v.estado', 1)
                            ->whereExists(function($flujoQuery) {
                                $flujoQuery->select(DB::raw(1))
                                         ->from('ticketflujo as tf')
                                         ->whereColumn('tf.idTicket', 't.idTickets')
                                         ->where('tf.idestadflujo', 2);
                            });
                });
            }
        })
        ->orderBy('t.fecha_creacion', 'desc')
        ->get();

    return view("solicitud.solicitudrepuesto.create", compact('tickets'));
}

// Nuevo endpoint para buscar ticket por ID
public function getTicketInfo($ticketId)
{
    $ticket = DB::table('tickets as t')
        ->select(
            't.idTickets',
            't.numero_ticket',
            't.idClienteGeneral',
            't.idCliente', 
            't.idMarca',
            't.idModelo',
            't.serie',
            't.fechaCompra',
            't.idTienda',
            't.fallaReportada',
            'cg.descripcion as cliente_general',
            'c.nombre as cliente_nombre',
            'c.documento as cliente_documento',
            'ti.nombre as tienda_nombre',
            'm.nombre as marca_nombre',
            'mo.nombre as modelo_nombre'
        )
        ->join('clientegeneral as cg', 't.idClienteGeneral', '=', 'cg.idClienteGeneral')
        ->leftJoin('cliente as c', 't.idCliente', '=', 'c.idCliente')
        ->leftJoin('tienda as ti', 't.idTienda', '=', 'ti.idTienda')
        ->leftJoin('marca as m', 't.idMarca', '=', 'm.idMarca')
        ->leftJoin('modelo as mo', 't.idModelo', '=', 'mo.idModelo')
        ->where('t.idTickets', $ticketId)
        ->first();

    return response()->json($ticket);
}


// Endpoint para obtener tipos de repuesto por modelo
public function getTiposRepuesto($modeloId)
{
    // Primero: Buscar artículos en articulo_modelo que tengan este modelo
    $articulosIds = DB::table('articulo_modelo')
        ->where('modelo_id', $modeloId)
        ->pluck('articulo_id');

    if ($articulosIds->isEmpty()) {
        return response()->json([]);
    }

    // Segundo: Buscar en articulos esos artículos y obtener sus subcategorías
    $tiposRepuesto = DB::table('articulos as a')
        ->select(
            'sc.id as idsubcategoria',
            'sc.nombre as tipo_repuesto'
        )
        ->join('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
        ->whereIn('a.idArticulos', $articulosIds)
        ->where('a.estado', 1)
        ->groupBy('sc.id', 'sc.nombre')
        ->get();

    return response()->json($tiposRepuesto);
}

// Endpoint para obtener códigos por tipo de repuesto y modelo
public function getCodigosRepuesto($modeloId, $subcategoriaId)
{
    // Primero: Buscar artículos en articulo_modelo que tengan este modelo
    $articulosIds = DB::table('articulo_modelo')
        ->where('modelo_id', $modeloId)
        ->pluck('articulo_id');

    if ($articulosIds->isEmpty()) {
        return response()->json([]);
    }

    // Segundo: Buscar en articulos esos artículos con la subcategoría seleccionada
    $codigos = DB::table('articulos as a')
        ->select(
            'a.idArticulos',
            'a.codigo_repuesto',
            'a.nombre'
        )
        ->whereIn('a.idArticulos', $articulosIds)
        ->where('a.idsubcategoria', $subcategoriaId)
        ->where('a.estado', 1)
        ->whereNotNull('a.codigo_repuesto')
        ->where('a.codigo_repuesto', '!=', '')
        ->get();

    return response()->json($codigos);
}

    public function store(Request $request)
    {
        // Aquí va la lógica para guardar la solicitud de repuesto
        // Por ahora solo redirigimos al index
        return redirect()->route('solicitudrepuesto.index')
            ->with('success', 'Solicitud de repuesto creada exitosamente');
    }

    // Puedes agregar los demás métodos después
    public function edit($id)
    {
        // Lógica para editar
        return view("solicitud.solicitudrepuesto.edit");
    }

    public function show($id)
    {
        // Lógica para mostrar
        return view("solicitud.solicitudrepuesto.show");
    }

    public function update(Request $request, $id)
    {
        // Lógica para actualizar
        return redirect()->route('solicitudrepuesto.index')
            ->with('success', 'Solicitud de repuesto actualizada exitosamente');
    }

    public function destroy($id)
    {
        // Lógica para eliminar
        return redirect()->route('solicitudrepuesto.index')
            ->with('success', 'Solicitud de repuesto eliminada exitosamente');
    }
}