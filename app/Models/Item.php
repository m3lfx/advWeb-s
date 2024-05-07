<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Order;

class Item extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'item';
    protected $primaryKey = 'item_id';
    public function orders()
    {
        return $this->belongsToMany(
            Order::class,
            'orderline',
            'item_id',
            'orderinfo_id'
        )->withPivot('quantity');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'item_id');
    }
}
