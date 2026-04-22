<?php

namespace App\Helpers;

use Maatwebsite\Excel\Facades\Excel;

class ExportHelper
{
    public static function export($fileName, $exportable)
    {
         return Excel::download($exportable, $fileName);
    }
}
