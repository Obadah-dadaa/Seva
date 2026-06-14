<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\PreorderProduct;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'itemsCount' => Item::count(),
            'activeItemsCount' => Item::where('active', true)->count(),
            'categoriesCount' => Category::count(),
            'preordersCount' => PreorderProduct::count(),
            'newOrdersCount' => Order::whereIn('status', ['new', 'pending_payment'])->count(),
            'latestItems' => Item::with('category')->latest()->take(6)->get(),
        ]);
    }
}
