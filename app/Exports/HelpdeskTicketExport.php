<?php

namespace App\Exports;

use App\Models\Ticket;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class HelpdeskTicketExport implements
    FromCollection,
    WithMapping,
    WithStyles,
    WithEvents,
    WithCustomStartCell
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection(): Collection
    {
        return Ticket::with([
            'clientegeneral',
            'cliente',
            'ticketflujo.estadoflujo',
            'transicion_status_tickets',
            'visitas',
            'tecnico',
            'tienda',
            'tiposervicio'
        ])
            ->where('idTipotickets', 2)
            ->when($this->startDate, fn($q) => $q->whereDate('fecha_creacion', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('fecha_creacion', '<=', $this->endDate))
            ->get();
    }

    public function map($ticket): array
    {
        $justificacion = optional($ticket->transicion_status_tickets->first())->justificacion ?? 'N/A';
        $estado = optional(optional($ticket->ticketflujo)->estadoflujo)->descripcion ?? 'N/A';
        $color = optional(optional($ticket->ticketflujo)->estadoflujo)->color ?? 'N/A';

        $nombre = optional(optional(optional($ticket->seleccionarVisita)->visita)->tecnico)->Nombre
            ?? optional($ticket->usuario)->Nombre;

        $apePat = optional(optional(optional($ticket->seleccionarVisita)->visita)->tecnico)->apellidoPaterno
            ?? optional($ticket->usuario)->apellidoPaterno;

        $apeMat = optional(optional(optional($ticket->seleccionarVisita)->visita)->tecnico)->apellidoMaterno
            ?? optional($ticket->usuario)->apellidoMaterno;

        $tecnico = $nombre ? trim("$nombre $apePat $apeMat") : 'N/A';

        $fecha_visita = optional(optional(optional($ticket->seleccionarVisita)->visita))->fecha_programada ?? 'N/A';

        return [
            $ticket->idTickets ?? 'N/A', // OT
            $ticket->numero_ticket ?? 'N/A',
            $ticket->fecha_creacion ?? 'N/A',
            $fecha_visita,
            optional($ticket->cliente)->nombre ?? 'N/A',
            optional($ticket->tienda)->nombre ?? 'N/A',
            optional($ticket->tiposervicio)->nombre ?? 'N/A',
            $justificacion,
            $estado,
            $tecnico,
            $color
        ];
    }


    public function startCell(): string
    {
        return 'A3';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue('A1', 'REPORTE TICKETS HELP DESK');

        // Estilo del título
        $sheet->getStyle('A1:K1')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER
            ],
            'font' => ['bold' => true, 'size' => 14],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => '000000']
                ]
            ],
        ]);

        // Estilo de los encabezados
        $sheet->getStyle('A2:K2')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true
            ],
            'font' => ['bold' => true],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => '000000']
                ]
            ],
        ]);

        // Autoajuste columnas A-L
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Filtro en encabezados
        $sheet->setAutoFilter('A2:K2');

        // Aplicar estilo general a todas las celdas desde A3
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("A3:K{$highestRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => '000000']
                ]
            ],
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $headers = [
                    'OT',
                    'N. TICKET',
                    'F. TICKET',
                    'F. VISITA',
                    'CLIENTE',
                    'TIENDA',
                    'TIPO SERVICIO',
                    'SOLUCIÓN',
                    'ESTADO FLUJO',
                    'TÉCNICO',
                    'COLOR'
                ];



                foreach ($headers as $index => $header) {
                    $cell = chr(65 + $index) . '2';
                    $sheet->setCellValue($cell, $header);
                }

                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("A3:I{$highestRow}")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ]);

                // Aplicar color de fondo fila por fila basado en la columna "COLOR" (K)
                for ($row = 3; $row <= $highestRow; $row++) {
                    $color = $sheet->getCell("K{$row}")->getValue(); // columna 11 (COLOR)
                    if ($color && $color !== 'N/A') {
                        $sheet->getStyle("A{$row}:J{$row}")->applyFromArray([
                            'fill' => [
                                'fillType'   => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => ltrim($color, '#')]
                            ]
                        ]);
                    }
                }

                // Ocultar la columna de color
                $sheet->getColumnDimension('K')->setVisible(false);
            },
        ];
    }
}
