<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketExport implements
    FromQuery,
    WithMapping,
    WithStyles,
    WithEvents,
    WithChunkReading,
    WithCustomStartCell
{
    protected $marca;
    protected $startDate;
    protected $endDate;
    protected $chunkSize;

    public function __construct($marca = null, $startDate = null, $endDate = null, $chunkSize = 500)
    {
        $this->marca     = $marca;
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
        $this->chunkSize = $chunkSize;
    }

    public function query()
    {
        $table = 'tickets';
        $availableColumns = Schema::getColumnListing($table);

        // Columnas a exportar de tickets y relaciones
        $columns = [
            'tickets.numero_ticket',
            'tickets.fecha_creacion',
            'tickets.fecha_visita',
            'tickets.solucion',
            'tickets.serie',
            'tickets.direccion',
            'modelo.nombre as modelo_nombre',
            'modelo.idCategoria as modelo_idCategoria',
            'categoria.nombre as categoria_nombre',
            'clientegeneral.descripcion as clientegeneral_descripcion',
            'cliente.nombre as cliente_nombre',
            'estado_flujo.descripcion as estado_flujo_descripcion',
            'estado_flujo.color as estado_flujo_color',
            'usuarios.Nombre as tecnico_nombre'
        ];

        // Si alguna columna de tickets no existe, se reemplaza por 'N/A'
        foreach ($columns as $index => $column) {
            // Solo se valida si la columna pertenece a tickets
            if (strpos($column, 'tickets.') === 0) {
                $colName = explode('.', $column)[1];
                if (!in_array($colName, $availableColumns)) {
                    $columns[$index] = DB::raw("'N/A' as $colName");
                }
            }
        }

        $query = Ticket::select($columns)
            ->leftJoin('modelo', 'tickets.idModelo', '=', 'modelo.idModelo')
            ->leftJoin('categoria', 'modelo.idCategoria', '=', 'categoria.idCategoria')
            ->leftJoin('clientegeneral', 'tickets.idClienteGeneral', '=', 'clientegeneral.idClienteGeneral')
            ->leftJoin('cliente', 'tickets.idCliente', '=', 'cliente.idCliente')
            ->leftJoin('estado_flujo', 'tickets.idEstadflujo', '=', 'estado_flujo.idEstadflujo')
            ->leftJoin('usuarios', 'tickets.idTecnico', '=', 'usuarios.idUsuario')
            ->when($this->marca, function ($q) {
                $q->where('modelo.idMarca', $this->marca);
            })
            ->when($this->startDate, fn($q) => $q->whereDate('tickets.fecha_creacion', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('tickets.fecha_creacion', '<=', $this->endDate));

        Log::info('Consulta SQL:', [
            'query'    => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        return $query;
    }

    /**
     * Mapeo de cada fila para el Excel.
     * Convertimos la "serie" a entero para quitar decimales.
     */
    public function map($ticket): array
    {
        $serie = $ticket->serie ?? 'N/A';
        if (is_numeric($serie)) {
            $serie = (int) $serie;
        }
    
        return [
            $ticket->numero_ticket              ?? 'N/A',
            $ticket->fecha_creacion             ?? 'N/A',
            $ticket->fecha_visita               ?? 'N/A',
            $ticket->categoria_nombre           ?? 'N/A', // modelo->categoria->nombre
            $ticket->clientegeneral_descripcion ?? 'N/A',
            $ticket->modelo_nombre              ?? 'N/A',
            $serie, // Serie convertida a entero
            $ticket->cliente_nombre             ?? 'N/A',
            $ticket->direccion                  ?? 'N/A',
            $ticket->solucion                   ?? 'N/A',
            $ticket->estado_flujo_descripcion   ?? 'N/A',
            $ticket->tecnico_nombre             ?? 'N/A',
            $ticket->estado_flujo_color         ?? 'N/A'  // Columna extra con el color
        ];
    }
    


    /**
     * Inicia la escritura en la celda A3, dejando la fila 1 para el título y la fila 2 para los encabezados.
     */
    public function startCell(): string
    {
        return 'A3';
    }

    /**
     * Tamaño del chunk para procesar la consulta en partes.
     */
    public function chunkSize(): int
    {
        return $this->chunkSize;
    }

    /**
     * Aplicamos estilos globales a la hoja.
     */
    public function styles(Worksheet $sheet)
    {
        // Título (fila 1)
        $sheet->mergeCells('A1:L1');
        $sheet->setCellValue('A1', 'REPORTE TICKETS DE SMART TV');
        $sheet->getStyle('A1:L1')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Encabezados (fila 2)
        $sheet->getStyle('A2:L2')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Datos (fila 3 en adelante)

        $highestRow = $sheet->getHighestRow();
        if ($highestRow >= 4) {
            $sheet->getStyle("A3:L{$highestRow}")->applyFromArray([
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
        }


        // Autoajuste de columnas (A a L)
        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Forzar la columna G (serie) a formato numérico sin decimales ('0')
        $sheet->getStyle('G')->getNumberFormat()->setFormatCode('0');

        // Autofiltro en la fila 2
        $sheet->setAutoFilter('A2:L2');
    }

    /**
     * Eventos para escribir encabezados y aplicar colores según la columna L.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
    
                // Encabezados en la fila 2 (solo 12 columnas visibles)
                $headers = [
                    'N. TICKET',
                    'F. TICKET',
                    'F. VISITA',
                    'CATEGORÍA',
                    'GENERAL',
                    'MODELO',
                    'SERIE',
                    'CLIENTE',
                    'DIRECCIÓN',
                    'SOLUCIÓN',
                    'ESTADO FLUJO',
                    'TÉCNICO'
                ];
    
                foreach ($headers as $index => $header) {
                    $cell = chr(65 + $index) . '2'; // Columnas A-L
                    $sheet->setCellValue($cell, $header);
                }
    
                // Opcional: Si deseas agregar encabezado para la columna extra (M) puedes hacerlo
                // $sheet->setCellValue('M2', 'Color');
    
                // Ocultar la columna M (la que contiene el color)
                $sheet->getColumnDimension('M')->setVisible(false);
    
                // Recorre las filas de datos (desde la fila 4)
                $highestRow = $sheet->getHighestRow();
                for ($row = 3; $row <= $highestRow; $row++) {
                    // Obtiene el color de la columna M
                    $color = $sheet->getCell('M' . $row)->getValue();
                    if ($color && $color !== 'N/A') {
                        $sheet->getStyle("A{$row}:L{$row}")->applyFromArray([
                            'fill' => [
                                'fillType'   => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => ltrim($color, '#')],
                            ],
                        ]);
                    }
                }
            },
        ];
    }
    
}
