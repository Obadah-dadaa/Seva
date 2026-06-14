<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class CustomerOrdersController extends Controller
{
    public function index()
    {
        $orders = Auth::user()
            ->orders()
            ->with('items')
            ->latest()
            ->get();

        return view('customer.orders', compact('orders'));
    }
}
