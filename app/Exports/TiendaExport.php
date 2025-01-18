<?php

namespace App\Exports;

use App\Models\Tienda;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TiendaExport implements 
    FromCollection, 
    WithMapping, 
    WithHeadings, 
    ShouldAutoSize, 
    WithStyles, 
    WithColumnWidths, 
    WithCustomStartCell
{
    private const HEADERS = [
        '#', 
        'RUC', 
        'Nombre', 
        'Celular', 
        'Email', 
        'Dirección', 
        'Referencia', 
        'Cliente Asociado'
    ];

    private \Illuminate\Support\Collection $tiendas;

    public function __construct()
    {
        $this->tiendas = Tienda::with('cliente')->get();

        if ($this->tiendas->isEmpty()) {
            throw new \Exception('No hay datos disponibles para exportar.');
        }
    }

    /**
     * Devuelve la colección de datos.
     */
    public function collection(): \Illuminate\Support\Collection
    {
        return $this->tiendas;
    }

    /**
     * Mapea las columnas que se exportarán.
     */
    public function map($tienda): array
    {
        static $rowNumber = 1;

        return [
            $rowNumber++,                                // Columna de numeración
            $tienda->ruc,                                // RUC
            $tienda->nombre,                             // Nombre
            $tienda->celular ?? 'No especificado',       // Celular
            $tienda->email ?? 'No especificado',         // Email
            $tienda->direccion,                         // Dirección
            $tienda->referencia ?? 'No especificado',    // Referencia
            $tienda->cliente->nombre ?? 'Sin Asociar',   // Cliente Asociado
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
            'B' => 20,  // RUC
            'C' => 30,  // Nombre
            'D' => 15,  // Celular
            'E' => 50,  // Email
            'F' => 50,  // Dirección
            'G' => 30,  // Referencia
            'H' => 30,  // Cliente Asociado
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
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'Reporte General de Tiendas');
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
        $sheet->mergeCells('A2:H2');
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
        $sheet->getStyle('A4:H4')->applyFromArray([
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
        $sheet->getStyle('A5:H' . $sheet->getHighestRow())->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Bordes alrededor de la tabla
        $sheet->getStyle('A4:H' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Bordes negros
                ],
            ],
        ]);

        return [];
    }
}
