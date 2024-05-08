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

use DB;

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
        
        $user = User::find(3)->customer;
        // dd($user->lname, $user->addressline);
        // ->lname;
        // $user_orders = Customer::has('orders')->get();
        // $customer = Customer::find(3)->user->email;
        // $customer_orders = Customer::find(2)->orders;
        // foreach($customer_orders as $orders) {
        //     dump($orders->orderinfo_id, $orders->date_placed);
        // }
        // $order = Order::find(8)->customer->lname;
        // $orders = Order::find(3)->items;
        // $orders = Order::all();
        $orders = Order::with('customer')->get();
        foreach($orders as $order) {
            dump($order->customer->fname);
        }
        // $items = Item::find(11)->orders;
        // foreach($items as $order) {
        //     dump($order->date_placed, $order->orderinfo_id, );
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
        // $customers = Customer::all();
        // foreach($customers as $customer) {

        //     dump($customer->orders);
        // }
        return $dataTable->render('user.profile');
        // return 'user profile';
    }
}
