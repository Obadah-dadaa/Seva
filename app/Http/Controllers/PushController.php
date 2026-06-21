<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushController extends Controller
{
    // Admin subscribes their browser
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'endpoint' => ['required', 'string'],
            'p256dh'   => ['required', 'string'],
            'auth'     => ['required', 'string'],
        ]);

        PushSubscription::updateOrCreate(
            ['endpoint' => $data['endpoint']],
            [
                'user_id' => Auth::id(),
                'p256dh'  => $data['p256dh'],
                'auth'    => $data['auth'],
            ]
        );

        return response()->json(['ok' => true]);
    }

    // Admin unsubscribes
    public function unsubscribe(Request $request)
    {
        $endpoint = $request->input('endpoint');
        if ($endpoint) {
            PushSubscription::where('endpoint', $endpoint)
                ->where('user_id', Auth::id())
                ->delete();
        }
        return response()->json(['ok' => true]);
    }

    // User's SW fetches this after receiving a ping — returns their latest order status
    public function userLatest()
    {
        if (!Auth::check() || Auth::user()->is_admin) {
            return response()->json(null);
        }

        $order = \App\Models\Order::where('customer_id', Auth::id())
            ->whereNotIn('status', ['new', 'pending_payment'])
            ->latest('updated_at')
            ->first();

        if (!$order) {
            return response()->json(null);
        }

        return response()->json([
            'title' => '📦 تحديث طلبك — SEVA',
            'body'  => 'طلب ' . $order->order_number . ': ' . $order->status_label,
            'url'   => route('orders.track', $order->order_number),
            'tag'   => 'seva-order-' . $order->id . '-' . $order->status,
        ]);
    }

    // Admin/Service Worker fetches this after receiving a push ping
    public function latest()
    {
        $order = \App\Models\Order::whereIn('status', ['new', 'pending_payment'])
            ->latest()
            ->first();

        if (!$order) {
            return response()->json(['title' => 'SEVA', 'body' => 'لديك طلبات جديدة']);
        }

        return response()->json([
            'title' => '🛍️ طلب جديد — SEVA',
            'body'  => 'رقم ' . $order->order_number . ' · ' . number_format($order->total, 0) . ' ج.م',
            'url'   => route('admin.orders.index'),
            'tag'   => 'seva-order-' . $order->id,
        ]);
    }
}
