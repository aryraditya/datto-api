<?php

namespace App\Exports;

use App\Models\Company;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class CompanyReportAsset implements FromView, WithEvents, WithTitle
{

    protected $company;

    public function __construct(Company $model)
    {
        $this->company = $model;
    }

    public function title(): string
    {
        return 'Asset Report';
    }

    public function view(): View
    {
        return view('company.report.asset', [
            'model' => $this->company
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class   => function(AfterSheet $event) {
                $worksheet      = $event->sheet->getDelegate();
                $highestColumn  = $worksheet->getHighestColumn();
                $highestRow     = $worksheet->getHighestRow();

                $worksheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $worksheet->getDefaultColumnDimension()->setWidth(10);
                $worksheet->getColumnDimension('A')->setWidth(25);
                $worksheet->getColumnDimension('B')->setWidth(25);

                $worksheet->getStyle("A2")->applyFromArray([
                    'alignment' => [
                        'vertical'  => 'center',
                        'horizontal' => 'left',
                        'wrapText'  => false
                    ],
                    'font'      => [
                        'bold'  => true
                    ]
                ]);


                $worksheet->getStyle("A1:$highestColumn$highestRow")->applyFromArray([
                    'alignment' => [
                        'vertical'  => 'center',
                        'horizontal' => 'left',
                        'wrapText'  => true
                    ],
                    'borders'   => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'EEEEEE']
                        ]
                    ]
                ]);

            }
        ];
    }
}
