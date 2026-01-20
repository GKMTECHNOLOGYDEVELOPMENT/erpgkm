<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Articulo;

class HarvestDetailExport implements FromArray, WithHeadings, WithTitle, WithStyles, WithEvents
{
    protected $data;
    protected $articulo;

    public function __construct(array $data, Articulo $articulo)
    {
        $this->data = $data;
        $this->articulo = $articulo;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'N°',
            'Fecha',
            'Custodia',
            'Cliente',
            'Cantidad',
            'Responsable',
            'Observaciones'
        ];
    }

    public function title(): string
    {
        return substr($this->articulo->codigo_repuesto, 0, 31);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            count($this->data) => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Agregar información del repuesto al inicio
                $event->sheet->insertNewRowBefore(1, 4);
                
                $event->sheet->setCellValue('A1', 'REPORTE DETALLADO DE RETIROS HARVEST');
                $event->sheet->setCellValue('A2', 'Repuesto: ' . $this->articulo->nombre);
                $event->sheet->setCellValue('A3', 'Código: ' . $this->articulo->codigo_repuesto);
                $event->sheet->setCellValue('A4', 'Generado: ' . date('d/m/Y H:i'));
                
                // Combinar celdas para el título
                $event->sheet->mergeCells('A1:G1');
                $event->sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => 'center']
                ]);
            },
        ];
    }
}