<?php

namespace App\Exports;

use App\Models\ClienteGeneral;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClientesGeneralExport implements FromCollection, WithHeadings, WithStyles, WithEvents, WithDrawings
{
    private $clientes;

    public function __construct()
    {
        $this->clientes = ClienteGeneral::all();
    }

    public function collection()
    {
        return $this->clientes->map(function ($cliente) {
            return [
                'descripcion' => $cliente->descripcion,
                'foto' => '', // Aquí irá la imagen
                'estado' => $cliente->estado ? 'Activo' : 'Inactivo',
            ];
        });
    }

    public function headings(): array
    {
        return ['Nombre', 'Foto', 'Estado'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // Encabezados
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'B71C1C']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Ajustar encabezados en la fila 2
                $sheet->getStyle('A1:C1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'B71C1C']],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                ]);

                // Ajustar columnas
                $sheet->getColumnDimension('A')->setWidth(30); // Nombre
                $sheet->getColumnDimension('B')->setWidth(20); // Foto
                $sheet->getColumnDimension('C')->setWidth(20); // Estado

                // Aplicar bordes y centrar contenido
                $sheet->getStyle('A1:C' . (count($this->clientes) + 1))->applyFromArray([
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Aplicar colores según el estado
                foreach ($this->clientes as $index => $cliente) {
                    $row = $index + 2; // Los datos empiezan en la fila 2
                    $color = $cliente->estado ? 'DFF0D8' : 'F8D7DA'; // Verde claro para activo, rojo claro para inactivo

                    $sheet->getStyle('C' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => 'solid',
                            'startColor' => ['rgb' => $color],
                        ],
                        'font' => ['bold' => true],
                    ]);
                }
            },
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $row = 2; // Las imágenes empiezan en la fila 2

        foreach ($this->clientes as $cliente) {
            if ($cliente->foto && Storage::exists('public/' . $cliente->foto)) {
                $drawing = new Drawing();
                $drawing->setName($cliente->descripcion);
                $drawing->setDescription('Foto del cliente');
                $drawing->setPath(storage_path('app/public/' . $cliente->foto)); // Ruta a la imagen
                $drawing->setHeight(40); // Tamaño de la imagen
                $drawing->setCoordinates('B' . $row); // Columna de la imagen
                $drawings[] = $drawing;
            }
            $row++;
        }

        return $drawings;
    }
}
