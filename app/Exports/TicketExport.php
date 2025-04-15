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
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TicketExport implements
    FromCollection,
    WithMapping,
    WithStyles,
    WithEvents,
    WithCustomStartCell
{
    protected $clienteGeneral;
    protected $startDate;
    protected $endDate;

    public function __construct($clienteGeneral = null, $startDate = null, $endDate = null)
    {
        $this->clienteGeneral = $clienteGeneral;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection(): Collection
    {
        return Ticket::with([
            'modelo.categoria',
            'clientegeneral',
            'cliente',
            'ticketflujo.estadoflujo',
            'transicion_status_tickets',
            'seleccionarVisita.visita.tecnico',
            'tecnico',
            'marca'
        ])
            ->where('idTipotickets', 1)
            ->when($this->clienteGeneral, fn($q) => $q->where('idClientegeneral', $this->clienteGeneral))
            ->when($this->startDate, fn($q) => $q->whereDate('fecha_creacion', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('fecha_creacion', '<=', $this->endDate))
            ->get();
    }

    public function map($ticket): array
    {
        $serie = is_numeric($ticket->serie) ? (int) $ticket->serie : ($ticket->serie ?? 'N/A');
        $justificacion = optional($ticket->transicion_status_tickets->first())->justificacion ?? 'N/A';
        $estado = optional(optional($ticket->ticketflujo)->estadoflujo)->descripcion ?? 'N/A';
        $color = optional(optional($ticket->ticketflujo)->estadoflujo)->color ?? 'N/A';
        $tecnico = optional(optional(optional($ticket->seleccionarVisita)->visita)->tecnico)->Nombre ?? optional($ticket->tecnico)->Nombre ?? 'N/A';
        $fecha_visita = optional(optional(optional($ticket->seleccionarVisita)->visita))->fecha_programada ?? 'N/A';

        return [
            $ticket->numero_ticket ?? 'N/A',
            $ticket->fecha_creacion ?? 'N/A',
            $fecha_visita,
            optional(optional($ticket->modelo)->categoria)->nombre ?? 'N/A',
            optional($ticket->clientegeneral)->descripcion ?? 'N/A',
            optional($ticket->marca)->nombre ?? 'N/A',
            optional($ticket->modelo)->nombre ?? 'N/A',
            $serie,
            optional($ticket->cliente)->nombre ?? 'N/A',
            $ticket->direccion ?? 'N/A',
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
        $sheet->mergeCells('A1:N1');
        $sheet->setCellValue('A1', 'REPORTE TICKETS DE SMART TV');

        $sheet->getStyle('A1:N1')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'font' => ['bold' => true, 'size' => 14],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        $sheet->getStyle('A2:N2')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        foreach (range('A', 'N') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle('H')->getNumberFormat()->setFormatCode('0'); // SERIE
        $sheet->setAutoFilter('A2:N2');
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $headers = [
                    'N. TICKET',
                    'F. TICKET',
                    'F. VISITA',
                    'CATEGORÍA',
                    'GENERAL',
                    'MARCA',
                    'MODELO',
                    'SERIE',
                    'CLIENTE',
                    'DIRECCIÓN',
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

                $sheet->getStyle("A3:N{$highestRow}")->applyFromArray([
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

                // Aplica el color a toda la fila visible (A-M)
                for ($row = 3; $row <= $highestRow; $row++) {
                    $color = $sheet->getCell("N{$row}")->getValue();
                    if ($color && $color !== 'N/A') {
                        $sheet->getStyle("A{$row}:M{$row}")->applyFromArray([
                            'fill' => [
                                'fillType'   => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => ltrim($color, '#')],
                            ],
                        ]);
                    }
                }

                // Oculta la columna de color
                $sheet->getColumnDimension('N')->setVisible(false);
            },
        ];
    }
}
