<?php

namespace App\Http\Controllers;

use App\Models\OrderStatusLog;
use Illuminate\Http\Request;

class CustomerNotificationsController extends Controller
{
    /**
     * Return the customer's recent order-status updates and the unread count.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $orderIds = $user->orders()->pluck('id');

        if ($orderIds->isEmpty()) {
            return response()->json(['count' => 0, 'latest_id' => null, 'latest' => null, 'items' => []]);
        }

        $seenAt = $user->notifications_seen_at;

        $count = OrderStatusLog::whereIn('order_id', $orderIds)
            ->when($seenAt, fn ($q) => $q->where('created_at', '>', $seenAt))
            ->count();

        $logs = OrderStatusLog::whereIn('order_id', $orderIds)
            ->with('order:id,order_number')
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        $items = $logs->map(function (OrderStatusLog $log) use ($seenAt) {
            $orderNumber = optional($log->order)->order_number;

            return [
                'id' => $log->id,
                'order_number' => $orderNumber,
                'message' => $log->customer_message,
                'icon' => $log->icon,
                'time' => optional($log->created_at)->diffForHumans(),
                'track_url' => $orderNumber ? url('/track/' . $orderNumber) : null,
                'unread' => !$seenAt || ($log->created_at && $log->created_at->gt($seenAt)),
            ];
        })->values();

        $latest = $items->first();

        return response()->json([
            'count' => $count,
            'latest_id' => $latest['id'] ?? null,
            'latest' => $latest ? [
                'order_number' => $latest['order_number'],
                'message' => $latest['message'],
                'icon' => $latest['icon'],
            ] : null,
            'items' => $items,
        ]);
    }

    /**
     * Mark all of the customer's notifications as seen.
     */
    public function markSeen(Request $request)
    {
        $request->user()->forceFill(['notifications_seen_at' => now()])->save();

        return response()->json(['count' => 0]);
    }
}
