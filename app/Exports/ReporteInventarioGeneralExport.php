<?php

namespace App\Exports;

use App\Models\Articulo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReporteInventarioGeneralExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{
    protected $data;

    public function __construct()
    {
        $this->data = $this->prepareData();
    }

    public function collection()
    {
        return new Collection($this->data);
    }

    private function prepareData()
    {
        $data = [];
        
        // Obtener solo repuestos (tipo 2)
        $articulos = Articulo::with([
            'unidad',
            'tipoarticulo',
            'subcategoria',
            'modelos.marca',
            'modelos.categoria'
        ])
        ->where('idTipoArticulo', 2)
        ->where('estado', 1) // Solo activos
        ->get();

        foreach ($articulos as $articulo) {
            // Obtener modelos
            $modeloNombres = $articulo->modelos->pluck('nombre')->join(' / ');
            
            // Obtener categorías
            $categorias = $articulo->modelos->pluck('categoria.nombre')
                ->filter()
                ->unique()
                ->join(' / ') ?: 'Sin Categoría';

            // Obtener estadísticas de movimientos
            $movimientos = \DB::table('inventario_ingresos_clientes')
                ->select(
                    \DB::raw("COUNT(CASE WHEN tipo_ingreso IN ('compra', 'ajuste', 'entrada_proveedor') THEN 1 END) as total_entradas"),
                    \DB::raw("COUNT(CASE WHEN tipo_ingreso = 'salida' THEN 1 END) as total_salidas")
                )
                ->where('articulo_id', $articulo->idArticulos)
                ->first();

            $entradas = $movimientos->total_entradas ?? 0;
            $salidas = $movimientos->total_salidas ?? 0;

            // Obtener ubicaciones del artículo
            $ubicaciones = \DB::table('rack_ubicacion_articulos as rua')
                ->join('rack_ubicaciones as ru', 'rua.rack_ubicacion_id', '=', 'ru.idRackUbicacion')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->leftJoin('clientegeneral as cg', 'rua.cliente_general_id', '=', 'cg.idClienteGeneral')
                ->where('rua.articulo_id', $articulo->idArticulos)
                ->where('rua.cantidad', '>', 0)
                ->select(
                    'r.sede',
                    'r.nombre as rack_nombre',
                    'ru.nivel',
                    'ru.posicion',
                    'ru.estado_ocupacion',
                    'rua.cantidad',
                    'ru.capacidad_maxima',
                    'cg.descripcion as cliente_general',
                    'ru.codigo_unico',
                    'ru.codigo'
                )
                ->get();

            // Si no tiene ubicaciones
            if ($ubicaciones->isEmpty()) {
                $data[] = [
                    'codigo_repuesto' => $articulo->codigo_repuesto,
                    'categoria' => $categorias,
                    'modelo' => $modeloNombres ?: 'Sin Modelo',
                    'stock_total' => $articulo->stock_total,
                    'entradas' => $entradas,
                    'salidas' => $salidas,
                    'ubicacion' => 'SIN UBICACIÓN',
                    'sede' => 'N/A',
                    'rack' => 'N/A',
                    'nivel' => 'N/A',
                    'posicion' => 'N/A',
                    'estado_ubicacion' => 'N/A',
                    'cantidad_ubicacion' => 0,
                    'capacidad_maxima' => 0,
                    'porcentaje_ocupacion' => '0%',
                    'cliente_general' => 'N/A',
                    'codigo_unico' => ''
                ];
            } else {
                // Una fila por cada ubicación
                foreach ($ubicaciones as $ubicacion) {
                    $porcentajeOcupacion = ($ubicacion->capacidad_maxima > 0) 
                        ? round(($ubicacion->cantidad / $ubicacion->capacidad_maxima) * 100) . '%'
                        : '0%';

                    $ubicacionTexto = $ubicacion->codigo_unico ?: 
                        "{$ubicacion->rack_nombre} - N{$ubicacion->nivel}P{$ubicacion->posicion}";

                    $data[] = [
                        'codigo_repuesto' => $articulo->codigo_repuesto,
                        'categoria' => $categorias,
                        'modelo' => $modeloNombres ?: 'Sin Modelo',
                        'stock_total' => $articulo->stock_total,
                        'entradas' => $entradas,
                        'salidas' => $salidas,
                        'ubicacion' => $ubicacionTexto,
                        'sede' => $ubicacion->sede ?? 'N/A',
                        'rack' => $ubicacion->rack_nombre ?? 'N/A',
                        'nivel' => $ubicacion->nivel ?? 'N/A',
                        'posicion' => $ubicacion->posicion ?? 'N/A',
                        'estado_ubicacion' => $ubicacion->estado_ocupacion ?? 'N/A',
                        'cantidad_ubicacion' => $ubicacion->cantidad,
                        'capacidad_maxima' => $ubicacion->capacidad_maxima,
                        'porcentaje_ocupacion' => $porcentajeOcupacion,
                        'cliente_general' => $ubicacion->cliente_general ?? 'N/A',
                        'codigo_unico' => $ubicacion->codigo_unico ?? ''
                    ];
                }
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'CÓDIGO REPUESTO',
            'CATEGORÍA',
            'MODELO(S)',
            'STOCK TOTAL',
            'ENTRADAS',
            'SALIDAS',
            'UBICACIÓN',
            'SEDE',
            'RACK',
            'NIVEL',
            'POSICIÓN',
            'ESTADO UBICACIÓN',
            'CANTIDAD EN UBICACIÓN',
            'CAPACIDAD MÁXIMA',
            '% OCUPACIÓN',
            'CLIENTE GENERAL',
            'CÓDIGO ÚNICO'
        ];
    }

    public function map($row): array
    {
        return [
            $row['codigo_repuesto'],
            $row['categoria'],
            $row['modelo'],
            $row['stock_total'],
            $row['entradas'],
            $row['salidas'],
            $row['ubicacion'],
            $row['sede'],
            $row['rack'],
            $row['nivel'],
            $row['posicion'],
            $row['estado_ubicacion'],
            $row['cantidad_ubicacion'],
            $row['capacidad_maxima'],
            $row['porcentaje_ocupacion'],
            $row['cliente_general'],
            $row['codigo_unico']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Cabecera
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2C3E50']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ],
            // Datos
            'A2:Q' . (count($this->data) + 1) => [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'DDDDDD']
                    ]
                ]
            ],
            // Columnas numéricas
            'D2:F' . (count($this->data) + 1) => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ],
            'M2:O' . (count($this->data) + 1) => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, // Código Repuesto
            'B' => 25, // Categoría
            'C' => 30, // Modelo(s)
            'D' => 15, // Stock Total
            'E' => 12, // Entradas
            'F' => 12, // Salidas
            'G' => 25, // Ubicación
            'H' => 15, // Sede
            'I' => 20, // Rack
            'J' => 10, // Nivel
            'K' => 12, // Posición
            'L' => 18, // Estado Ubicación
            'M' => 22, // Cantidad Ubicación
            'N' => 18, // Capacidad Máxima
            'O' => 15, // % Ocupación
            'P' => 25, // Cliente General
            'Q' => 20, // Código Único
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Congelar la cabecera
                $event->sheet->freezePane('A2');
                
                // Autoajustar altura de filas
                $event->sheet->getDelegate()->getDefaultRowDimension()->setRowHeight(25);
                
                // Añadir filtros
                $event->sheet->setAutoFilter(
                    $event->sheet->calculateWorksheetDimension()
                );
            },
        ];
    }
}