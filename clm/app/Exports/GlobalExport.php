<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;



class GlobalExport implements FromCollection, WithHeadings
{
    protected $data;
    protected $headings;

    public function __construct($data, $headings)
    {
        $this->data = $data;
        $this->headings = $headings;
    }

    // Add the data collection
    public function collection()
    {
        return collect($this->data);
    }

    // Define the column headings
    public function headings(): array
    {
        return $this->headings;
    }
    
    
}
