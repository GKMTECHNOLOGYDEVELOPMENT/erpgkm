<?php

namespace App\Exports;

use App\Models\Marca;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MarcasExport implements 
    FromCollection, 
    WithMapping, 
    WithHeadings, 
    ShouldAutoSize, 
    WithStyles, 
    WithColumnWidths, 
    WithCustomStartCell
{
    private const HEADERS = ['#', 'Nombre', 'Estado'];
    private \Illuminate\Support\Collection $marcas;

    public function __construct()
    {
        $this->marcas = Marca::all();

        if ($this->marcas->isEmpty()) {
            throw new \Exception('No hay datos disponibles para exportar.');
        }
    }

    /**
     * Devuelve la colección de datos.
     */
    public function collection(): \Illuminate\Support\Collection
    {
        return $this->marcas;
    }

    /**
     * Mapea las columnas que se exportarán.
     */
    public function map($marca): array
    {
        static $rowNumber = 1;

        return [
            $rowNumber++,                            // Columna de numeración
            $marca->nombre,                         // Nombre
            $marca->estado == 1 ? 'Activo' : 'Inactivo', // Estado
        ];
    }

    /**
     * Define los encabezados de las columnas.
     */
    public function headings(): array
    {
        return self::HEADERS;
    }

    /**
     * Define los anchos de las columnas.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10,  // Número
            'B' => 30,  // Nombre
            'C' => 15,  // Estado
        ];
    }

    /**
     * Define la celda de inicio de los datos.
     */
    public function startCell(): string
    {
        return 'A4'; // Inicia desde la celda A4 para dejar espacio para el título y la fecha
    }

    /**
     * Aplica estilos al archivo Excel.
     */
    public function styles(Worksheet $sheet): array
    {
        // Establecer la zona horaria de Perú
        date_default_timezone_set('America/Lima');

        // Título del reporte
        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', 'REPORTE GENERAL DE MARCAS');
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
        $sheet->mergeCells('A2:C2');
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

        // Estilo del encabezado
        $sheet->getStyle('A4:C4')->applyFromArray([
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

        // Ajustar texto y centrar datos
        $sheet->getStyle('A5:C' . $sheet->getHighestRow())->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true, // Ajustar texto
            ],
        ]);

        // Bordes alrededor de la tabla
        $sheet->getStyle('A4:C' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Bordes negros
                ],
            ],
        ]);

        // Estilo para las celdas "Activo" e "Inactivo"
        foreach (range(5, $sheet->getHighestRow()) as $row) {
            $estado = $sheet->getCell("C{$row}")->getValue();
            if ($estado === 'Activo') {
                $sheet->getStyle("C{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF38C172'], // Fondo verde
                    ],
                    'font' => [
                        'color' => ['argb' => 'FFFFFFFF'], // Texto blanco
                    ],
                ]);
            } elseif ($estado === 'Inactivo') {
                $sheet->getStyle("C{$row}")->applyFromArray([
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
