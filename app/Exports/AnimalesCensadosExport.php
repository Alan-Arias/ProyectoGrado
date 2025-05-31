<?php

namespace App\Exports;

use App\Models\Animal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Chart\{
    Chart,
    DataSeries,
    DataSeriesValues,
    Layout,
    Legend,
    PlotArea,
    Title
};

class AnimalesCensadosExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return Animal::with(['raza', 'tipoAnimal', 'incapacidad', 'FormaAdquisicion', 'propietario'])
            ->where('censo_data', 'si')
            ->get()
            ->map(function ($animal) {
                return [
                    'ID' => $animal->id,
                    'Nombre' => $animal->nombre,
                    'Color' => $animal->color,
                    'Fecha Nacimiento' => $animal->fecha_nac,
                    'Castrado' => $animal->castrado,
                    'Estado' => $animal->estado,
                    'Fecha Deceso' => $animal->fecha_deceso,
                    'Motivo Deceso' => $animal->motivo_deceso,
                    'Alergico' => $animal->alergico,
                    'Sexo' => $animal->sexo,
                    'N° Chip' => $animal->n_chip,
                    'Carnet Vacuna' => $animal->carnet_vacuna,
                    'Última Vacuna' => $animal->ultima_vacuna,
                    'Código Propietario' => $animal->codigo_propietario,
                    'Raza' => optional($animal->raza)->nombre,
                    'Tipo Animal' => optional($animal->tipoAnimal)->nombre,
                    'Incapacidad' => optional($animal->incapacidad)->descripcion,
                    'Forma Adquisición' => optional($animal->FormaAdquisicion)->nombre,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Color',
            'Fecha Nacimiento',
            'Castrado',
            'Estado',
            'Fecha Deceso',
            'Motivo Deceso',
            'Alergico',
            'Sexo',
            'N° Chip',
            'Carnet Vacuna',
            'Última Vacuna',
            'Código Propietario',
            'Raza',
            'Tipo Animal',
            'Incapacidad',
            'Forma Adquisición'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Aplica estilo al encabezado (primera fila)
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'B3E5FC']]]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Autoajustar columnas
                foreach (range('A', $sheet->getHighestColumn()) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Insertar datos para el gráfico debajo de la tabla
                $row = $sheet->getHighestRow() + 2;
                $sheet->setCellValue('A' . $row, 'Categoría');
                $sheet->setCellValue('B' . $row, 'Cantidad');
                $row++;

                $total = Animal::where('censo_data', 'si')->count();
                $machos = Animal::where('censo_data', 'si')->where('sexo', 'macho')->count();
                $hembras = Animal::where('censo_data', 'si')->where('sexo', 'hembra')->count();
                $castrados = Animal::where('censo_data', 'si')->where('castrado', 'si')->count();

                $data = [
                    ['Total', $total],
                    ['Machos', $machos],
                    ['Hembras', $hembras],
                    ['Castrados', $castrados],
                ];

                foreach ($data as $d) {
                    $sheet->setCellValue('A' . $row, $d[0]);
                    $sheet->setCellValue('B' . $row, $d[1]);
                    $row++;
                }

                // Crear gráfico
                $dataSeriesLabels = [new DataSeriesValues('String', 'Worksheet!$B$' . ($row - 4), null, 1)];
                $xAxisTickValues = [new DataSeriesValues('String', 'Worksheet!$A$' . ($row - 4) . ':$A$' . ($row - 1), null, 4)];
                $dataSeriesValues = [new DataSeriesValues('Number', 'Worksheet!$B$' . ($row - 4) . ':$B$' . ($row - 1), null, 4)];

                $series = new DataSeries(
                    DataSeries::TYPE_BARCHART,
                    DataSeries::GROUPING_CLUSTERED,
                    range(0, count($dataSeriesValues) - 1),
                    $dataSeriesLabels,
                    $xAxisTickValues,
                    $dataSeriesValues
                );

                $plotArea = new PlotArea(null, [$series]);
                $legend = new Legend(Legend::POSITION_RIGHT, null, false);
                $title = new Title('Reporte General de Animales Censados');

                $chart = new Chart(
                    'Reporte Animales',
                    $title,
                    $legend,
                    $plotArea
                );

                // Posición del gráfico (debajo de los datos)
                $chart->setTopLeftPosition('D' . ($row - 5));
                $chart->setBottomRightPosition('L' . ($row + 10));

                $sheet->addChart($chart);
            }
        ];
    }
}
