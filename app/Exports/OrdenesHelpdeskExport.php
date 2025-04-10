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

class OrdenesHelpdeskExport implements
    FromCollection,
    WithMapping,
    WithStyles,
    WithEvents,
    WithCustomStartCell
{
    public function collection(): Collection
    {
        return Ticket::with([
            'modelo.categoria',
            'clientegeneral',
            'cliente',
            'tienda',
            'tiposervicio',
            'ticketflujo.estadoFlujo',
            'transicion_status_tickets',
            'seleccionarVisita.visita.tecnico',
            'tecnico'
        ])
            ->where('idTipotickets', 2)
            ->get();
    }

    public function map($ticket): array
    {
        $justificacion = optional($ticket->transicion_status_tickets->first())->justificacion ?? 'N/A';
        $estado = optional(optional($ticket->ticketflujo)->estadoFlujo)->descripcion ?? 'N/A';
        $color = optional(optional($ticket->ticketflujo)->estadoFlujo)->color ?? 'N/A';
        $tecnico = optional(optional(optional($ticket->seleccionarVisita)->visita)->tecnico)->Nombre ?? optional($ticket->tecnico)->Nombre ?? 'N/A';
        $fecha_visita = optional(optional(optional($ticket->seleccionarVisita)->visita))->fecha_programada ?? 'N/A';

        return [
            $ticket->idTickets ?? 'N/A', // A - OT
            $ticket->numero_ticket ?? 'N/A', // B
            $ticket->fecha_creacion ?? 'N/A', // C
            $fecha_visita, // D
            optional($ticket->cliente)->nombre ?? 'N/A', // E
            optional($ticket->tienda)->nombre ?? 'N/A', // F
            optional($ticket->tiposervicio)->nombre ?? 'N/A', // G
            $justificacion, // H
            $estado, // I
            $tecnico, // J
            $color // K (oculta)
        ];
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue('A1', 'REPORTE TICKETS DE HELPDESK');
        $sheet->getStyle('A1:K1')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'font' => ['bold' => true, 'size' => 14],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        $sheet->getStyle('A2:K2')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle('A')->getNumberFormat()->setFormatCode('0'); // OT numérico
        $sheet->setAutoFilter('A2:K2');
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $headers = [
                    'OT', 'N. TICKET', 'F. TICKET', 'F. VISITA',
                    'CLIENTE', 'TIENDA', 'TIPO SERVICIO', 'SOLUCIÓN',
                    'ESTADO FLUJO', 'TÉCNICO', 'COLOR'
                ];

                foreach ($headers as $index => $header) {
                    $cell = chr(65 + $index) . '2';
                    $sheet->setCellValue($cell, $header);
                }

                $max = $sheet->getHighestRow();
                $sheet->getStyle("A3:K{$max}")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                for ($i = 3; $i <= $max; $i++) {
                    $color = $sheet->getCell("K{$i}")->getValue(); // K = COLOR
                    if ($color && $color !== 'N/A') {
                        $sheet->getStyle("A{$i}:J{$i}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => ltrim($color, '#')],
                            ],
                        ]);
                    }
                }

                $sheet->getColumnDimension('K')->setVisible(false); // Ocultar columna color
            }
        ];
    }
}
