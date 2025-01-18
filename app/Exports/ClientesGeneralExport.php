<?php

namespace App\Exports;

use App\Models\ClienteGeneral;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ClientesGeneralExport implements 
    FromCollection, 
    WithMapping, 
    WithHeadings, 
    ShouldAutoSize, 
    WithStyles, 
    WithColumnWidths, 
    WithCustomStartCell, 
    WithDrawings
{
    private const HEADERS = ['#', 'Nombre', 'Foto', 'Estado'];
    private \Illuminate\Support\Collection $clientes;

    public function __construct()
    {
        $this->clientes = ClienteGeneral::all();

        if ($this->clientes->isEmpty()) {
            throw new \Exception('No hay datos disponibles para exportar.');
        }
    }

    public function collection(): \Illuminate\Support\Collection
    {
        return $this->clientes;
    }

    public function map($cliente): array
    {
        static $rowNumber = 1;

        return [
            $rowNumber++, // Columna de numeración
            mb_strimwidth($cliente->descripcion, 0, 50, '...'), // Descripción limitada
            $cliente->foto ? '' : 'Sin Imagen', // Si no hay foto, mostrar "Sin Imagen"
            $cliente->estado ? 'Activo' : 'Inactivo', // Estado
        ];
    }

    public function headings(): array
    {
        return self::HEADERS;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // Número
            'B' => 30,  // Nombre
            'C' => 25,  // Foto
            'D' => 15,  // Estado
        ];
    }

    public function startCell(): string
    {
        return 'A4'; // La tabla inicia en la celda A4
    }

    public function drawings()
    {
        $drawings = [];
        $rowIndex = 5; // Inicia en la fila siguiente a los encabezados

        foreach ($this->clientes as $cliente) {
            if ($cliente->foto) {
                $drawing = new Drawing();
                $drawing->setName('Foto');
                $drawing->setDescription($cliente->descripcion);
                $drawing->setPath(public_path($cliente->foto)); // Ruta de la imagen
                $drawing->setHeight(60); // Altura de la imagen
                $drawing->setCoordinates('C' . $rowIndex); // Ubica la imagen en la columna "C"
                $drawing->setOffsetX(55); // Centra horizontalmente
                $drawing->setOffsetY(15); // Centra verticalmente
                $drawings[] = $drawing;
            }
            $rowIndex++;
        }

        return $drawings;
    }

    public function styles(Worksheet $sheet): array
    {
        // Título del reporte
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'Reporte General de Clientes');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['argb' => 'FFFFFFFF'], // Texto blanco
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE3342F'], // Fondo rojo
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Fecha del reporte
        $sheet->mergeCells('A2:D2');
        $sheet->setCellValue('A2', 'Generado el: ' . now()->setTimezone('America/Lima')->format('d/m/Y H:i'));
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'italic' => true,
                'color' => ['argb' => 'FF555555'], // Texto gris
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Encabezados
        $sheet->getStyle('A4:D4')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE3342F'], // Fondo rojo
            ],
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'], // Texto blanco
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Altura de las filas
        foreach (range(5, $sheet->getHighestRow()) as $row) {
            $sheet->getRowDimension($row)->setRowHeight(70); // Ajusta la altura de las filas
        }

        // Centrar todo
        $sheet->getStyle('A5:D' . $sheet->getHighestRow())->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Bordes alrededor de la tabla
        $sheet->getStyle('A4:D' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Bordes negros
                ],
            ],
        ]);

        // Estilo para "Activo" e "Inactivo"
        foreach (range(5, $sheet->getHighestRow()) as $row) {
            $estado = $sheet->getCell("D{$row}")->getValue();
            if ($estado === 'Activo') {
                $sheet->getStyle("D{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF38C172'], // Fondo verde
                    ],
                    'font' => [
                        'color' => ['argb' => 'FFFFFFFF'], // Texto blanco
                    ],
                ]);
            } elseif ($estado === 'Inactivo') {
                $sheet->getStyle("D{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFE3342F'], // Fondo rojo
                    ],
                    'font' => [
                        'color' => ['argb' => 'FFFFFFFF'], // Texto blanco
                    ],
                ]);
            }
        }

        return [];
    }
}
