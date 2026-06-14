@extends('admin.layout')

@section('title', 'الإشعارات والطلبات')

@section('content')
<style>
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: .78rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .badge-pending  { background: #fff3cd; color: #856404; }
    .badge-new      { background: #d4edda; color: #155724; }
    .badge-reviewed { background: #cce5ff; color: #004085; }
    .badge-completed{ background: #e2e3e5; color: #383d41; }
    .badge-cancelled{ background: #f8d7da; color: #721c24; }

    .row-new td:first-child, .row-pending td:first-child {
        border-right: 3px solid #c9a84c;
    }
    .row-new, .row-pending { background: #fffbf0 !important; }
</style>

<div class="admin-head">
    <h1>الإشعارات والطلبات</h1>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
        <tr>
            <th>رقم الطلب</th>
            <th>الزبون</th>
            <th>الهاتف</th>
            <th>الدفع</th>
            <th>القطع</th>
            <th>الإجمالي</th>
            <th>التاريخ</th>
            <th>الحالة</th>
            <th>إجراءات</th>
        </tr>
        </thead>
        <tbody>
        @forelse($orders as $order)
            @php
                $rowClass   = in_array($order->status, ['new', 'pending_payment']) ? 'row-new' : '';
                $badgeMap   = ['pending_payment' => 'badge-pending', 'new' => 'badge-new', 'reviewed' => 'badge-reviewed', 'completed' => 'badge-completed', 'delivered' => 'badge-completed', 'cancelled' => 'badge-cancelled'];
                $iconMap    = ['pending_payment' => '⏳', 'new' => '🛍️', 'reviewed' => '🔍', 'completed' => '🚚', 'delivered' => '📦', 'cancelled' => '❌'];
                $badgeClass = $badgeMap[$order->status] ?? 'badge-reviewed';
                $badgeIcon  = $iconMap[$order->status]  ?? '';
            @endphp
            <tr class="{{ $rowClass }}">
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->customer_phone }}</td>
                <td>{{ $order->payment_method_label }}</td>
                <td>{{ $order->items_count }}</td>
                <td>{{ number_format($order->total) }}</td>
                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <span class="status-badge {{ $badgeClass }}">
                        {{ $badgeIcon }} {{ $order->status_label }}
                    </span>
                </td>
                <td><a class="btn-admin btn-muted" href="{{ route('admin.orders.show', $order) }}">عرض</a></td>
            </tr>
        @empty
            <tr><td colspan="9">لا توجد طلبات بعد.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top: 16px;">{{ $orders->links() }}</div>
</div>
@endsection
