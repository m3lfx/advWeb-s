<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Item;
use App\Models\Stock;

class ItemStockImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $item = Item::create([
                'description' => $row['product_name'],
                'cost_price' => $row['cost'],
                'sell_price' => $row['sell_price'],
                'img_path' => 'default.jpg',
                
            ]);
            $stock = new Stock();
            $stock->item_id = $item->item_id;
            $stock->quantity = $row['quantity'];
            $stock->save();
        }
    }
}
