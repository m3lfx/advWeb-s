<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Stock;
use App\Models\Order;
use App\Models\Customer;
use App\Cart;

use Validator;
use Storage;
use DB;
use Session;
use Carbon\Carbon;
use Auth;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::all();
        $items = DB::table('item')->join('stock', 'item.item_id', '=', 'stock.item_id')->get();
        return view('item.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('item.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->description);
        $rules = [
            'img_path' => 'mimes:jpg,bmp,png',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $item = new Item();
        $item->description = $request->description;
        $item->sell_price = $request->sell_price;
        $item->cost_price = $request->cost_price;

        $name = $request->file('img_path')->getClientOriginalName();
        $extension = $request->file('img_path')->getClientOriginalExtension();

        $path = Storage::putFileAs(
            'public/items/images',
            $request->file('img_path'),
            $name
        );
        $item->img_path = 'storage/items/images/' . $name;
        $item->save();

        $stock = new Stock();
        $stock->item_id = $item->item_id;
        $stock->quantity = $request->quantity;
        $stock->save();
        return redirect()->route('items.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = DB::table('item')->join('stock', 'item.item_id', '=', 'stock.item_id')->where('item.item_id', $id)->first();
        // dd($items);
        return view('item.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'img_path' => 'mimes:jpg,bmp,png',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $item = Item::find($id);
        $item->description = $request->description;
        $item->sell_price = $request->sell_price;
        $item->cost_price = $request->cost_price;

        $name = $request->file('img_path')->getClientOriginalName();


        $path = Storage::putFileAs(
            'public/items/images',
            $request->file('img_path'),
            $name
        );
        $item->img_path = 'storage/items/images/' . $name;
        $item->save();

        $stock = Stock::find($id);

        $stock->quantity = $request->quantity;
        $stock->save();
        return redirect()->route('items.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::destroy($id);
        Stock::destroy($id);
        return redirect()->route('items.index');
    }

    public function getItems()
    {
        $items = DB::table('item')->join('stock', 'item.item_id', '=', 'stock.item_id')->get();
        return view('shop.index', compact('items'));
    }

    public function addToCart($id)
    {
        $item = Item::find($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        // dd($oldCart);
        $cart = new Cart($oldCart);
        // dd($cart);
        $cart->add($item, $item->item_id);

        Session::put('cart', $cart);
        // dd(Session::get('cart'));
        // $request->session()->save();
        Session::save();
        // dd(Session::get('cart'));

        return redirect('/');
    }

    public function getCart()
    {
        if (!Session::has('cart')) {
            return view('shop.shopping-cart');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        // dd($cart);
        return view('shop.shopping-cart', ['products' => $cart->items, 'totalPrice' => $cart->totalPrice]);
    }

    public function getReduceByOne($id)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->reduceByOne($id);
        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
            // Session::save();
        } else {
            Session::forget('cart');
        }
        return redirect()->route('getCart');
    }

    public function getRemoveItem($id)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);
        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
            Session::save();
        } else {
            Session::forget('cart');
        }
        return redirect()->route('getCart');
    }

    public function postCheckout(){
        if (!Session::has('cart')) {
            return redirect()->route('getCart');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        // dd($cart->items);
        try {
            DB::beginTransaction();
            $order = new Order();

            $customer =  Customer::where('user_id', Auth::id())->first();
            // dd($cart->items);
	        // $customer->orders()->save($order);
            $order->customer_id = $customer->customer_id;
            $order->date_placed = now();
            $order->date_shipped = Carbon::now()->addDays(5);
            // $order->shipvia = $request->shipper_id;
            // $order->shipping = $request->shipping;
            $order->shipping = 10.00  ;
            $order->status = 'Processing';
            $order->save();
            // dd($cart->items);
    	    foreach($cart->items as $items){
        		$id = $items['item']['item_id'];
                // dd($id);
                DB::table('orderline')->insert(
                    ['item_id' => $id, 
                     'orderinfo_id' => $order->orderinfo_id,
                     'quantity' => $items['qty']
                    ]
                    );
        		
                $stock = Stock::find($id);
          		$stock->quantity = $stock->quantity - $items['qty'];
         		$stock->save();
            }
            // dd($order);
        }
        catch (\Exception $e) {
            // dd($e->getMessage());
	        DB::rollback();
            // dd($order);
            return redirect()->route('getCart')->with('error', $e->getMessage());
        }
    
        DB::commit();
        Session::forget('cart');
        return redirect('/')->with('success','Successfully Purchased Your Products!!!');
    }
}
