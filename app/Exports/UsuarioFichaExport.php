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
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

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
            'D' => 25,
            'E' => 18,
            'F' => 18,
            'G' => 18
        ];
    }

    public function array(): array
    {
        $data = [];
        $this->filaActual = 1;

        // TÍTULO PRINCIPAL
        $data[$this->filaActual++] = ['FORMATOS Y REGISTROS', '', '', '', '', '', ''];
        $data[$this->filaActual++] = ['FICHA DE DATOS DEL PERSONAL', '', '', '', '', '', ''];
        $data[$this->filaActual++] = ['', '', '', '', '', '', ''];

        // SECCIÓN 1: INFORMACIÓN GENERAL - SIN ESPACIOS EXTRA
        $data[$this->filaActual++] = ['1. INFORMACIÓN GENERAL', '', '', '', '', '', ''];

        // Apellidos y Nombres
        $data[$this->filaActual++] = ['APELLIDO PATERNO', 'APELLIDO MATERNO', 'NOMBRES COMPLETOS', '', '', '', ''];

        // Fecha de Nacimiento y Lugar
        $data[$this->filaActual++] = ['FECHA DE NACIMIENTO', '', '', 'LUGAR DE NACIMIENTO', '', '', ''];

        // Tipo Doc, N° Doc, Edad, Sexo, Nacionalidad
        $data[$this->filaActual++] = ['TIPO DOC.', 'N° DOC.', 'EDAD', 'SEXO', 'NACIONALIDAD', '', ''];

        // Estado Civil, Email, Teléfono
        $data[$this->filaActual++] = ['ESTADO CIVIL', 'E-MAIL', 'TELÉFONO', '', '', '', ''];

        // Domicilio Actual
        $data[$this->filaActual++] = ['DOMICILIO ACTUAL', 'N° / Mz / Lt', 'URBANIZACIÓN', 'DEPARTAMENTO', 'PROVINCIA', 'DISTRITO', ''];

        // Datos Bancarios
        $data[$this->filaActual++] = ['ENTIDAD BANCARIA', '', 'TIPO CUENTA', 'MONEDA', 'N° CUENTA', 'CCI', ''];

        // Seguro y Pensiones
        $data[$this->filaActual++] = ['SEGURO SALUD', 'SISTEMA PENSIONES', 'COMPAÑÍA AFP', '', '', '', ''];

        // SECCIÓN 2: INFORMACIÓN ACADÉMICA
        $data[$this->filaActual++] = ['2. INFORMACIÓN ACADÉMICA (Consignar los estudios realizados)', '', '', '', '', '', ''];

        // Encabezados de la tabla
        $data[$this->filaActual++] = ['NIVEL', 'TERMINÓ', 'CENTRO DE ESTUDIOS', 'ESPECIALIDAD', 'NIVEL / GRADO ACADÉMICO', 'F. INICIO', 'F. FIN'];

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
                $sheet->mergeCells('A1:G1');
                $sheet->mergeCells('A2:G2');
                $sheet->setCellValue('A1', 'FORMATOS Y REGISTROS');
                $sheet->setCellValue('A2', 'FICHA DE DATOS DEL PERSONAL');

                // Título de sección 1
                $sheet->mergeCells('A4:G4');
                $sheet->setCellValue('A4', '1. INFORMACIÓN GENERAL');

                // ============================================
                // APELLIDOS Y NOMBRES (Fila 5: Títulos, Fila 6: Datos)
                // ============================================
                $sheet->mergeCells('A5:B5');
                $sheet->mergeCells('C5:E5');
                $sheet->mergeCells('F5:G5');
                $sheet->setCellValue('A5', 'APELLIDO PATERNO');
                $sheet->setCellValue('C5', 'APELLIDO MATERNO');
                $sheet->setCellValue('F5', 'NOMBRES COMPLETOS');

                // Datos de apellidos y nombres
                $sheet->mergeCells('A6:B6');
                $sheet->mergeCells('C6:E6');
                $sheet->mergeCells('F6:G6');
                $sheet->setCellValue('A6', strtoupper($this->usuario->apellidoPaterno ?? ''));
                $sheet->setCellValue('C6', strtoupper($this->usuario->apellidoMaterno ?? ''));
                $sheet->setCellValue('F6', strtoupper($this->usuario->Nombre ?? ''));

                // ============================================
                // FECHA DE NACIMIENTO (Fila 7: Títulos, Fila 8: Subtítulos, Fila 9: Datos)
                // ============================================
                $fechaNac = $this->usuario->fechaNacimiento ? Carbon::parse($this->usuario->fechaNacimiento) : null;

                // Títulos
                $sheet->mergeCells('A7:C7');
                $sheet->mergeCells('D7:G7');
                $sheet->setCellValue('A7', 'FECHA DE NACIMIENTO');
                $sheet->setCellValue('D7', 'LUGAR DE NACIMIENTO');

                // Subtítulos
                $sheet->setCellValue('A8', 'DÍA');
                $sheet->setCellValue('B8', 'MES');
                $sheet->setCellValue('C8', 'AÑO');
                $sheet->mergeCells('D8:G8');
                $sheet->setCellValue('D8', 'DEPARTAMENTO - PROVINCIA - DISTRITO');

                // DATOS: DÍA, MES, AÑO
                $sheet->setCellValue('A9', $fechaNac ? $fechaNac->format('d') : '');
                $sheet->setCellValue('B9', $fechaNac ? $fechaNac->format('m') : '');
                $sheet->setCellValue('C9', $fechaNac ? $fechaNac->format('Y') : '');

                // Lugar de nacimiento
                $depNac = $this->getNombreDepartamento($this->usuario->fichaGeneral->nacimientoDepartamento ?? '');
                $provNac = $this->getNombreProvincia($this->usuario->fichaGeneral->nacimientoProvincia ?? '');
                $distNac = $this->getNombreDistrito($this->usuario->fichaGeneral->nacimientoDistrito ?? '');

                $lugarNacimiento = trim($depNac . ' - ' . $provNac . ' - ' . $distNac, ' -');
                $sheet->mergeCells('D9:G9');
                $sheet->setCellValue('D9', strtoupper($lugarNacimiento));

                // ============================================
                // TIPO DOC, N° DOC, EDAD, SEXO, NACIONALIDAD (Fila 10: Títulos, Fila 11: Datos)
                // ============================================
                $sheet->mergeCells('A10:B10');
                $sheet->mergeCells('C10:D10');
                $sheet->setCellValue('A10', 'TIPO DOC.');
                $sheet->setCellValue('C10', 'N° DOC.');
                $sheet->setCellValue('E10', 'EDAD');
                $sheet->setCellValue('F10', 'SEXO');
                $sheet->setCellValue('G10', 'NAC.');

                // Datos
                $sheet->mergeCells('A11:B11');
                $sheet->mergeCells('C11:D11');
                $sheet->setCellValue('A11', strtoupper($this->usuario->tipoDocumento->nombre ?? ''));
                $sheet->setCellValue('C11', $this->usuario->documento ?? '');
                $sheet->setCellValue('E11', $fechaNac ? $fechaNac->age . ' años' : '');
                $sheet->setCellValue('F11', strtoupper($this->usuario->sexo->nombre ?? ''));
                $sheet->setCellValue('G11', strtoupper($this->usuario->nacionalidad ?? 'Peruana'));

                // ============================================
                // ESTADO CIVIL, EMAIL, TELÉFONO (Fila 12: Títulos, Fila 13: Datos)
                // ============================================
                $sheet->mergeCells('A12:B12');
                $sheet->mergeCells('C12:D12');
                $sheet->mergeCells('E12:G12');
                $sheet->setCellValue('A12', 'ESTADO CIVIL');
                $sheet->setCellValue('C12', 'E-MAIL');
                $sheet->setCellValue('E12', 'TELÉFONO');

                // Datos
                $sheet->mergeCells('A13:B13');
                $sheet->mergeCells('C13:D13');
                $sheet->mergeCells('E13:G13');
                $sheet->setCellValue('A13', strtoupper($this->getEstadoCivilTexto($this->usuario->estadocivil)));
                $sheet->setCellValue('C13', strtolower($this->usuario->correo ?? ''));
                $sheet->setCellValue('E13', $this->usuario->telefono ?? '');

                // ============================================
                // DOMICILIO (Fila 14: Títulos, Fila 15: Datos)
                // ============================================
                $sheet->mergeCells('A14:B14');
                $sheet->mergeCells('C14:D14');
                $sheet->mergeCells('E14:G14');
                $sheet->setCellValue('A14', 'DOMICILIO');
                $sheet->setCellValue('C14', 'N° / Mz / Lt');
                $sheet->setCellValue('E14', 'URBANIZACIÓN');

                // Datos domicilio
                $sheet->mergeCells('A15:B15');
                $sheet->mergeCells('C15:D15');
                $sheet->mergeCells('E15:G15');
                $sheet->setCellValue('A15', strtoupper($this->usuario->fichaGeneral->domicilioVia ?? ''));
                $sheet->setCellValue('C15', strtoupper($this->usuario->fichaGeneral->domicilioMzLt ?? ''));
                $sheet->setCellValue('E15', strtoupper($this->usuario->fichaGeneral->domicilioUrb ?? ''));

                // ============================================
                // DEPARTAMENTO, PROVINCIA, DISTRITO (Fila 16: Títulos, Fila 17: Datos)
                // ============================================
                $sheet->mergeCells('A16:B16');
                $sheet->mergeCells('C16:D16');
                $sheet->mergeCells('E16:G16');
                $sheet->setCellValue('A16', 'DEPARTAMENTO');
                $sheet->setCellValue('C16', 'PROVINCIA');
                $sheet->setCellValue('E16', 'DISTRITO');

                // Datos ubicación domicilio
                $depDomicilio = $this->getNombreDepartamento($this->usuario->fichaGeneral->domicilioDepartamento ?? '');
                $provDomicilio = $this->getNombreProvincia($this->usuario->fichaGeneral->domicilioProvincia ?? '');
                $distDomicilio = $this->getNombreDistrito($this->usuario->fichaGeneral->domicilioDistrito ?? '');

                $sheet->mergeCells('A17:B17');
                $sheet->mergeCells('C17:D17');
                $sheet->mergeCells('E17:G17');
                $sheet->setCellValue('A17', strtoupper($depDomicilio));
                $sheet->setCellValue('C17', strtoupper($provDomicilio));
                $sheet->setCellValue('E17', strtoupper($distDomicilio));

                // ============================================
                // DATOS BANCARIOS (Fila 18: Títulos, Fila 19: Datos)
                // ============================================
                // TÍTULOS
                $sheet->mergeCells('A18:B18');
                $sheet->setCellValue('A18', 'ENTIDAD BANCARIA');
                $sheet->setCellValue('C18', 'TIPO CUENTA');
                $sheet->setCellValue('D18', 'MONEDA');
                $sheet->setCellValue('E18', 'N° CUENTA');
                $sheet->mergeCells('F18:G18');
                $sheet->setCellValue('F18', 'CCI');

                // DATOS BANCARIOS
                $entidadBancaria = $this->usuario->fichaGeneral->entidadBancaria ?? '';
                $tipoCuenta = $this->usuario->fichaGeneral->tipoCuenta ?? '';
                $moneda = $this->usuario->fichaGeneral->moneda ?? '';
                $numeroCuenta = $this->usuario->fichaGeneral->numeroCuenta ?? '';
                $numeroCCI = $this->usuario->fichaGeneral->numeroCCI ?? '';

                $sheet->mergeCells('A19:B19');
                $sheet->setCellValue('A19', strtoupper($this->getNombreEntidadBancaria($entidadBancaria)));
                $sheet->setCellValue('C19', strtoupper($this->getNombreTipoCuenta($tipoCuenta)));
                $sheet->setCellValue('D19', strtoupper($this->getNombreMoneda($moneda)));
                $sheet->setCellValue('E19', $numeroCuenta);
                $sheet->mergeCells('F19:G19');
                $sheet->setCellValue('F19', $numeroCCI);

                // ============================================
                // SEGURO Y PENSIÓN (Fila 20: Títulos, Fila 21: Datos)
                // ============================================
                $sheet->mergeCells('A20:B20');
                $sheet->mergeCells('C20:D20');
                $sheet->mergeCells('E20:G20');
                $sheet->setCellValue('A20', 'SEGURO SALUD');
                $sheet->setCellValue('C20', 'SISTEMA PENSIONES');
                $sheet->setCellValue('E20', 'COMPAÑÍA AFP');

                // Datos seguro y pensión
                $sheet->mergeCells('A21:B21');
                $sheet->mergeCells('C21:D21');
                $sheet->mergeCells('E21:G21');
                $sheet->setCellValue('A21', $this->getFormatoSeguro($this->usuario->fichaGeneral->seguroSalud ?? ''));
                $sheet->setCellValue('C21', $this->getFormatoPension($this->usuario->fichaGeneral->sistemaPensiones ?? ''));
                $sheet->setCellValue('E21', $this->getFormatoAFP($this->usuario->fichaGeneral->afpCompania ?? ''));

                // ============================================
                // SECCIÓN: INFORMACIÓN ACADÉMICA
                // ============================================
                $filaActual = 22;

                // Título de la sección
                $sheet->mergeCells('A' . $filaActual . ':G' . $filaActual);
                $sheet->setCellValue('A' . $filaActual, '2. INFORMACIÓN ACADÉMICA (Consignar los estudios realizados)');
                $sheet->getStyle('A' . $filaActual)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']],
                ]);
                $filaActual++;

                // Encabezados de la tabla (Fila 23)
                $sheet->setCellValue('A' . $filaActual, 'NIVEL');
                $sheet->setCellValue('B' . $filaActual, 'TERMINÓ');
                $sheet->setCellValue('C' . $filaActual, 'CENTRO DE ESTUDIOS');
                $sheet->setCellValue('D' . $filaActual, 'ESPECIALIDAD');
                $sheet->setCellValue('E' . $filaActual, 'NIVEL / GRADO ACADÉMICO');
                $sheet->setCellValue('F' . $filaActual, 'F. INICIO');
                $sheet->setCellValue('G' . $filaActual, 'F. FIN');

                // Aplicar estilo a los encabezados
                $sheet->getStyle('A' . $filaActual . ':G' . $filaActual)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']],
                ]);
                $filaActual++;

                // Obtener los estudios del usuario
                $estudios = $this->usuario->estudios ?? collect();

                // Mostrar estudios
                if ($estudios->count() > 0) {
                    foreach ($estudios as $estudio) {
                        $termino = $estudio->termino ? 'SI [X] NO [ ]' : 'SI [ ] NO [X]';
                        $fechaInicio = $estudio->fechaInicio ? Carbon::parse($estudio->fechaInicio)->format('d/m/Y') : '';
                        $fechaFin = $estudio->fechaFin ? Carbon::parse($estudio->fechaFin)->format('d/m/Y') : '';

                        $sheet->setCellValue('A' . $filaActual, $estudio->nivel ?? '');
                        $sheet->setCellValue('B' . $filaActual, $termino);
                        $sheet->setCellValue('C' . $filaActual, $estudio->centroEstudios ?? '');
                        $sheet->setCellValue('D' . $filaActual, $estudio->especialidad ?? '');
                        $sheet->setCellValue('E' . $filaActual, $estudio->gradoAcademico ?? '');
                        $sheet->setCellValue('F' . $filaActual, $fechaInicio);
                        $sheet->setCellValue('G' . $filaActual, $fechaFin);
                        $filaActual++;
                    }
                } else {
                    $niveles = ['SECUNDARIA', 'TÉCNICO', 'UNIVERSITARIO', 'POSTGRADO', 'MAESTRÍA'];
                    foreach ($niveles as $nivel) {
                        $sheet->setCellValue('A' . $filaActual, $nivel);
                        $sheet->setCellValue('B' . $filaActual, 'SI [ ] NO [ ]');
                        $filaActual++;
                    }
                }

                // ============================================
                // SECCIÓN: INFORMACIÓN FAMILIAR
                // ============================================

                // Título de la sección
                $sheet->mergeCells('A' . $filaActual . ':G' . $filaActual);
                $sheet->setCellValue('A' . $filaActual, '3. INFORMACIÓN FAMILIAR');
                $sheet->getStyle('A' . $filaActual)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']],
                ]);
                $filaActual++;

                // Encabezados de la tabla familiar
                $sheet->setCellValue('A' . $filaActual, 'PARENTESCO');
                $sheet->setCellValue('B' . $filaActual, 'APELLIDOS Y NOMBRES');
                $sheet->setCellValue('C' . $filaActual, 'Nº DOC.');
                $sheet->setCellValue('D' . $filaActual, 'OCUPACIÓN');
                $sheet->setCellValue('E' . $filaActual, 'SEXO');
                $sheet->setCellValue('F' . $filaActual, 'F. NAC.');
                $sheet->setCellValue('G' . $filaActual, 'DOMICILIO ACTUAL');

                // Aplicar estilo a los encabezados (SIN FONDO GRIS)
                $sheet->getStyle('A' . $filaActual . ':G' . $filaActual)->applyFromArray([
                    'font' => ['bold' => true],
                ]);
                $filaActual++;

                // Obtener los familiares del usuario
                $familiares = $this->usuario->familiares ?? collect();

                // Si hay familiares, los mostramos
                if ($familiares->count() > 0) {
                    foreach ($familiares as $familiar) {
                        $fechaNacFamiliar = $familiar->fechaNacimiento ? Carbon::parse($familiar->fechaNacimiento)->format('d/m/Y') : '';

                        $sheet->setCellValue('A' . $filaActual, $familiar->parentesco ?? '');
                        $sheet->setCellValue('B' . $filaActual, strtoupper($familiar->apellidosNombres ?? ''));
                        $sheet->setCellValue('C' . $filaActual, $familiar->nroDocumento ?? '');
                        $sheet->setCellValue('D' . $filaActual, $familiar->ocupacion ?? '');
                        $sheet->setCellValue('E' . $filaActual, strtoupper($familiar->sexo ?? ''));
                        $sheet->setCellValue('F' . $filaActual, $fechaNacFamiliar);
                        $sheet->setCellValue('G' . $filaActual, strtoupper($familiar->domicilioActual ?? ''));
                        $filaActual++;
                    }
                } else {
                    $sheet->setCellValue('A' . $filaActual, 'CÓNYUGE');
                    $filaActual++;
                    $sheet->setCellValue('A' . $filaActual, 'CONCUBIN@');
                    $filaActual++;
                    $sheet->setCellValue('A' . $filaActual, 'HIJOS');
                    $sheet->getStyle('A' . $filaActual)->getFont()->setBold(true);
                    $filaActual++;
                    for ($i = 1; $i <= 4; $i++) {
                        $sheet->setCellValue('A' . $filaActual, $i);
                        $filaActual++;
                    }
                }

                // ============================================
                // SECCIÓN: INFORMACIÓN DE SALUD
                // ============================================

                // Título de la sección
                $sheet->mergeCells('A' . $filaActual . ':G' . $filaActual);
                $sheet->setCellValue('A' . $filaActual, '4. INFORMACIÓN DE SALUD');
                $sheet->getStyle('A' . $filaActual)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']],
                ]);
                $filaActual++;

                // Encabezados de la tabla de salud
                $sheet->setCellValue('A' . $filaActual, 'DETALLES');
                $sheet->setCellValue('B' . $filaActual, 'MARQUE CON (X)');
                $sheet->setCellValue('C' . $filaActual, 'ESPECIFICAR');
                $sheet->mergeCells('C' . $filaActual . ':G' . $filaActual);

                // Aplicar estilo a los encabezados
                $sheet->getStyle('A' . $filaActual . ':G' . $filaActual)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']],
                ]);
                $filaActual++;

                // Obtener datos de salud
                $salud = $this->usuario->salud ?? null;

                // Vacuna COVID-19
                $sheet->setCellValue('A' . $filaActual, '¿Ha recibido la vacuna contra la COVID-19?');
                $sheet->setCellValue('B' . $filaActual, $this->getFormatoCheckbox($salud->vacunaCovid ?? null));
                $dosis1 = $salud && $salud->covidDosis1 ? Carbon::parse($salud->covidDosis1)->format('d/m/Y') : '__/__/____';
                $dosis2 = $salud && $salud->covidDosis2 ? Carbon::parse($salud->covidDosis2)->format('d/m/Y') : '__/__/____';
                $dosis3 = $salud && $salud->covidDosis3 ? Carbon::parse($salud->covidDosis3)->format('d/m/Y') : '__/__/____';
                $sheet->setCellValue('C' . $filaActual, '1° Dosis: ' . $dosis1 . ' | 2° Dosis: ' . $dosis2 . ' | 3° Dosis: ' . $dosis3);
                $sheet->mergeCells('C' . $filaActual . ':G' . $filaActual);
                $filaActual++;

                // Dolencia crónica
                $sheet->setCellValue('A' . $filaActual, '¿Padece de alguna dolencia crónica (asma, úlceras, diabetes, epilepsia, insuficiencia coronaria, hipertensión u otra)?');
                $sheet->setCellValue('B' . $filaActual, $this->getFormatoCheckbox($salud->dolenciaCronica ?? null));
                $sheet->setCellValue('C' . $filaActual, $salud->dolenciaDetalle ?? '');
                $sheet->mergeCells('C' . $filaActual . ':G' . $filaActual);
                $filaActual++;

                // Discapacidad
                $sheet->setCellValue('A' . $filaActual, '¿Padece de algún tipo de discapacidad?');
                $sheet->setCellValue('B' . $filaActual, $this->getFormatoCheckbox($salud->discapacidad ?? null));
                $sheet->setCellValue('C' . $filaActual, $salud->discapacidadDetalle ?? '');
                $sheet->mergeCells('C' . $filaActual . ':G' . $filaActual);
                $filaActual++;

                // TIPO DE SANGRE
                $sheet->setCellValue('A' . $filaActual, 'TIPO DE SANGRE:');
                $sheet->setCellValue('B' . $filaActual, $salud->tipoSangre ?? '');
                $sheet->setCellValue('C' . $filaActual, '');
                $sheet->mergeCells('C' . $filaActual . ':G' . $filaActual);
                $sheet->getStyle('A' . $filaActual . ':B' . $filaActual)->getFont()->setBold(true);
                $filaActual++;

                // Contactos de Emergencia
                $sheet->setCellValue('A' . $filaActual, 'INDICAR DATOS DE FAMILIAR EN CASO DE ACCIDENTE Y/O EMERGENCIA:');
                $sheet->mergeCells('A' . $filaActual . ':G' . $filaActual);
                $sheet->getStyle('A' . $filaActual)->getFont()->setBold(true);
                $filaActual++;

                // Encabezados de contactos de emergencia
                $sheet->setCellValue('A' . $filaActual, 'APELLIDOS Y NOMBRES');
                $sheet->setCellValue('B' . $filaActual, 'PARENTESCO');
                $sheet->setCellValue('C' . $filaActual, 'DIRECCIÓN Y TELÉFONO');
                $sheet->mergeCells('C' . $filaActual . ':G' . $filaActual);

                // Aplicar estilo a los encabezados
                $sheet->getStyle('A' . $filaActual . ':G' . $filaActual)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']],
                ]);
                $filaActual++;

                // Obtener contactos de emergencia
                $contactos = $this->usuario->contactosEmergencia ?? collect();

                // Mostrar contactos
                if ($contactos->count() > 0) {
                    foreach ($contactos as $contacto) {
                        $sheet->setCellValue('A' . $filaActual, strtoupper($contacto->apellidosNombres ?? ''));
                        $sheet->setCellValue('B' . $filaActual, $contacto->parentesco ?? '');
                        $sheet->setCellValue('C' . $filaActual, $contacto->direccionTelefono ?? '');
                        $sheet->mergeCells('C' . $filaActual . ':G' . $filaActual);
                        $filaActual++;
                    }
                } else {
                    for ($i = 1; $i <= 2; $i++) {
                        $sheet->mergeCells('C' . $filaActual . ':G' . $filaActual);
                        $filaActual++;
                    }
                }

                // ============================================
                // SECCIÓN: DATOS LABORALES (DISEÑO CORREGIDO)
                // ============================================

                // Título de la sección
                $sheet->mergeCells('A' . $filaActual . ':G' . $filaActual);
                $sheet->setCellValue('A' . $filaActual, '5. DATOS LABORALES');
                $sheet->getStyle('A' . $filaActual)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']],
                ]);
                $filaActual++;

                // Obtener datos laborales
                $laboral = $this->usuario->laboral ?? null;
                $tipoContrato = null;

                if ($laboral && $laboral->idTipoContrato) {
                    $tipoContrato = \App\Models\TipoContrato::find($laboral->idTipoContrato);
                }

                // ============================================
                // FILA 1: Sueldo y Área (títulos)
                // ============================================
                $sheet->setCellValue('A' . $filaActual, 'SUELDO');
                $sheet->setCellValue('D' . $filaActual, 'ÁREA'); // Cambiado a columna D
                $sheet->mergeCells('A' . $filaActual . ':C' . $filaActual); // Sueldo ocupa A-C
                $sheet->mergeCells('D' . $filaActual . ':G' . $filaActual); // Área ocupa D-G

                // Aplicar estilo a los títulos
                $sheet->getStyle('A' . $filaActual . ':G' . $filaActual)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']],
                ]);
                $filaActual++;

                // ============================================
                // FILA 2: Datos de Sueldo y Área
                // ============================================
                $sheet->setCellValue('A' . $filaActual, 'S/ ' . number_format($this->usuario->sueldoMensual ?? 0, 2));
                $sheet->mergeCells('A' . $filaActual . ':C' . $filaActual);
                $sheet->setCellValue('D' . $filaActual, strtoupper($this->usuario->tipoArea->nombre ?? ''));
                $sheet->mergeCells('D' . $filaActual . ':G' . $filaActual);
                $filaActual++;

                // ============================================
                // FILA 3: Tipo de Contrato y Cargo (títulos)
                // ============================================
                $sheet->setCellValue('A' . $filaActual, 'TIPO DE CONTRATO');
                $sheet->setCellValue('D' . $filaActual, 'CARGO'); // Cambiado a columna D
                $sheet->mergeCells('A' . $filaActual . ':C' . $filaActual);
                $sheet->mergeCells('D' . $filaActual . ':G' . $filaActual);

                // Aplicar estilo a los títulos
                $sheet->getStyle('A' . $filaActual . ':G' . $filaActual)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']],
                ]);
                $filaActual++;

                // ============================================
                // FILA 4: Datos de Tipo de Contrato y Cargo
                // ============================================
                $sheet->setCellValue('A' . $filaActual, strtoupper($tipoContrato->nombre ?? $laboral->tipoContrato ?? ''));
                $sheet->mergeCells('A' . $filaActual . ':C' . $filaActual);
                $sheet->setCellValue('D' . $filaActual, strtoupper($this->usuario->tipoUsuario->nombre ?? ''));
                $sheet->mergeCells('D' . $filaActual . ':G' . $filaActual);
                $filaActual++;

                // ============================================
                // FILA 5: Fecha de Inicio y Hora de Inicio de Jornada (títulos)
                // ============================================
                $sheet->setCellValue('A' . $filaActual, 'FECHA DE INICIO');
                $sheet->setCellValue('D' . $filaActual, 'HORA DE INICIO DE JORNADA'); // Cambiado a columna D
                $sheet->mergeCells('A' . $filaActual . ':C' . $filaActual);
                $sheet->mergeCells('D' . $filaActual . ':G' . $filaActual);

                // Aplicar estilo a los títulos
                $sheet->getStyle('A' . $filaActual . ':G' . $filaActual)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']],
                ]);
                $filaActual++;

                // ============================================
                // FILA 6: Datos de Fecha de Inicio y Hora de Inicio
                // ============================================
                $fechaInicio = $laboral && $laboral->fechaInicio ? Carbon::parse($laboral->fechaInicio)->format('d/m/Y') : '';
                $horaInicio = $laboral && $laboral->horaInicioJornada ? Carbon::parse($laboral->horaInicioJornada)->format('H:i') : '';

                $sheet->setCellValue('A' . $filaActual, $fechaInicio);
                $sheet->mergeCells('A' . $filaActual . ':C' . $filaActual);
                $sheet->setCellValue('D' . $filaActual, $horaInicio);
                $sheet->mergeCells('D' . $filaActual . ':G' . $filaActual);
                $filaActual++;

                // ============================================
                // FILA 7: Fecha de Término y Hora de Término de Jornada (títulos)
                // ============================================
                $sheet->setCellValue('A' . $filaActual, 'FECHA DE TÉRMINO');
                $sheet->setCellValue('D' . $filaActual, 'HORA DE TÉRMINO DE JORNADA'); // Cambiado a columna D
                $sheet->mergeCells('A' . $filaActual . ':C' . $filaActual);
                $sheet->mergeCells('D' . $filaActual . ':G' . $filaActual);

                // Aplicar estilo a los títulos
                $sheet->getStyle('A' . $filaActual . ':G' . $filaActual)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']],
                ]);
                $filaActual++;

                // ============================================
                // FILA 8: Datos de Fecha de Término y Hora de Término
                // ============================================
                $fechaTermino = $laboral && $laboral->fechaTermino ? Carbon::parse($laboral->fechaTermino)->format('d/m/Y') : '';
                $horaFin = $laboral && $laboral->horaFinJornada ? Carbon::parse($laboral->horaFinJornada)->format('H:i') : '';

                $sheet->setCellValue('A' . $filaActual, $fechaTermino);
                $sheet->mergeCells('A' . $filaActual . ':C' . $filaActual);
                $sheet->setCellValue('D' . $filaActual, $horaFin);
                $sheet->mergeCells('D' . $filaActual . ':G' . $filaActual);
                $filaActual++;

                // ============================================
                // SECCIÓN: DOCUMENTOS IMPORTANTES (VERSIÓN FINAL)
                // ============================================

                // Título de la sección
                $sheet->mergeCells('A' . $filaActual . ':G' . $filaActual);
                $sheet->setCellValue('A' . $filaActual, '6. DOCUMENTOS IMPORTANTES');
                $sheet->getStyle('A' . $filaActual)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']],
                ]);
                $filaActual++;

                // Obtener documentos del usuario
                $documentos = $this->usuario->documentos_usuario ?? collect();

                // Función auxiliar para verificar si existe un tipo de documento
                $tieneDocumento = function ($tipo) use ($documentos) {
                    $tipo = strtoupper($tipo);
                    $existe = $documentos->filter(function ($doc) use ($tipo) {
                        return strtoupper($doc->tipo_documento ?? '') === $tipo;
                    })->count() > 0;
                    return $existe ? 'X' : '';
                };

                // ============================================
                // FILA 1: Primera fila de títulos (3 columnas)
                // ============================================
                $sheet->setCellValue('A' . $filaActual, 'Currículum Vitae');
                $sheet->setCellValue('C' . $filaActual, 'Certificado de antecedentes policiales');
                $sheet->setCellValue('E' . $filaActual, 'Declaración Jurada de domicilio');

                // Merges para distribuir correctamente (cada título ocupa 2 columnas)
                $sheet->mergeCells('A' . $filaActual . ':B' . $filaActual);
                $sheet->mergeCells('C' . $filaActual . ':D' . $filaActual);
                $sheet->mergeCells('E' . $filaActual . ':G' . $filaActual);

                // Aplicar estilo a los títulos
                $sheet->getStyle('A' . $filaActual . ':G' . $filaActual)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']],
                ]);
                $filaActual++;

                // ============================================
                // FILA 2: Valores de la primera fila de documentos
                // ============================================
                $sheet->setCellValue('A' . $filaActual, $tieneDocumento('CV'));
                $sheet->mergeCells('A' . $filaActual . ':B' . $filaActual);
                $sheet->setCellValue('C' . $filaActual, $tieneDocumento('PENALES'));
                $sheet->mergeCells('C' . $filaActual . ':D' . $filaActual);
                $sheet->setCellValue('E' . $filaActual, $tieneDocumento('DOMICILIO'));
                $sheet->mergeCells('E' . $filaActual . ':G' . $filaActual);
                $filaActual++;

                // ============================================
                // FILA 3: Segunda fila de títulos (3 columnas)
                // ============================================
                $sheet->setCellValue('A' . $filaActual, 'Copia de DNI Vigente');
                $sheet->setCellValue('C' . $filaActual, 'Certificados de trabajos anteriores');
                $sheet->setCellValue('E' . $filaActual, 'Partida de Matrimonio u otros');

                // Merges para distribuir correctamente
                $sheet->mergeCells('A' . $filaActual . ':B' . $filaActual);
                $sheet->mergeCells('C' . $filaActual . ':D' . $filaActual);
                $sheet->mergeCells('E' . $filaActual . ':G' . $filaActual);

                // Aplicar estilo a los títulos
                $sheet->getStyle('A' . $filaActual . ':G' . $filaActual)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']],
                ]);
                $filaActual++;

                // ============================================
                // FILA 4: Valores de la segunda fila de documentos
                // ============================================
                $sheet->setCellValue('A' . $filaActual, $tieneDocumento('DNI'));
                $sheet->mergeCells('A' . $filaActual . ':B' . $filaActual);
                $sheet->setCellValue('C' . $filaActual, $tieneDocumento('TRABAJOS'));
                $sheet->mergeCells('C' . $filaActual . ':D' . $filaActual);
                $sheet->setCellValue('E' . $filaActual, $tieneDocumento('MATRIMONIO'));
                $sheet->mergeCells('E' . $filaActual . ':G' . $filaActual);
                $filaActual++;

                // ============================================
                // FILA 5: Tercera fila de títulos (3 columnas)
                // ============================================
                $sheet->setCellValue('A' . $filaActual, 'Cartilla de Vacunación');
                $sheet->setCellValue('C' . $filaActual, 'Certificados de estudios técnicos u otros');
                $sheet->setCellValue('E' . $filaActual, 'Copia de DNI de hijos');

                // Merges para distribuir correctamente
                $sheet->mergeCells('A' . $filaActual . ':B' . $filaActual);
                $sheet->mergeCells('C' . $filaActual . ':D' . $filaActual);
                $sheet->mergeCells('E' . $filaActual . ':G' . $filaActual);

                // Aplicar estilo a los títulos
                $sheet->getStyle('A' . $filaActual . ':G' . $filaActual)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']],
                ]);
                $filaActual++;

                // ============================================
                // FILA 6: Valores de la tercera fila de documentos
                // ============================================
                $sheet->setCellValue('A' . $filaActual, $tieneDocumento('VACUNACION'));
                $sheet->mergeCells('A' . $filaActual . ':B' . $filaActual);
                $sheet->setCellValue('C' . $filaActual, $tieneDocumento('ESTUDIOS'));
                $sheet->mergeCells('C' . $filaActual . ':D' . $filaActual);
                $sheet->setCellValue('E' . $filaActual, $tieneDocumento('DNI_HIJOS'));
                $sheet->mergeCells('E' . $filaActual . ':G' . $filaActual);
                $filaActual++;

                // ============================================
                // FILA 7: Declaración jurada (CON ALTURA AUMENTADA)
                // ============================================
                // Guardar el número de fila para ajustar altura después
                $filaDeclaracion = $filaActual;

                $sheet->setCellValue('A' . $filaActual, 'Declaro bajo juramento que los datos proporcionados a la EMPRESA son veraces, autorizando a la misma efectuar las verificaciones que considere pertinentes. En caso se compruebe que los datos no son verídicos, LA EMPRESA podrá tomar las medidas que considere convenientes sin compromiso ni responsabilidad alguna.');
                $sheet->mergeCells('A' . $filaActual . ':G' . $filaActual);
                $sheet->getStyle('A' . $filaActual)->getAlignment()->setWrapText(true);
                $sheet->getStyle('A' . $filaActual)->getFont()->setItalic(true);

                // Aumentar la altura de la fila de la declaración para que se lea completa
                $sheet->getRowDimension($filaDeclaracion)->setRowHeight(40); // 40 píxeles de altura

                // ============================================
                // SECCIÓN: FIRMA Y DECLARACIÓN (AJUSTE FINAL)
                // ============================================

                // Guardamos la última fila con bordes (documentos importantes)
                $ultimaFilaConBordes = $filaActual - 1;

                $filaActual++; // Espacio en blanco después de documentos importantes

                // Avanzar hasta llegar aproximadamente a la fila 57
                while ($filaActual < 57) {
                    $sheet->setCellValue('A' . $filaActual, '');
                    $filaActual++;
                }

                // ============================================
                // FILA 57: Espacio (vacío)
                // ============================================
                $sheet->setCellValue('A' . $filaActual, '');
                $filaActual++; // Fila 58

                // ============================================
                // FILA 58: IMAGEN DE LA FIRMA (COLUMNA D)
                // ============================================
                $filaFirma = 58; // Fija en fila 58

                // Verificar si hay firma guardada
                if (!empty($this->usuario->firma)) {
                    // Crear un archivo temporal con la imagen de la firma
                    $tempFile = tempnam(sys_get_temp_dir(), 'firma_');
                    file_put_contents($tempFile, $this->usuario->firma);

                    // Insertar la imagen en el Excel en columna D
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setName('Firma');
                    $drawing->setDescription('Firma del trabajador');
                    $drawing->setPath($tempFile);
                    $drawing->setHeight(50); // Altura de la firma
                    $drawing->setWidth(150); // Ancho de la firma
                    $drawing->setCoordinates('D' . $filaFirma); // COLUMNA D, FILA 58
                    $drawing->setWorksheet($sheet);
                }

                $filaActual = 61; // Continuamos desde la fila 61

                // ============================================
                // FILA 61: TEXTO "Firma del Trabajador" (COLUMNA D) y Fecha (COLUMNAS A, B, C)
                // ============================================

                // Obtener fecha actual para mostrar en columnas A, B, C
                $fechaActual = Carbon::now();
                $dia = $fechaActual->format('d');
                $mes = $fechaActual->format('m');
                $anio = $fechaActual->format('Y');
                $nombreMes = $this->getNombreMes($mes);

                // Fecha en columnas A, B, C
                $sheet->setCellValue('A' . $filaActual, 'Lima, ' . $dia . ' de ' . $nombreMes . ' de ' . $anio);
                $sheet->mergeCells('A' . $filaActual . ':C' . $filaActual);
                $sheet->getStyle('A' . $filaActual)->getFont()->setBold(true);
                $sheet->getStyle('A' . $filaActual)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // "Firma del Trabajador" en columna D
                $sheet->setCellValue('D' . $filaActual, 'Firma del Trabajador');
                $sheet->getStyle('D' . $filaActual)->getFont()->setBold(true);
                $sheet->getStyle('D' . $filaActual)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                $filaActual++; // Fila 62

                // ============================================
                // FILA 62: DNI (COLUMNA D - SIN MERGE) y HUELLA (COLUMNA F)
                // ============================================
                $sheet->setCellValue('D' . $filaActual, 'DNI N° ' . ($this->usuario->documento ?? '____________'));
                $sheet->getStyle('D' . $filaActual)->getFont()->setBold(true);
                $sheet->getStyle('D' . $filaActual)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // Huella en columna F
                $sheet->setCellValue('F' . $filaActual, 'Huella');
                $sheet->getStyle('F' . $filaActual)->getFont()->setBold(true);
                $sheet->getStyle('F' . $filaActual)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                $filaActual++; // Fila 63

                // ============================================
                // FILA 63: Espacio para la huella (vacío)
                // ============================================
                $sheet->setCellValue('F' . $filaActual, '');
                $sheet->mergeCells('F' . $filaActual . ':G' . $filaActual);
                $filaActual += 2;

                // ============================================
                // APLICAR BORDES EXTERNOS A COLUMNA F, FILAS 58-61
                // ============================================
                $sheet->getStyle('F58:F61')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                        'inside' => [
                            'borderStyle' => Border::BORDER_NONE,
                        ],
                    ],
                ]);

                // ============================================
                // ESTILOS FINALES
                // ============================================
                $lastRow = $sheet->getHighestRow();

                // Estilos generales (sin bordes)
                $sheet->getStyle('A1:G' . $lastRow)->getFont()->setName('Arial')->setSize(10);
                $sheet->getStyle('A1:G' . $lastRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A1:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1:G' . $lastRow)->getAlignment()->setWrapText(true);

                // Títulos principales
                $sheet->getStyle('A1:G2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                ]);

                // Títulos de sección (solo hasta la última fila con bordes)
                $sheet->getStyle('A4:G4')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']],
                ]);


                // TÍTULOS DE CAMPOS CON FONDO GRIS (solo hasta la última fila con bordes)
                $filasTitulos = [5, 7, 8, 10, 12, 14, 16, 18, 20, 23];
                foreach ($filasTitulos as $fila) {
                    if ($fila <= $ultimaFilaConBordes) {
                        $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray([
                            'font' => ['bold' => true],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']],
                        ]);
                    }
                }

                // APLICAR BORDES SOLO HASTA LA ÚLTIMA FILA CON BORDES (documentos importantes)
                $sheet->getStyle('A1:G' . $ultimaFilaConBordes)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                // Las filas después de $ultimaFilaConBordes NO tienen bordes

                // Configurar página
                $sheet->getPageSetup()
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4)
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
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
        $seguro = strtoupper($seguro);
        return "SIS " . ($seguro == 'SIS' ? '[X]' : '[ ]') . "   ESSALUD " . ($seguro == 'ESSALUD' ? '[X]' : '[ ]') . "   EPS " . ($seguro == 'EPS' ? '[X]' : '[ ]');
    }

    private function getFormatoPension($pension)
    {
        $pension = strtoupper($pension);
        return "ONP " . ($pension == 'ONP' ? '[X]' : '[ ]') . "   AFP " . ($pension == 'AFP' ? '[X]' : '[ ]') . "   N/A " . ($pension == 'NA' ? '[X]' : '[ ]');
    }

    private function getFormatoAFP($afp)
    {
        return "Integra " . ($afp == 'Integra' ? '[X]' : '[ ]') . "   Horizonte " . ($afp == 'Horizonte' ? '[X]' : '[ ]') . "   Profuturo " . ($afp == 'Profuturo' ? '[X]' : '[ ]') . "   Prima " . ($afp == 'Prima' ? '[X]' : '[ ]');
    }

    private function getFormatoCheckbox($valor)
    {
        if ($valor === null) {
            return 'SI [ ] NO [ ]';
        }
        return $valor ? 'SI [X] NO [ ]' : 'SI [ ] NO [X]';
    }
    private function getNombreMes($mes)
    {
        $meses = [
            '01' => 'enero',
            '02' => 'febrero',
            '03' => 'marzo',
            '04' => 'abril',
            '05' => 'mayo',
            '06' => 'junio',
            '07' => 'julio',
            '08' => 'agosto',
            '09' => 'septiembre',
            '10' => 'octubre',
            '11' => 'noviembre',
            '12' => 'diciembre'
        ];

        return $meses[$mes] ?? $mes;
    }
    private function getNombreEntidadBancaria($id)
    {
        $bancos = [
            '1' => 'Banco de Crédito del Perú (BCP)',
            '2' => 'BBVA Perú',
            '3' => 'Scotiabank Perú',
            '4' => 'Interbank',
            '5' => 'Banco de la Nación',
            '6' => 'Banco de Comercio',
            '7' => 'BanBif',
            '8' => 'Banco Pichincha',
            '9' => 'Citibank Perú',
            '10' => 'MiBanco',
            '11' => 'Banco GNB Perú',
            '12' => 'Banco Falabella',
            '13' => 'Banco Ripley',
            '14' => 'Banco Santander Perú',
            '15' => 'Alfin Banco',
            '16' => 'Bank of China',
            '17' => 'Bci Perú',
            '18' => 'ICBC Perú Bank',
        ];

        return $bancos[$id] ?? '';
    }

    private function getNombreTipoCuenta($id)
    {
        $tipos = [
            '1' => 'Ahorros',
            '2' => 'Corriente',
            '3' => 'CTS',
        ];

        return $tipos[$id] ?? '';
    }

    private function getNombreMoneda($moneda)
    {
        $monedas = [
            'PEN' => 'SOLES',
            'USD' => 'DÓLARES',
            '1' => 'SOLES',
            '2' => 'DÓLARES',
        ];

        return $monedas[$moneda] ?? strtoupper($moneda);
    }

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
