<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\CustomersDataTable;
use App\DataTables\UsersDataTable;
use App\DataTables\AdminOrdersDataTable;

class DashboardController extends Controller
{
    public function getCustomers(CustomersDataTable $dataTable)
    {
        return $dataTable->render('dashboard.customers');
    }

    public function getUsers(UsersDataTable $dataTable) {
        return $dataTable->render('dashboard.users');
    }

    public function getOrders(AdminOrdersDataTable $dataTable) {
        return $dataTable->render('dashboard.orders');
    }
}
