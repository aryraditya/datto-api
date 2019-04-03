<?php

namespace App\Exports;

use App\Models\Company;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class CompanyReportRegional implements FromView, WithEvents, WithTitle
{
    protected $company;

    public function __construct(Company $model)
    {
        $this->company = $model;
    }

    public function title(): string
    {
        return 'Backup Regional';
    }

    public function view(): View
    {
        return view('company.report.regional', [
            'model' => $this->company
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class   => function(AfterSheet $event) {
                $worksheet      = $event->sheet->getDelegate();

                $worksheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $worksheet->getDefaultColumnDimension()->setWidth(20);
                $worksheet->getColumnDimension('B')->setWidth(50);

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

            }
        ];
    }
}
