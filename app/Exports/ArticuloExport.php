<?php

namespace App\Exports;

use App\Models\Articulo;
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

class ArticuloExport implements
    FromCollection,
    WithMapping,
    WithHeadings,
    ShouldAutoSize,
    WithStyles,
    WithColumnWidths,
    WithCustomStartCell,
    WithDrawings
{
    private const HEADERS = ['#', 'Nombre', 'Foto', 'Unidad', 'Código', 'Stock Total', 'Serie', 'Tipo de Artículo', 'Modelo', 'Estado'];
    private \Illuminate\Support\Collection $articulos;

    public function __construct()
    {
        $this->articulos = Articulo::with(['unidad', 'tipoarticulo', 'modelo'])->get();

        if ($this->articulos->isEmpty()) {
            throw new \Exception('No hay datos disponibles para exportar.');
        }
    }

    public function collection(): \Illuminate\Support\Collection
    {
        return $this->articulos;
    }

    public function map($articulo): array
    {
        static $rowNumber = 1;

        return [
            $rowNumber++, // Columna de numeración
            $articulo->nombre, // Nombre
            $articulo->foto ? '' : 'Sin Imagen', // Foto
            $articulo->unidad->nombre ?? 'N/A', // Unidad
            $articulo->codigo, // Código
            $articulo->stock_total, // Stock Total
            $articulo->serie ?: 'N/A', // Serie
            $articulo->tipoarticulo->nombre ?? 'N/A', // Tipo de Artículo
            $articulo->modelo->nombre ?? 'N/A', // Modelo
            $articulo->estado ? 'Activo' : 'Inactivo', // Estado
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
            'D' => 20,  // Unidad
            'E' => 15,  // Código
            'F' => 15,  // Stock Total
            'G' => 20,  // Serie
            'H' => 30,  // Tipo de Artículo
            'I' => 25,  // Modelo
            'J' => 15,  // Estado
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

        foreach ($this->articulos as $articulo) {
            if ($articulo->foto) {
                $drawing = new Drawing();
                $drawing->setName('Foto');
                $drawing->setDescription($articulo->nombre);
                $drawing->setPath(public_path($articulo->foto)); // Ruta de la imagen
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
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'REPORTE DE ARTÍCULOS');
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
        $sheet->mergeCells('A2:J2');
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
        $sheet->getStyle('A4:J4')->applyFromArray([
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
        $sheet->getStyle('A5:J' . $sheet->getHighestRow())->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Bordes alrededor de la tabla
        $sheet->getStyle('A4:J' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Bordes negros
                ],
            ],
        ]);

        // Estilo para "Activo" e "Inactivo"
        foreach (range(5, $sheet->getHighestRow()) as $row) {
            $estado = $sheet->getCell("J{$row}")->getValue();
            if ($estado === 'Activo') {
                $sheet->getStyle("J{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF38C172'], // Fondo verde
                    ],
                    'font' => [
                        'color' => ['argb' => 'FFFFFFFF'], // Texto blanco
                    ],
                ]);
            } elseif ($estado === 'Inactivo') {
                $sheet->getStyle("J{$row}")->applyFromArray([
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
