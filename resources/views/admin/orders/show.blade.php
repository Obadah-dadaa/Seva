@extends('admin.layout')

@section('title', 'تفاصيل الطلب')

@section('content')
@php
    $stepMap = ['pending_payment' => 0, 'new' => 1, 'reviewed' => 2, 'completed' => 3, 'delivered' => 4, 'cancelled' => -1];
    $currentStep = $stepMap[$order->status] ?? 1;
    $isCancelled = $order->status === 'cancelled';
@endphp

<style>
    .adm-stepper { display:flex; align-items:flex-start; justify-content:space-between; position:relative; padding:8px 0 20px; }
    .adm-stepper::before { content:''; position:absolute; top:20px; right:20px; left:20px; height:3px; background:#e8e0d6; z-index:0; }
    .adm-step { display:flex; flex-direction:column; align-items:center; gap:8px; flex:1; position:relative; z-index:1; }
    .adm-dot { width:40px; height:40px; border-radius:50%; background:#e8e0d6; color:#aaa; font-size:1.1rem; display:flex; align-items:center; justify-content:center; border:3px solid #e8e0d6; }
    .adm-step.done .adm-dot { background:#25a244; border-color:#25a244; color:#fff; }
    .adm-step.active .adm-dot { background:#b8860b; border-color:#b8860b; color:#fff; box-shadow:0 0 0 5px rgba(184,134,11,.18); }
    .adm-lbl { font-size:.75rem; font-weight:700; color:#aaa; text-align:center; }
    .adm-step.done .adm-lbl, .adm-step.active .adm-lbl { color:#2c1a0e; }
    .cancelled-banner { display:flex; align-items:center; gap:10px; padding:14px 18px; background:#fff0f0; border:1px solid #f5c6c6; border-radius:10px; color:#c0392b; font-weight:700; font-size:.95rem; margin-bottom:4px; }
</style>

<div class="admin-head">
    <h1>طلب {{ $order->order_number }}</h1>
    <a class="btn-admin btn-muted" href="{{ route('admin.orders.index') }}">رجوع</a>
</div>

<div class="admin-card" style="margin-bottom: 18px;">
    <div class="form-grid">
        <div><strong>الاسم:</strong> {{ $order->customer_name }}</div>
        <div><strong>الهاتف:</strong> {{ $order->customer_phone }}</div>
        <div><strong>المدينة:</strong> {{ $order->customer_city }}</div>
        <div><strong>العنوان:</strong> {{ $order->customer_address }}</div>
        <div><strong>طريقة الدفع:</strong> {{ $order->payment_method_label }}</div>
        @if($order->payment_reference)
            <div><strong>رقم عملية الدفع:</strong> {{ $order->payment_reference }}</div>
        @endif
        <div><strong>تاريخ الطلب:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</div>
        @if($order->customer_notes)
            <div class="form-field full"><strong>ملاحظات:</strong> {{ $order->customer_notes }}</div>
        @endif
    </div>
</div>

<div class="admin-card" style="margin-bottom: 18px;">
    <div class="admin-head" style="margin-bottom: 18px;">
        <h1 style="font-size: 22px;">حالة الطلب</h1>
    </div>

    @if($isCancelled)
        <div class="cancelled-banner">❌ تم إلغاء الطلب</div>
    @else
        <div class="adm-stepper">
            <div class="adm-step {{ $currentStep >= 1 ? ($currentStep > 1 ? 'done' : 'active') : '' }}">
                <div class="adm-dot">🛍️</div>
                <div class="adm-lbl">الطلب</div>
            </div>
            <div class="adm-step {{ $currentStep >= 2 ? ($currentStep > 2 ? 'done' : 'active') : '' }}">
                <div class="adm-dot">🔍</div>
                <div class="adm-lbl">مراجعة</div>
            </div>
            <div class="adm-step {{ $currentStep >= 3 ? ($currentStep > 3 ? 'done' : 'active') : '' }}">
                <div class="adm-dot">🚚</div>
                <div class="adm-lbl">شحن</div>
            </div>
            <div class="adm-step {{ $currentStep >= 4 ? 'done' : '' }}">
                <div class="adm-dot">📦</div>
                <div class="adm-lbl">تسليم</div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="actions" style="margin-top: 8px;">
        @csrf
        @method('PUT')
        <select name="status" style="max-width: 280px;">
            @if($order->status === 'pending_payment')
                <option value="pending_payment" selected disabled>⏳ بانتظار تأكيد الدفع</option>
            @endif
            <option value="new"       {{ $order->status === 'new'       ? 'selected' : '' }}>🛍️ جديد / تم تأكيد الدفع</option>
            <option value="reviewed"  {{ $order->status === 'reviewed'  ? 'selected' : '' }}>🔍 تمت المراجعة</option>
            <option value="completed"  {{ $order->status === 'completed'  ? 'selected' : '' }}>🚚 جاري الشحن</option>
            <option value="delivered"  {{ $order->status === 'delivered'  ? 'selected' : '' }}>📦 تم التسليم للزبون</option>
            <option value="cancelled"  {{ $order->status === 'cancelled'  ? 'selected' : '' }}>❌ ملغي</option>
        </select>
        <button class="btn-admin" type="submit">تحديث الحالة</button>
    </form>
</div>

@if($order->statusLogs->isNotEmpty())
<div class="admin-card" style="margin-bottom:18px;">
    <div class="admin-head" style="margin-bottom:14px;">
        <h1 style="font-size:22px;">سجل الحالات</h1>
    </div>
    <div style="display:flex;flex-direction:column;gap:0;">
        @foreach($order->statusLogs as $log)
        <div style="display:flex;gap:14px;align-items:flex-start;position:relative;padding-bottom:{{ !$loop->last ? '16px' : '0' }};">
            @if(!$loop->last)
            <div style="position:absolute;right:19px;top:36px;bottom:0;width:2px;background:#e8e0d6;"></div>
            @endif
            <div style="width:40px;height:40px;border-radius:50%;background:#f7f3ee;border:2px solid #e8e0d6;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">{{ $log->icon }}</div>
            <div style="padding-top:8px;">
                <div style="font-size:.9rem;font-weight:700;color:#2c2a25;">{{ $log->status_label }}</div>
                <div style="font-size:.78rem;color:#aaa;margin-top:2px;">{{ $log->created_at->format('Y-m-d H:i') }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="admin-card">
    <table class="admin-table">
        <thead>
        <tr>
            <th>الصورة</th>
            <th>القطعة</th>
            <th>الصنف</th>
            <th>اللون</th>
            <th>المقاس</th>
            <th>الكمية</th>
            <th>السعر</th>
            <th>الإجمالي</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td><img src="{{ $item->public_image }}" alt="{{ $item->product_name }}"></td>
                <td>{{ $item->product_name }}<br><small>{{ $item->product_type }}</small></td>
                <td>{{ $item->category_name }}</td>
                <td>{{ $item->color ?: '-' }}</td>
                <td>{{ $item->size ?: '-' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price) }}</td>
                <td>{{ number_format($item->subtotal) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th colspan="6">الإجمالي</th>
            <th>{{ number_format($order->total) }}</th>
        </tr>
        </tfoot>
    </table>
</div>
@endsection
