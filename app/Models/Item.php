<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Order;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Item extends Model implements Searchable
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'item';
    protected $primaryKey = 'item_id';
    public $fillable = [
        'description',
        'sell_price',
        'cost_price',
        'img_path',
    ];
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

    public function getSearchResult(): SearchResult
    {
       $url = route('items.show', $this->item_id);
    
        return new \Spatie\Searchable\SearchResult(
           $this,
           $this->description,
           $url
        );
    }
}
