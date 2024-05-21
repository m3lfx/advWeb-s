<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Storage;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     // $customer = Customer::find(1);
    //     // // dump($customer->orders);
    //     // foreach($customer->orders as $order) {
    //     //     dump($order->orderinfo_id,$order->date_placed);
    //     // }

    //     // $order = Order::find(1);
    //     // dump($order->customer->title, $order->customer->fname, $order->customer->addressline);
    //     // $user = User::find(5)->customer;
    //     // dump($user->lname, $user->fname);
    //     // $user = Customer::find(17)->user;
    //     // dump($user->name, $user->email);
    //     // $customers = Order::find(1)->customers;
    //     // // dump($customers);
    //     // foreach($customers as $customer) {
    //     //     dump($customer->description, $customer->sell_price);
    //     // }

    //     // $orders = customer::find(4);
    //     // $customers = customer::all();
    //     // dump($customers->orders());
    //     // foreach($customers as $customer) {
    //     //     dump($customer->description, $customer->sell_price);
    //     // }
    //     // foreach($customers as $customer) {
    //     //     // dump($customer->orders);
    //     //     foreach($customer->orders as $order) {
    //     //         dump($order->orderinfo_id);
    //     //     }
    //     // }

    //     //  $orders = Order::all();
    //     // // dump($orders->customers);
    //     // foreach($orders as $order) {
    //     //     dump($order->customers);
    //     // }
    //     // $customers = Customer::with('orders')->get();
    //     // dump($customers);
    //     // foreach($customers as $customer){
    //     //     dump($customer->lname);
    //     //     foreach($customer->orders as $order) {
    //     //         dump($order->orderinfo_id, $order->date_placed);
    //     //     }
    //     // }

    //     $orders = Order::with(['customer','customers'])->where('orderinfo_id', 1)->get();
    //     dump($orders);
    //     foreach($orders as $order){
    //         dump($order->customer->customer_id, $order->customer->lname);
    //         foreach($order->customers as $customer){
    //             dump($customer->description, $customer->sell_price);
    //         }
            
    //     }
    // }

    public function index()
    {
        $data = Customer::orderBy('customer_id', 'DESC')->get();
        
        return response()->json($data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User([
            'name' => $request->fname . ' ' . $request->lname,
            'email' => $request->email,
            'password' => bcrypt($request->input('password')),
        ]);
        $user->save();
        $customer = new Customer();
        $customer->user_id = $user->id;
        $customer->title = $request->title;
        $customer->lname = $request->lname;
        $customer->fname = $request->fname;
        $customer->addressline = $request->addressline;
        $customer->town = $request->town;
        $customer->zipcode = $request->zipcode;
        $customer->phone = $request->phone;
        $files = $request->file('uploads');
        $customer->image_path = 'storage/images/' . $files->getClientOriginalName();
        $customer->save();
        $data = ['status' => 'saved'];
        Storage::put(
            'public/images/' . $files->getClientOriginalName(),
            file_get_contents($files)
        );

        return response()->json([
            "success" => "customer created successfully.",
            "customer" => $customer,
            "status" => 200
        ]);
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
        $data = Customer::find($id);
        return response()->json($data);
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
        
        $customer = Customer::find($id);
        $customer->title = $request->title;
        $customer->lname = $request->lname;
        $customer->fname = $request->fname;
        $customer->addressline = $request->addressline;
        $customer->town = $request->town;
        $customer->zipcode = $request->zipcode;
        $customer->phone = $request->phone;
        $files = $request->file('uploads');
        $customer->image_path = 'storage/images/' . $files->getClientOriginalName();
        $customer->save();
        $data = ['status' => 'saved'];
        Storage::put(
            'public/images/' . $files->getClientOriginalName(),
            file_get_contents($files)
        );

        return response()->json([
            "success" => "customer created successfully.",
            "customer" => $customer,
            "status" => 200
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
		$data = array('success' => 'deleted','code'=>200);
        return response()->json($data);
    }

    public function import()
    {
        // php artisan make:import ItemImport --model=Item
        // $request->validate([
        //     'item_upload' => [
        //         'required',
        //         new ItemExcelRule($request->file('item_upload')),
        //     ],
        // ]);
        Excel::import(
            new ItemStockImport(),
            request()
                ->file('item_upload')
                ->storeAs(
                    'files',
                    request()
                        ->file('item_upload')
                        ->getClientOriginalName()
                )
        );
        return redirect()->back()->with('success', 'Excel file Imported Successfully');
    }
}
