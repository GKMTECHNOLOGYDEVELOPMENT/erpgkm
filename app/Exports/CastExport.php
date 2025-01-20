<?php

namespace App\Exports;

use App\Models\Cast;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CastExport implements 
    FromCollection, 
    WithMapping, 
    WithHeadings, 
    ShouldAutoSize, 
    WithStyles, 
    WithColumnWidths, 
    WithCustomStartCell
{
    private const HEADERS = ['#', 'RUC', 'Nombre', 'Teléfono', 'Email', 'Departamento', 'Provincia', 'Distrito', 'Dirección', 'Estado'];
    private \Illuminate\Support\Collection $casts;

    public function __construct()
    {
        $this->casts = Cast::all();

        if ($this->casts->isEmpty()) {
            throw new \Exception('No hay datos disponibles para exportar.');
        }
    }

    /**
     * Devuelve la colección de datos.
     */
    public function collection(): \Illuminate\Support\Collection
    {
        return $this->casts;
    }

    /**
     * Mapea las columnas que se exportarán.
     */
    public function map($cast): array
    {
        static $rowNumber = 1;

        return [
            $rowNumber++,                               // Columna de numeración
            $cast->ruc,                                 // RUC
            $cast->nombre,                              // Nombre
            $cast->telefono ?? 'No especificado',       // Teléfono
            $cast->email ?? 'No especificado',          // Email
            $cast->departamento ?? 'No especificado',   // Departamento
            $cast->provincia ?? 'No especificado',      // Provincia
            $cast->distrito ?? 'No especificado',       // Distrito
            $cast->direccion ?? 'No especificado',      // Dirección
            $cast->estado === 'Activo' ? 'Activo' : 'Inactivo', // Estado
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
            'A' => 5,   // Número
            'B' => 20,  // RUC
            'C' => 25,  // Nombre
            'D' => 15,  // Teléfono
            'E' => 40,  // Email
            'F' => 20,  // Departamento
            'G' => 20,  // Provincia
            'H' => 20,  // Distrito
            'I' => 40,  // Dirección
            'J' => 15,  // Estado
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
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'Reporte General de CAST');
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

        // Estilo del encabezado
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

        // Centramos los datos de todas las columnas
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

        // Estilo para las celdas "Activo" e "Inactivo"
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
