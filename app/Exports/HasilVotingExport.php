<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\Chart;

class HasilVotingExport implements FromArray, WithHeadings, WithEvents
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return ['No. Urut', 'Nama Ketua', 'Nama Wakil', 'Kelas', 'Jumlah Suara', 'Persentase'];
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->data['hasilPerKandidat'] as $item) {
            $rows[] = [
                $item->kandidat->no_urut,
                $item->kandidat->nama_ketua,
                $item->kandidat->nama_wakil,
                $item->kandidat->kelas_ketua,
                $item->suara,
                $item->persen . '%',
            ];
        }

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $jumlahBaris = count($this->data['hasilPerKandidat']);
                if ($jumlahBaris === 0) {
                    return;
                }

                $sheet = $event->sheet->getDelegate();
                $baruAkhir = 1 + $jumlahBaris; // data mulai baris 2

                $labels = [new DataSeriesValues('String', "Worksheet!\$B\$2:\$B\${$baruAkhir}", null, $jumlahBaris)];
                $values = [new DataSeriesValues('Number', "Worksheet!\$E\$2:\$E\${$baruAkhir}", null, $jumlahBaris)];
                $categories = [new DataSeriesValues('String', "Worksheet!\$B\$2:\$B\${$baruAkhir}", null, $jumlahBaris)];

                $series = new DataSeries(
                    DataSeries::TYPE_BARCHART,
                    DataSeries::GROUPING_CLUSTERED,
                    range(0, count($values) - 1),
                    $labels,
                    $categories,
                    $values
                );

                $plotArea = new PlotArea(null, [$series]);
                $legend = new Legend(Legend::POSITION_RIGHT, null, false);
                $title = new Title('Hasil Voting per Kandidat');

                $chart = new Chart('chart_hasil_voting', $title, $legend, $plotArea);
                $chart->setTopLeftPosition('H2');
                $chart->setBottomRightPosition('P20');

                $sheet->addChart($chart);
            },
        ];
    }
}