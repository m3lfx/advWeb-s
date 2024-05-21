<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Imports\CustomerImport;
use App\Imports\ItemImport;

class UserImport implements WithMultipleSheets
{
    /**
    * @param Collection $collection
    */
    public function sheets(): array
    {
        return [
            'customer' => new CustomerImport(),
            'item' => new ItemStockImport(),
            
        ];
    }
}
