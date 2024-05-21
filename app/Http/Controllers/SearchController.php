<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Searchable\Search;
use App\Models\Item;
use App\Models\Customer;

class SearchController extends Controller
{
    
    public function search(Request $request) {
        // dd($request->query('term'));
        $searchResults = (new Search())
        ->registerModel(Item::class, 'description')
        ->registerModel(Customer::class, ['lname', 'fname', 'addressline'])
        ->search(trim($request->query('term')));
        // ?term=value&keyword=nike+adidas
        // dd($searchResults);
        return view('search', compact("searchResults"));
    }
}
