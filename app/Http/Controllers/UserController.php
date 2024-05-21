<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Item;
use App\DataTables\OrderDataTable;
use App\DataTables\UsersDataTable;
use Barryvdh\Debugbar\Facades\Debugbar as FacadesDebugbar;
use DB;
use Debugbar;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UserImport;

class UserController extends Controller
{
    public function register()
    {
        return view('user.register');
    }

    public function postSignup(Request $request)
    {
        $this->validate($request, [
            'email' => 'email| required| unique:users',
            'password' => 'required| min:4'
        ]);
        $user = new User([
            'name' => $request->fname . ' ' . $request->lname,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        $customer = new Customer;
        $customer->user_id = $user->id;
        $customer->fname = $request->fname;
        $customer->lname = $request->lname;
        $customer->addressline = $request->addressline;
        $customer->phone = $request->phone;
        $customer->zipcode = $request->zipcode;
        $customer->save();
        Auth::login($user);
        return redirect()->route('user.register')->with('success', 'you are registered');
    }

    public function login()
    {
        return view('user.login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function postSignin(Request $request)
    {
        $this->validate($request, [
            'email' => 'email| required',
            'password' => 'required| min:4'
        ]);

        if (auth()->attempt(array('email' => $request->email, 'password' => $request->password))) {

            return redirect('/');
        } else {
            return redirect()->route('user.login')
                ->with('error', 'Email-Address And Password Are Wrong.');
        }
    }

    public function getProfile(OrderDataTable $dataTable)
    {
        
        // $user = User::find(3)->customer;
        // Debugbar::info($user->lname);
        // dd($user->lname, $user->addressline);
        // ->lname;
        // $user_orders = Customer::has('orders')->get();
        // $customer = Customer::find(3)->user->email;
        // $customer_orders = Customer::find(2)->orders;
        // Debugbar::info($customer_orders);
        // // // dd($customer_orders);
        // foreach($customer_orders as $orders) {
        //     dump($orders->orderinfo_id, $orders->date_placed);
        // }
        // $order = Order::find(8)->customer;
        // // dd($order);
        // Debugbar::info($order);
        // $orders = Order::find(3)->items;
        
        // $orders = Order::with('customer')->get();
        // foreach($orders as $order) {
        //     dump($order->customer->fname);
        // }
        // $items = Order::with('items')->get();
        // Debugbar::info($items);
        // foreach($items as $item) {
        //     Debugbar::info($item->description, $item->sell_price);
            
        // }
       
        // dd($items);
        // $customer = Customer::where('user_id', Auth::id())->first();
        // $orders = DB::table('customer as c')
        //     ->join('orderinfo as o', 'o.customer_id', '=', 'c.customer_id')
        //     ->join('orderline as ol', 'o.orderinfo_id', '=', 'ol.orderinfo_id')
        //     ->join('item as i', 'ol.item_id', '=', 'i.item_id')
        //     ->where('c.user_id', Auth::id())
        //     ->select('o.orderinfo_id', 'o.date_placed', 'o.status', DB::raw("SUM(ol.quantity * i.sell_price) as total"))
        //     ->groupBy('o.orderinfo_id', 'o.date_placed', 'o.status')->get();
        // // dd($orders);
        // // return view('user.profile',compact('orders'));
        // $customer_orders = Order::all();
        // $customer_orders = Order::with('customer')->get();
        // Debugbar::info($customer_orders);
        // foreach($customer_orders as $order) {

        //     Debugbar::info($order->customer->customer_id);
        // }
        

        return $dataTable->render('user.profile');
        // return 'user profile';
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
            new UserImport(),
            request()
                ->file('customer_upload')
                ->storeAs(
                    'files',
                    request()
                        ->file('customer_upload')
                        ->getClientOriginalName()
                )
        );
        return redirect()->back()->with('success', 'Excel user file Imported Successfully');
    }
}
