<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\WebPushService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function notifications()
    {
        $afterId = (int) request('after_id', 0);
        $query = Order::whereIn('status', ['new', 'pending_payment']);
        $latest = (clone $query)->latest()->first();
        $count = (clone $query)->when($afterId > 0, function ($orders) use ($afterId) {
            $orders->where('id', '>', $afterId);
        })->count();

        return response()->json([
            'count' => $count,
            'latest_id' => optional($latest)->id,
            'latest_number' => optional($latest)->order_number,
            'latest_status' => optional($latest)->status_label,
        ]);
    }

    public function index()
    {
        $orders = Order::withCount('items')->latest()->paginate(12);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items', 'statusLogs']);

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending_payment,new,reviewed,completed,delivered,cancelled'],
        ]);

        $order->update([
            'status' => $data['status'],
            'reviewed_at' => $data['status'] === 'new' ? null : ($order->reviewed_at ?: now()),
        ]);

        $order->statusLogs()->create(['status' => $data['status'], 'created_at' => now()]);

        // Push notification to the customer
        if ($order->user_id) {
            try {
                (new WebPushService())->notifyUser(
                    $order->user_id,
                    '📦 تحديث طلبك — SEVA',
                    'طلب ' . $order->order_number . ': ' . $order->status_label,
                    url('/track/' . $order->order_number),
                );
            } catch (\Throwable $e) {}
        }

        return back()->with('success', 'تم تحديث حالة الطلب.');
    }
}
