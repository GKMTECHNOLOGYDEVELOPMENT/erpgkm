<?php

namespace App\Http\Controllers\solicitud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        return view("solicitud.solicitudrepuesto.create");
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