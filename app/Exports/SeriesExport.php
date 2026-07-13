<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use App\Models\Serie;

class SeriesExport implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Serie::select(
            'seriesCode',
            'seriesYear',
            'documentType',
            'firstDocumentNo',
            'lastDocumentNo'
        )->get();
    }


    public function headings(): array
    {
        return [
            'SÉRIE',
            'ANO',
            'TIPO DOCUMENTO',
            'PRIMEIRO Nº',
            'ÚLTIMO Nº'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Inserir linhas no topo
                $sheet->insertNewRowBefore(1, 4);

                // TÍTULO
                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', 'RELATÓRIO DE SÉRIES');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold'=>true, 'size'=>16],
                    'alignment' => ['horizontal'=>Alignment::HORIZONTAL_CENTER]
                ]);

                // Empresa
                $sheet->mergeCells('A2:E2');
                $sheet->setCellValue('A2', 'ANGOENGENHARIA ERP - Sistema de Facturação');
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Data
                $sheet->mergeCells('A3:E3');
                $sheet->setCellValue('A3', 'Data de impressão: '.now()->format('d/m/Y H:i'));
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Cabeçalho (linha 5)
                $sheet->getStyle('A5:E5')->applyFromArray([
                    'font'=>['bold'=>true,'color'=>['rgb'=>'FFFFFF']],
                    'fill'=>[
                        'fillType'=>'solid',
                        'startColor'=>['rgb'=>'0D6EFD']
                    ],
                    'alignment'=>['horizontal'=>Alignment::HORIZONTAL_CENTER]
                ]);

                // Auto size
                foreach(range('A','E') as $col){
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            }
        ];
    }
}
