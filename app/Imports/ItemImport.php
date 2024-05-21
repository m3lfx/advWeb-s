<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Stock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $item = new Item([
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
