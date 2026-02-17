<?php

namespace App\Exports;

use App\Models\Usuario;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class UsuarioFichaExport implements FromArray, WithStyles, WithColumnWidths, WithTitle, ShouldAutoSize, WithEvents
{
    protected $usuario;
    protected $filaActual = 1;
    protected $departamentos = [];
    protected $provincias = [];
    protected $distritos = [];

    public function __construct($usuario)
    {
        $this->usuario = $usuario;
        $this->cargarUbigeos();
    }

private function cargarUbigeos()
{
    $departamentosPath = public_path('ubigeos/departamentos.json');
    $provinciasPath    = public_path('ubigeos/provincias.json');
    $distritosPath     = public_path('ubigeos/distritos.json');

    // Departamentos (array plano)
    if (file_exists($departamentosPath)) {
        $this->departamentos = collect(
            json_decode(file_get_contents($departamentosPath), true)
        )->keyBy('id_ubigeo')->toArray();
    }

    // Provincias (agrupado por departamento)
    if (file_exists($provinciasPath)) {
        $provincias = json_decode(file_get_contents($provinciasPath), true);

        $this->provincias = collect($provincias)
            ->flatten(1)
            ->keyBy('id_ubigeo')
            ->toArray();
    }

    // Distritos (agrupado por provincia)
    if (file_exists($distritosPath)) {
        $distritos = json_decode(file_get_contents($distritosPath), true);

        $this->distritos = collect($distritos)
            ->flatten(1)
            ->keyBy('id_ubigeo')
            ->toArray();
    }
}


    public function title(): string
    {
        return 'FICHA DEL PERSONAL';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,
            'B' => 18,
            'C' => 18,
            'D' => 18,
            'E' => 18,
            'F' => 18
        ];
    }

    public function array(): array
    {
        $data = [];
        $this->filaActual = 1;

        // TÍTULO PRINCIPAL
        $data[$this->filaActual++] = ['FORMATOS Y REGISTROS'];
        $data[$this->filaActual++] = ['FICHA DE DATOS DEL PERSONAL'];
        $data[$this->filaActual++] = [''];

        // SECCIÓN 1: INFORMACIÓN GENERAL
        $data[$this->filaActual++] = ['1. INFORMACIÓN GENERAL'];
        $data[$this->filaActual++] = [''];

        // Apellidos y Nombres
        $data[$this->filaActual++] = ['APELLIDO PATERNO', 'APELLIDO MATERNO', 'NOMBRES COMPLETOS'];
        $this->filaActual++;

        // Fecha de Nacimiento (DESGLOSADA EN 3 CELDAS) y Lugar de Nacimiento
        $data[$this->filaActual++] = ['FECHA DE NACIMIENTO', '', '', 'LUGAR DE NACIMIENTO', '', ''];
        $this->filaActual++;

        // Tipo Doc, N° Doc, Edad, Sexo, Nacionalidad
        $data[$this->filaActual++] = ['TIPO DOC.', 'N° DOC.', 'EDAD', 'SEXO', 'NACIONALIDAD', ''];
        $this->filaActual++;

        // Estado Civil, Email, Teléfono
        $data[$this->filaActual++] = ['ESTADO CIVIL', 'E-MAIL', 'TELÉFONO', '', '', ''];
        $this->filaActual++;

        // Domicilio Actual
        $data[$this->filaActual++] = ['DOMICILIO ACTUAL', 'N° / Mz / Lt', 'URBANIZACIÓN', 'DEPARTAMENTO', 'PROVINCIA', 'DISTRITO'];
        $this->filaActual++;

        // Datos Bancarios
        $data[$this->filaActual++] = ['ENTIDAD BANCARIA', 'TIPO CUENTA', 'MONEDA', 'N° CUENTA', 'CCI', ''];
        $this->filaActual++;

        // Seguro y Pensiones
        $data[$this->filaActual++] = ['SEGURO SALUD', 'SISTEMA PENSIONES', 'COMPAÑÍA AFP', '', '', ''];
        $this->filaActual++;

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ============================================
                // TÍTULOS PRINCIPALES
                // ============================================
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');
                $sheet->setCellValue('A1', 'FORMATOS Y REGISTROS');
                $sheet->setCellValue('A2', 'FICHA DE DATOS DEL PERSONAL');

                // Título de sección
                $sheet->mergeCells('A4:F4');
                $sheet->setCellValue('A4', '1. INFORMACIÓN GENERAL');

                // ============================================
                // APELLIDOS Y NOMBRES
                // ============================================
                $sheet->mergeCells('A6:B6');
                $sheet->mergeCells('C6:D6');
                $sheet->mergeCells('E6:F6');
                $sheet->setCellValue('A6', 'APELLIDO PATERNO');
                $sheet->setCellValue('C6', 'APELLIDO MATERNO');
                $sheet->setCellValue('E6', 'NOMBRES COMPLETOS');

                // Datos de apellidos y nombres
                $sheet->mergeCells('A7:B7');
                $sheet->mergeCells('C7:D7');
                $sheet->mergeCells('E7:F7');
                $sheet->setCellValue('A7', strtoupper($this->usuario->apellidoPaterno ?? ''));
                $sheet->setCellValue('C7', strtoupper($this->usuario->apellidoMaterno ?? ''));
                $sheet->setCellValue('E7', strtoupper($this->usuario->Nombre ?? ''));

                // ============================================
                // FECHA DE NACIMIENTO - DESGLOSADA EN 3 CELDAS
                // ============================================
                $fechaNac = $this->usuario->fechaNacimiento ? Carbon::parse($this->usuario->fechaNacimiento) : null;

                // Títulos
                $sheet->mergeCells('A8:C8');
                $sheet->mergeCells('D8:F8');
                $sheet->setCellValue('A8', 'FECHA DE NACIMIENTO');
                $sheet->setCellValue('D8', 'LUGAR DE NACIMIENTO');

                // Subtítulos
                $sheet->setCellValue('A9', 'DÍA');
                $sheet->setCellValue('B9', 'MES');
                $sheet->setCellValue('C9', 'AÑO');
                $sheet->mergeCells('D9:F9');
                $sheet->setCellValue('D9', 'DEPARTAMENTO - PROVINCIA - DISTRITO');

                // DATOS: DÍA, MES, AÑO
                $sheet->setCellValue('A10', $fechaNac ? $fechaNac->format('d') : '');
                $sheet->setCellValue('B10', $fechaNac ? $fechaNac->format('m') : '');
                $sheet->setCellValue('C10', $fechaNac ? $fechaNac->format('Y') : '');

                // Lugar de nacimiento con NOMBRES reales
                $depNac = $this->getNombreDepartamento($this->usuario->fichaGeneral->nacimientoDepartamento ?? '');
                $provNac = $this->getNombreProvincia($this->usuario->fichaGeneral->nacimientoProvincia ?? '');
                $distNac = $this->getNombreDistrito($this->usuario->fichaGeneral->nacimientoDistrito ?? '');

                $lugarNacimiento = trim($depNac . ' - ' . $provNac . ' - ' . $distNac, ' -');
                $sheet->mergeCells('D10:F10');
                $sheet->setCellValue('D10', strtoupper($lugarNacimiento));

                // ============================================
                // TIPO DOC, N° DOC, EDAD, SEXO, NACIONALIDAD
                // ============================================
                $sheet->mergeCells('A11:B11');
                $sheet->mergeCells('C11:D11');
                $sheet->setCellValue('A11', 'TIPO DOC.');
                $sheet->setCellValue('C11', 'N° DOC.');
                $sheet->setCellValue('E11', 'EDAD');
                $sheet->setCellValue('F11', 'SEXO / NAC.');

                // Datos
                $sheet->mergeCells('A12:B12');
                $sheet->mergeCells('C12:D12');
                $sheet->setCellValue('A12', strtoupper($this->usuario->tipoDocumento->nombre ?? ''));
                $sheet->setCellValue('C12', $this->usuario->documento ?? '');
                $sheet->setCellValue('E12', $fechaNac ? $fechaNac->age . ' años' : '');
                $sheet->setCellValue('F12', strtoupper(($this->usuario->sexo->nombre ?? '') . ' / ' . ($this->usuario->nacionalidad ?? 'Peruana')));

                // ============================================
                // ESTADO CIVIL, EMAIL, TELÉFONO
                // ============================================
                $sheet->mergeCells('A13:B13');
                $sheet->mergeCells('C13:D13');
                $sheet->mergeCells('E13:F13');
                $sheet->setCellValue('A13', 'ESTADO CIVIL');
                $sheet->setCellValue('C13', 'E-MAIL');
                $sheet->setCellValue('E13', 'TELÉFONO');

                // Datos
                $sheet->mergeCells('A14:B14');
                $sheet->mergeCells('C14:D14');
                $sheet->mergeCells('E14:F14');
                $sheet->setCellValue('A14', strtoupper($this->getEstadoCivilTexto($this->usuario->estadocivil)));
                $sheet->setCellValue('C14', strtolower($this->usuario->correo ?? ''));
                $sheet->setCellValue('E14', $this->usuario->telefono ?? '');

                // ============================================
                // DOMICILIO
                // ============================================
                $sheet->mergeCells('A15:B15');
                $sheet->mergeCells('C15:D15');
                $sheet->mergeCells('E15:F15');
                $sheet->setCellValue('A15', 'DOMICILIO');
                $sheet->setCellValue('C15', 'N° / Mz / Lt');
                $sheet->setCellValue('E15', 'URBANIZACIÓN');

                // Datos domicilio
                $sheet->mergeCells('A16:B16');
                $sheet->mergeCells('C16:D16');
                $sheet->mergeCells('E16:F16');
                $sheet->setCellValue('A16', strtoupper($this->usuario->direccion ?? ''));
                $sheet->setCellValue('C16', '');
                $sheet->setCellValue('E16', '');

                // ============================================
                // DEPARTAMENTO, PROVINCIA, DISTRITO (DOMICILIO) - CON NOMBRES REALES
                // ============================================
                $sheet->mergeCells('A17:B17');
                $sheet->mergeCells('C17:D17');
                $sheet->mergeCells('E17:F17');
                $sheet->setCellValue('A17', 'DEPARTAMENTO');
                $sheet->setCellValue('C17', 'PROVINCIA');
                $sheet->setCellValue('E17', 'DISTRITO');

                // Datos ubicación domicilio con NOMBRES reales
                $depDomicilio = $this->getNombreDepartamento($this->usuario->departamento);
                $provDomicilio = $this->getNombreProvincia($this->usuario->provincia);
                $distDomicilio = $this->getNombreDistrito($this->usuario->distrito);

                $sheet->mergeCells('A18:B18');
                $sheet->mergeCells('C18:D18');
                $sheet->mergeCells('E18:F18');
                $sheet->setCellValue('A18', strtoupper($depDomicilio));
                $sheet->setCellValue('C18', strtoupper($provDomicilio));
                $sheet->setCellValue('E18', strtoupper($distDomicilio));

                // ============================================
                // DATOS BANCARIOS
                // ============================================
                $sheet->mergeCells('A19:B19');
                $sheet->mergeCells('C19:D19');
                $sheet->mergeCells('E19:F19');
                $sheet->setCellValue('A19', 'ENTIDAD BANCARIA');
                $sheet->setCellValue('C19', 'TIPO CUENTA');
                $sheet->setCellValue('E19', 'MONEDA');

                // Datos bancarios 1
                $sheet->mergeCells('A20:B20');
                $sheet->mergeCells('C20:D20');
                $sheet->mergeCells('E20:F20');
                $sheet->setCellValue('A20', strtoupper($this->usuario->fichaGeneral->entidad_bancaria ?? ''));
                $sheet->setCellValue('C20', strtoupper($this->usuario->fichaGeneral->tipo_cuenta ?? ''));
                $sheet->setCellValue('E20', strtoupper($this->usuario->fichaGeneral->moneda ?? ''));

                // N° CUENTA y CCI
                $sheet->mergeCells('A21:B21');
                $sheet->mergeCells('C21:D21');
                $sheet->setCellValue('A21', 'N° CUENTA');
                $sheet->setCellValue('C21', 'CCI');

                // Datos bancarios 2
                $sheet->mergeCells('A22:B22');
                $sheet->mergeCells('C22:D22');
                $sheet->setCellValue('A22', $this->usuario->fichaGeneral->numero_cuenta ?? '');
                $sheet->setCellValue('C22', $this->usuario->fichaGeneral->numero_cci ?? '');

                // ============================================
                // SEGURO Y PENSIÓN
                // ============================================
                $sheet->mergeCells('A23:B23');
                $sheet->mergeCells('C23:D23');
                $sheet->mergeCells('E23:F23');
                $sheet->setCellValue('A23', 'SEGURO SALUD');
                $sheet->setCellValue('C23', 'SISTEMA PENSIONES');
                $sheet->setCellValue('E23', 'COMPAÑÍA AFP');

                // Datos seguro y pensión
                $sheet->mergeCells('A24:B24');
                $sheet->mergeCells('C24:D24');
                $sheet->mergeCells('E24:F24');
                $sheet->setCellValue('A24', $this->getFormatoSeguro($this->usuario->fichaGeneral->tipo_seguro ?? ''));
                $sheet->setCellValue('C24', $this->getFormatoPension($this->usuario->fichaGeneral->sistema_pensiones ?? ''));
                $sheet->setCellValue('E24', $this->getFormatoAFP($this->usuario->fichaGeneral->compania_afp ?? ''));

                // ============================================
                // ESTILOS - CENTRAR TODO
                // ============================================
                $lastRow = $sheet->getHighestRow();

                // Estilos generales - AHORA CENTRADO HORIZONTALMENTE
                $sheet->getStyle('A1:F' . $lastRow)->getFont()->setName('Arial')->setSize(10);
                $sheet->getStyle('A1:F' . $lastRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A1:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1:F' . $lastRow)->getAlignment()->setWrapText(true);

                // Títulos principales
                $sheet->getStyle('A1:A2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                ]);

                // Título de sección
                $sheet->getStyle('A4')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']],
                ]);

                // Títulos de campos (fondo gris)
                $filasTitulos = [6, 8, 9, 11, 13, 15, 17, 19, 21, 23];
                foreach ($filasTitulos as $fila) {
                    $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']],
                    ]);
                }

                // Bordes para toda la hoja
                $sheet->getStyle('A1:F' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                // Configurar página
                $sheet->getPageSetup()
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4)
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
            },
        ];
    }

    // FUNCIONES AUXILIARES
    private function getEstadoCivilTexto($estado)
    {
        $estados = ['1' => 'Soltero', '2' => 'Casado', '3' => 'Divorciado', '4' => 'Viudo'];
        return $estados[$estado] ?? '';
    }

    private function getFormatoSeguro($seguro)
    {
        return "SIS " . ($seguro == 'SIS' ? '[X]' : '[ ]') . "   ESSALUD " . ($seguro == 'ESSALUD' ? '[X]' : '[ ]') . "   EPS " . ($seguro == 'EPS' ? '[X]' : '[ ]');
    }

    private function getFormatoPension($pension)
    {
        return "ONP " . ($pension == 'ONP' ? '[X]' : '[ ]') . "   AFP " . ($pension == 'AFP' ? '[X]' : '[ ]') . "   N/A " . ($pension == 'N/A' ? '[X]' : '[ ]');
    }

    private function getFormatoAFP($afp)
    {
        return "Integra " . ($afp == 'Integra' ? '[X]' : '[ ]') . "   Horizonte " . ($afp == 'Horizonte' ? '[X]' : '[ ]') . "   Profuturo " . ($afp == 'Profuturo' ? '[X]' : '[ ]') . "   Prima " . ($afp == 'Prima' ? '[X]' : '[ ]');
    }

    // FUNCIONES PARA OBTENER NOMBRES DE UBIGEO DESDE LOS JSON
 private function getNombreDepartamento($codigo)
{
    return $this->departamentos[$codigo]['nombre_ubigeo'] ?? '';
}

private function getNombreProvincia($codigo)
{
    return $this->provincias[$codigo]['nombre_ubigeo'] ?? '';
}

private function getNombreDistrito($codigo)
{
    return $this->distritos[$codigo]['nombre_ubigeo'] ?? '';
}

}
