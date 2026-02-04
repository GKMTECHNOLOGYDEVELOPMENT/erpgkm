<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KardexExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    use Exportable;

    protected $search;
    protected $startDate;
    protected $endDate;

    public function __construct($search = null, $startDate = null, $endDate = null)
    {
        $this->search = $search;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        // Subquery para CAS
        $subqueryCas = DB::table('kardex')
            ->select(
                'idArticulo',
                DB::raw('DATE(fecha) as fecha_kardex'),
                DB::raw('MAX(cas) as cas')
            )
            ->groupBy('idArticulo', DB::raw('DATE(fecha)'));

        // Consulta base
        $query = DB::table('inventario_ingresos_clientes as iic')
            ->join('articulos', 'iic.articulo_id', '=', 'articulos.idArticulos')
            ->leftJoinSub($subqueryCas, 'kardex_cas', function($join) {
                $join->on('iic.articulo_id', '=', 'kardex_cas.idArticulo')
                     ->on(DB::raw('DATE(iic.created_at)'), '=', 'kardex_cas.fecha_kardex');
            })
            ->leftJoin('tipoarticulos', 'articulos.idTipoArticulo', '=', 'tipoarticulos.idTipoArticulo')
            ->leftJoin('modelo', 'articulos.idModelo', '=', 'modelo.idModelo')
            ->leftJoin('marca', 'modelo.idMarca', '=', 'marca.idMarca')
            ->leftJoin('categoria', 'modelo.idCategoria', '=', 'categoria.idCategoria')
            ->leftJoin('subcategorias', 'articulos.idsubcategoria', '=', 'subcategorias.id')
            ->leftJoin('clientegeneral', 'iic.cliente_general_id', '=', 'clientegeneral.idClienteGeneral')
            ->select(
                'iic.id',
                'iic.articulo_id',
                'iic.cantidad', // Esta columna tiene valores positivos (entradas) y negativos (salidas)
                'iic.created_at as fecha',
                DB::raw("CASE 
                    WHEN iic.cantidad > 0 THEN 'ENTRADA'
                    WHEN iic.cantidad < 0 THEN 'SALIDA'
                    ELSE 'OTRO'
                END as tipo_movimiento"),
                DB::raw("ABS(iic.cantidad) as unidades_absolutas"),
                'tipoarticulos.nombre as tipo_articulo_nombre',
                'modelo.nombre as modelo_nombre',
                'marca.nombre as marca_nombre',
                'categoria.nombre as categoria_nombre',
                'subcategorias.nombre as subcategoria_nombre',
                DB::raw("CASE 
                    WHEN articulos.idTipoArticulo = 2 THEN articulos.codigo_repuesto 
                    ELSE articulos.nombre 
                END as nombre_producto"),
                'articulos.codigo_barras',
                'articulos.idTipoArticulo',
                'articulos.codigo_repuesto',
                'iic.numero_orden',
                'iic.codigo_solicitud',
                'iic.tipo_ingreso',
                'clientegeneral.descripcion as cliente_nombre',
                'kardex_cas.cas',
                DB::raw("CASE 
                    WHEN iic.tipo_ingreso = 'salida_provincia' THEN 'PROVINCIA'
                    WHEN iic.tipo_ingreso IN ('compra', 'ajuste', 'salida', 'entrada_proveedor') THEN 'LIMA'
                    ELSE 'SIN REGISTRO'
                END as region")
            )
            ->distinct();

        // Aplicar filtros
        if ($this->search) {
            $query->where(function($q) {
                $q->where('articulos.nombre', 'like', "%{$this->search}%")
                  ->orWhere('articulos.codigo_barras', 'like', "%{$this->search}%")
                  ->orWhere('articulos.codigo_repuesto', 'like', "%{$this->search}%")
                  ->orWhere('tipoarticulos.nombre', 'like', "%{$this->search}%")
                  ->orWhere('modelo.nombre', 'like', "%{$this->search}%")
                  ->orWhere('marca.nombre', 'like', "%{$this->search}%")
                  ->orWhere('categoria.nombre', 'like', "%{$this->search}%")
                  ->orWhere('subcategorias.nombre', 'like', "%{$this->search}%")
                  ->orWhere('iic.numero_orden', 'like', "%{$this->search}%")
                  ->orWhere('iic.codigo_solicitud', 'like', "%{$this->search}%")
                  ->orWhere('clientegeneral.descripcion', 'like', "%{$this->search}%")
                  ->orWhere('kardex_cas.cas', 'like', "%{$this->search}%")
                  ->orWhere(DB::raw("CASE 
                        WHEN iic.tipo_ingreso = 'salida_provincia' THEN 'PROVINCIA'
                        WHEN iic.tipo_ingreso IN ('compra', 'ajuste', 'salida', 'entrada_proveedor') THEN 'LIMA'
                        ELSE 'SIN REGISTRO'
                    END"), 'like', "%{$this->search}%");
            });
        }

        if ($this->startDate) {
            $query->whereDate('iic.created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('iic.created_at', '<=', $this->endDate);
        }

        return $query->orderBy('iic.created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Código de Repuesto',
            'Tipo',
            'Subcategoría',
            'Marca',
            'Modelo',
            'CAS',
            'Región',
            'Cantidad',
            'Movimiento',
            'Ticket/N° Orden'
        ];
    }

    public function map($movimiento): array
    {
        // Obtener código de repuesto o código de barras
        $codigoRepuesto = $movimiento->codigo_repuesto ?? $movimiento->codigo_barras ?? 'N/A';
        
        // La cantidad original (positiva para entradas, negativa para salidas)
        $cantidad = (int)$movimiento->cantidad;
        
        // Determinar tipo de movimiento basado en la cantidad
        $tipoMovimiento = $cantidad > 0 ? 'ENTRADA' : 'SALIDA';
        
        // Si es salida, la cantidad se muestra como positiva pero el tipo es SALIDA
        // O podemos mantener el signo negativo para distinguir visualmente
        $cantidadMostrar = abs($cantidad); // Mostrar siempre positivo

        return [
            Carbon::parse($movimiento->fecha)->format('d/m/Y'),
            $codigoRepuesto,
            $movimiento->tipo_articulo_nombre ?? 'N/A',
            $movimiento->subcategoria_nombre ?? 'N/A',
            $movimiento->marca_nombre ?? 'N/A',
            $movimiento->modelo_nombre ?? 'N/A',
            $movimiento->cas ?? 'N/A',
            $movimiento->region ?? 'N/A',
            $cantidadMostrar, // Cantidad siempre positiva
            $tipoMovimiento,  // ENTRADA o SALIDA
            $movimiento->numero_orden ?? 'N/A'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2C5282']
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // Fecha
            'B' => 25, // Código de Repuesto
            'C' => 15, // Tipo
            'D' => 15, // Subcategoría
            'E' => 15, // Marca
            'F' => 20, // Modelo
            'G' => 15, // CAS
            'H' => 15, // Región
            'I' => 12, // Cantidad
            'J' => 15, // Movimiento
            'K' => 20, // Ticket/N° Orden
        ];
    }
}