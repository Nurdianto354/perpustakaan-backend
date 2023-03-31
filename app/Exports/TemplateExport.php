<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\TemplateSheet;
use App\Exports\KategoriSheet;

class TemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new TemplateSheet();
        $sheets[] = new KategoriSheet();

        return $sheets;
    }
}
