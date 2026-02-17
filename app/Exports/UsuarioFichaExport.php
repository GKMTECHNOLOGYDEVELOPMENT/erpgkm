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

        // SECCIÓN 1: INFORMACIÓN GENERAL
        $data[$this->filaActual++] = ['1. INFORMACIÓN GENERAL', '', '', '', '', '', ''];
        $data[$this->filaActual++] = ['', '', '', '', '', '', ''];

        // Apellidos y Nombres
        $data[$this->filaActual++] = ['APELLIDO PATERNO', 'APELLIDO MATERNO', 'NOMBRES COMPLETOS', '', '', '', ''];
        $this->filaActual++;

        // Fecha de Nacimiento y Lugar
        $data[$this->filaActual++] = ['FECHA DE NACIMIENTO', '', '', 'LUGAR DE NACIMIENTO', '', '', ''];
        $this->filaActual++;

        // Tipo Doc, N° Doc, Edad, Sexo, Nacionalidad
        $data[$this->filaActual++] = ['TIPO DOC.', 'N° DOC.', 'EDAD', 'SEXO', 'NACIONALIDAD', '', ''];
        $this->filaActual++;

        // Estado Civil, Email, Teléfono
        $data[$this->filaActual++] = ['ESTADO CIVIL', 'E-MAIL', 'TELÉFONO', '', '', '', ''];
        $this->filaActual++;

        // Domicilio Actual
        $data[$this->filaActual++] = ['DOMICILIO ACTUAL', 'N° / Mz / Lt', 'URBANIZACIÓN', 'DEPARTAMENTO', 'PROVINCIA', 'DISTRITO', ''];
        $this->filaActual++;

        // Datos Bancarios
        $data[$this->filaActual++] = ['ENTIDAD BANCARIA', '', 'TIPO CUENTA', 'MONEDA', 'N° CUENTA', 'CCI', ''];
        $this->filaActual++;

        // Seguro y Pensiones
        $data[$this->filaActual++] = ['SEGURO SALUD', 'SISTEMA PENSIONES', 'COMPAÑÍA AFP', '', '', '', ''];
        $this->filaActual++;

        // SECCIÓN 2: INFORMACIÓN ACADÉMICA
        $data[$this->filaActual++] = ['2. INFORMACIÓN ACADÉMICA (Consignar los estudios realizados)', '', '', '', '', '', ''];
        $this->filaActual++;

        // Encabezados de la tabla
        $data[$this->filaActual++] = ['NIVEL', 'TERMINÓ', 'CENTRO DE ESTUDIOS', 'ESPECIALIDAD', 'NIVEL / GRADO ACADÉMICO', 'F. INICIO', 'F. FIN'];
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
                $sheet->mergeCells('A1:G1');
                $sheet->mergeCells('A2:G2');
                $sheet->setCellValue('A1', 'FORMATOS Y REGISTROS');
                $sheet->setCellValue('A2', 'FICHA DE DATOS DEL PERSONAL');

                // Título de sección 1
                $sheet->mergeCells('A4:G4');
                $sheet->setCellValue('A4', '1. INFORMACIÓN GENERAL');

                // ============================================
                // APELLIDOS Y NOMBRES
                // ============================================
                $sheet->mergeCells('A6:B6');
                $sheet->mergeCells('C6:E6');
                $sheet->mergeCells('F6:G6');
                $sheet->setCellValue('A6', 'APELLIDO PATERNO');
                $sheet->setCellValue('C6', 'APELLIDO MATERNO');
                $sheet->setCellValue('F6', 'NOMBRES COMPLETOS');

                // Datos de apellidos y nombres
                $sheet->mergeCells('A7:B7');
                $sheet->mergeCells('C7:E7');
                $sheet->mergeCells('F7:G7');
                $sheet->setCellValue('A7', strtoupper($this->usuario->apellidoPaterno ?? ''));
                $sheet->setCellValue('C7', strtoupper($this->usuario->apellidoMaterno ?? ''));
                $sheet->setCellValue('F7', strtoupper($this->usuario->Nombre ?? ''));

                // ============================================
                // FECHA DE NACIMIENTO
                // ============================================
                $fechaNac = $this->usuario->fechaNacimiento ? Carbon::parse($this->usuario->fechaNacimiento) : null;

                // Títulos
                $sheet->mergeCells('A8:C8');
                $sheet->mergeCells('D8:G8');
                $sheet->setCellValue('A8', 'FECHA DE NACIMIENTO');
                $sheet->setCellValue('D8', 'LUGAR DE NACIMIENTO');

                // Subtítulos
                $sheet->setCellValue('A9', 'DÍA');
                $sheet->setCellValue('B9', 'MES');
                $sheet->setCellValue('C9', 'AÑO');
                $sheet->mergeCells('D9:G9');
                $sheet->setCellValue('D9', 'DEPARTAMENTO - PROVINCIA - DISTRITO');

                // DATOS: DÍA, MES, AÑO
                $sheet->setCellValue('A10', $fechaNac ? $fechaNac->format('d') : '');
                $sheet->setCellValue('B10', $fechaNac ? $fechaNac->format('m') : '');
                $sheet->setCellValue('C10', $fechaNac ? $fechaNac->format('Y') : '');

                // Lugar de nacimiento
                $depNac = $this->getNombreDepartamento($this->usuario->fichaGeneral->nacimientoDepartamento ?? '');
                $provNac = $this->getNombreProvincia($this->usuario->fichaGeneral->nacimientoProvincia ?? '');
                $distNac = $this->getNombreDistrito($this->usuario->fichaGeneral->nacimientoDistrito ?? '');

                $lugarNacimiento = trim($depNac . ' - ' . $provNac . ' - ' . $distNac, ' -');
                $sheet->mergeCells('D10:G10');
                $sheet->setCellValue('D10', strtoupper($lugarNacimiento));

                // ============================================
                // TIPO DOC, N° DOC, EDAD, SEXO, NACIONALIDAD
                // ============================================
                $sheet->mergeCells('A11:B11');
                $sheet->mergeCells('C11:D11');
                $sheet->setCellValue('A11', 'TIPO DOC.');
                $sheet->setCellValue('C11', 'N° DOC.');
                $sheet->setCellValue('E11', 'EDAD');
                $sheet->setCellValue('F11', 'SEXO');
                $sheet->setCellValue('G11', 'NAC.');

                // Datos
                $sheet->mergeCells('A12:B12');
                $sheet->mergeCells('C12:D12');
                $sheet->setCellValue('A12', strtoupper($this->usuario->tipoDocumento->nombre ?? ''));
                $sheet->setCellValue('C12', $this->usuario->documento ?? '');
                $sheet->setCellValue('E12', $fechaNac ? $fechaNac->age . ' años' : '');
                $sheet->setCellValue('F12', strtoupper($this->usuario->sexo->nombre ?? ''));
                $sheet->setCellValue('G12', strtoupper($this->usuario->nacionalidad ?? 'Peruana'));

                // ============================================
                // ESTADO CIVIL, EMAIL, TELÉFONO
                // ============================================
                $sheet->mergeCells('A13:B13');
                $sheet->mergeCells('C13:D13');
                $sheet->mergeCells('E13:G13');
                $sheet->setCellValue('A13', 'ESTADO CIVIL');
                $sheet->setCellValue('C13', 'E-MAIL');
                $sheet->setCellValue('E13', 'TELÉFONO');

                // Datos
                $sheet->mergeCells('A14:B14');
                $sheet->mergeCells('C14:D14');
                $sheet->mergeCells('E14:G14');
                $sheet->setCellValue('A14', strtoupper($this->getEstadoCivilTexto($this->usuario->estadocivil)));
                $sheet->setCellValue('C14', strtolower($this->usuario->correo ?? ''));
                $sheet->setCellValue('E14', $this->usuario->telefono ?? '');

                // ============================================
                // DOMICILIO
                // ============================================
                $sheet->mergeCells('A15:B15');
                $sheet->mergeCells('C15:D15');
                $sheet->mergeCells('E15:G15');
                $sheet->setCellValue('A15', 'DOMICILIO');
                $sheet->setCellValue('C15', 'N° / Mz / Lt');
                $sheet->setCellValue('E15', 'URBANIZACIÓN');

                // Datos domicilio
                $sheet->mergeCells('A16:B16');
                $sheet->mergeCells('C16:D16');
                $sheet->mergeCells('E16:G16');
                $sheet->setCellValue('A16', strtoupper($this->usuario->fichaGeneral->domicilioVia ?? ''));
                $sheet->setCellValue('C16', strtoupper($this->usuario->fichaGeneral->domicilioMzLt ?? ''));
                $sheet->setCellValue('E16', strtoupper($this->usuario->fichaGeneral->domicilioUrb ?? ''));

                // ============================================
                // DEPARTAMENTO, PROVINCIA, DISTRITO (DOMICILIO)
                // ============================================
                $sheet->mergeCells('A17:B17');
                $sheet->mergeCells('C17:D17');
                $sheet->mergeCells('E17:G17');
                $sheet->setCellValue('A17', 'DEPARTAMENTO');
                $sheet->setCellValue('C17', 'PROVINCIA');
                $sheet->setCellValue('E17', 'DISTRITO');

                // Datos ubicación domicilio
                $depDomicilio = $this->getNombreDepartamento($this->usuario->fichaGeneral->domicilioDepartamento ?? '');
                $provDomicilio = $this->getNombreProvincia($this->usuario->fichaGeneral->domicilioProvincia ?? '');
                $distDomicilio = $this->getNombreDistrito($this->usuario->fichaGeneral->domicilioDistrito ?? '');

                $sheet->mergeCells('A18:B18');
                $sheet->mergeCells('C18:D18');
                $sheet->mergeCells('E18:G18');
                $sheet->setCellValue('A18', strtoupper($depDomicilio));
                $sheet->setCellValue('C18', strtoupper($provDomicilio));
                $sheet->setCellValue('E18', strtoupper($distDomicilio));

                // ============================================
                // DATOS BANCARIOS
                // ============================================
                // TÍTULOS (Fila 19)
                $sheet->mergeCells('A19:B19');
                $sheet->setCellValue('A19', 'ENTIDAD BANCARIA');
                $sheet->setCellValue('C19', 'TIPO CUENTA');
                $sheet->setCellValue('D19', 'MONEDA');
                $sheet->setCellValue('E19', 'N° CUENTA');
                $sheet->mergeCells('F19:G19');
                $sheet->setCellValue('F19', 'CCI');

                // DATOS BANCARIOS (Fila 20)
                $entidadBancaria = $this->usuario->fichaGeneral->entidadBancaria ?? '';
                $tipoCuenta = $this->usuario->fichaGeneral->tipoCuenta ?? '';
                $moneda = $this->usuario->fichaGeneral->moneda ?? '';
                $numeroCuenta = $this->usuario->fichaGeneral->numeroCuenta ?? '';
                $numeroCCI = $this->usuario->fichaGeneral->numeroCCI ?? '';

                $sheet->mergeCells('A20:B20');
                $sheet->setCellValue('A20', strtoupper($this->getNombreEntidadBancaria($entidadBancaria)));
                $sheet->setCellValue('C20', strtoupper($this->getNombreTipoCuenta($tipoCuenta)));
                $sheet->setCellValue('D20', strtoupper($this->getNombreMoneda($moneda)));
                $sheet->setCellValue('E20', $numeroCuenta);
                $sheet->mergeCells('F20:G20');
                $sheet->setCellValue('F20', $numeroCCI);

                // ============================================
                // SEGURO Y PENSIÓN
                // ============================================
                $sheet->mergeCells('A21:B21');
                $sheet->mergeCells('C21:D21');
                $sheet->mergeCells('E21:G21');
                $sheet->setCellValue('A21', 'SEGURO SALUD');
                $sheet->setCellValue('C21', 'SISTEMA PENSIONES');
                $sheet->setCellValue('E21', 'COMPAÑÍA AFP');

                // Datos seguro y pensión
                $sheet->mergeCells('A22:B22');
                $sheet->mergeCells('C22:D22');
                $sheet->mergeCells('E22:G22');
                $sheet->setCellValue('A22', $this->getFormatoSeguro($this->usuario->fichaGeneral->seguroSalud ?? ''));
                $sheet->setCellValue('C22', $this->getFormatoPension($this->usuario->fichaGeneral->sistemaPensiones ?? ''));
                $sheet->setCellValue('E22', $this->getFormatoAFP($this->usuario->fichaGeneral->afpCompania ?? ''));

                // ============================================
                // SECCIÓN: INFORMACIÓN ACADÉMICA
                // ============================================
                $filaActual = 23;

                // Título de la sección
                $sheet->mergeCells('A' . $filaActual . ':G' . $filaActual);
                $sheet->setCellValue('A' . $filaActual, '2. INFORMACIÓN ACADÉMICA (Consignar los estudios realizados)');
                $sheet->getStyle('A' . $filaActual)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']],
                ]);
                $filaActual++;

                // Encabezados de la tabla
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

                // Aplicar estilo a los encabezados
                $sheet->getStyle('A' . $filaActual . ':G' . $filaActual)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']],
                ]);
                $filaActual++;

                // Obtener los familiares del usuario
                $familiares = $this->usuario->familiares ?? collect();

                // Si hay familiares, los mostramos
                if ($familiares->count() > 0) {
                    foreach ($familiares as $familiar) {
                        // Formatear fecha de nacimiento
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
                    // Si no hay familiares, mostramos filas vacías
                    $sheet->setCellValue('A' . $filaActual, 'CÓNYUGE');
                    $filaActual++;

                    $sheet->setCellValue('A' . $filaActual, 'CONCUBIN@');
                    $filaActual++;

                    // Subtítulo HIJOS
                    $sheet->setCellValue('A' . $filaActual, 'HIJOS');
                    $sheet->getStyle('A' . $filaActual)->getFont()->setBold(true);
                    $filaActual++;

                    // 4 filas para hijos
                    for ($i = 1; $i <= 4; $i++) {
                        $sheet->setCellValue('A' . $filaActual, $i);
                        $filaActual++;
                    }
                }

                // ============================================
                // SECCIÓN: INFORMACIÓN DE SALUD (MEJORADA)
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

                // Vacuna COVID-19 (ahora con soporte para 3 dosis)
                $sheet->setCellValue('A' . $filaActual, '¿Ha recibido la vacuna contra la COVID-19?');
                $sheet->setCellValue('B' . $filaActual, $this->getFormatoCheckbox($salud->vacunaCovid ?? null));

                // Formatear dosis (1, 2 y 3)
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

                // TIPO DE SANGRE - Ahora como una fila más de la tabla con merge de celdas
                $sheet->setCellValue('A' . $filaActual, 'TIPO DE SANGRE:');
                $sheet->setCellValue('B' . $filaActual, $salud->tipoSangre ?? '');
                $sheet->setCellValue('C' . $filaActual, '');
                $sheet->mergeCells('C' . $filaActual . ':G' . $filaActual);
                // Aplicar estilo para destacar el tipo de sangre
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
                    // 2 filas vacías para contactos
                    for ($i = 1; $i <= 2; $i++) {
                        $sheet->mergeCells('C' . $filaActual . ':G' . $filaActual);
                        $filaActual++;
                    }
                }

                // ============================================
                // ESTILOS FINALES
                // ============================================
                $lastRow = $sheet->getHighestRow();

                // Estilos generales
                $sheet->getStyle('A1:G' . $lastRow)->getFont()->setName('Arial')->setSize(10);
                $sheet->getStyle('A1:G' . $lastRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A1:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1:G' . $lastRow)->getAlignment()->setWrapText(true);

                // Títulos principales
                $sheet->getStyle('A1:G2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                ]);

                // Títulos de sección
                $sheet->getStyle('A4:G4')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']],
                ]);

                // ============================================
                // TÍTULOS DE CAMPOS (FONDO GRIS Y NEGRITA) - ¡AGREGA ESTO!
                // ============================================
                $filasTitulos = [6, 8, 9, 11, 13, 15, 17, 19, 21, 24];
                foreach ($filasTitulos as $fila) {
                    $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']],
                    ]);
                }

                // Bordes
                $sheet->getStyle('A1:G' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                // Ajustar altura de filas
                $sheet->getRowDimension(21)->setRowHeight(25);
                $sheet->getRowDimension(22)->setRowHeight(35);
                $sheet->getRowDimension(23)->setRowHeight(25);
                $sheet->getRowDimension(24)->setRowHeight(25);

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
