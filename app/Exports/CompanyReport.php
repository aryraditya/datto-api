<?php

namespace App\Exports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CompanyReport implements WithMultipleSheets
{
    /**
     * @var Company
     */
    private $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    public function sheets(): array
    {
        return [
            new CompanyReportRegional($this->company),
            new CompanyReportAsset($this->company),
            new CompanyReportStorage($this->company)
        ];
    }
}
