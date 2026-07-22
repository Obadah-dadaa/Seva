<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>طلباتي — SEVA</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('styles.css') }}">
<style>
  body { background:#f7f3ee; min-height:100vh; }
  .orders-wrap { max-width:640px; margin:0 auto; padding:24px 16px 60px; }

  .orders-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; gap:12px; flex-wrap:wrap; }
  .orders-brand { display:flex; align-items:center; gap:10px; }
  .orders-brand img { width:42px; height:42px; border-radius:50%; object-fit:contain; background:#fff; padding:4px; box-shadow:0 2px 8px rgba(0,0,0,.1); }
  .orders-brand span { font-size:1.3rem; font-weight:900; color:#2c1a0e; }
  .orders-user { font-size:.85rem; color:#888; font-weight:600; }

  .orders-title { font-size:1.4rem; font-weight:900; color:#2c1a0e; margin-bottom:4px; }
  .orders-count { font-size:.85rem; color:#aaa; margin-bottom:20px; }

  .order-card { background:#fff; border-radius:16px; padding:20px; margin-bottom:14px; box-shadow:0 2px 10px rgba(0,0,0,.05); text-decoration:none; display:block; border:1.5px solid transparent; transition:border-color .2s, box-shadow .2s; }
  .order-card:hover { border-color:#c9a84c; box-shadow:0 4px 18px rgba(0,0,0,.09); }

  .order-card-top { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:12px; }
  .order-num { font-size:1rem; font-weight:900; color:#2c1a0e; }
  .order-date { font-size:.78rem; color:#aaa; margin-top:3px; }

  .status-badge { display:inline-flex; align-items:center; gap:5px; padding:5px 12px; border-radius:20px; font-size:.78rem; font-weight:700; white-space:nowrap; }
  .badge-pending  { background:#fff3cd; color:#856404; }
  .badge-new      { background:#d4edda; color:#155724; }
  .badge-reviewed { background:#cce5ff; color:#004085; }
  .badge-completed{ background:#e8f4fd; color:#0c5460; }
  .badge-delivered{ background:#d4edda; color:#155724; }
  .badge-cancelled{ background:#f8d7da; color:#721c24; }

  .order-card-meta { display:flex; gap:20px; font-size:.85rem; color:#666; flex-wrap:wrap; }
  .order-card-meta strong { color:#2c1a0e; }

  .order-items-preview { margin-top:12px; display:flex; gap:8px; flex-wrap:wrap; }
  .item-chip { background:#f7f3ee; border-radius:8px; padding:4px 10px; font-size:.78rem; color:#555; font-weight:600; }

  .order-card-arrow { color:#c9a84c; font-size:1.1rem; flex-shrink:0; margin-top:2px; }

  .empty-state { text-align:center; padding:60px 20px; color:#aaa; }
  .empty-state .empty-icon { font-size:3rem; margin-bottom:12px; }
  .empty-state p { font-size:.95rem; font-weight:600; margin-bottom:20px; }

  .btn-gold { display:inline-flex; align-items:center; justify-content:center; padding:12px 24px; background:linear-gradient(135deg,#c9a84c,#8b6a2e); color:#fff; border-radius:14px; font:700 .9rem Cairo,sans-serif; text-decoration:none; }

  .footer-links { display:flex; justify-content:space-between; align-items:center; margin-top:20px; flex-wrap:wrap; gap:10px; }
  .link-muted { color:#b8860b; font-size:.85rem; font-weight:600; text-decoration:none; }
  .link-muted:hover { text-decoration:underline; }

  .logout-form { display:inline; }
  .btn-logout { background:none; border:none; color:#c0392b; font:600 .85rem Cairo,sans-serif; cursor:pointer; padding:0; }
  .btn-logout:hover { text-decoration:underline; }
</style>
</head>
<body>

<div class="orders-wrap">

  <div class="orders-header">
    <div class="orders-brand">
      <img src="{{ asset('seva-logo-transparent.png') }}" alt="SEVA">
      <span>SEVA</span>
    </div>
    <div class="orders-user">👤 {{ auth()->user()->name }}</div>
  </div>

  <div class="orders-title">طلباتي</div>
  <div class="orders-count">{{ $orders->count() }} {{ $orders->count() === 1 ? 'طلب' : 'طلبات' }}</div>

  @forelse($orders as $order)
    @php
      $badgeClass = [
        'pending_payment' => 'badge-pending',
        'new'             => 'badge-new',
        'reviewed'        => 'badge-reviewed',
        'completed'       => 'badge-completed',
        'delivered'       => 'badge-delivered',
        'cancelled'       => 'badge-cancelled',
      ][$order->status] ?? 'badge-new';

      $badgeIcon = [
        'pending_payment' => '⏳',
        'new'             => '🛍️',
        'reviewed'        => '🔍',
        'completed'       => '🚚',
        'delivered'       => '📦',
        'cancelled'       => '❌',
      ][$order->status] ?? '';
    @endphp

    <a href="{{ route('orders.track', $order->order_number) }}" class="order-card">
      <div class="order-card-top">
        <div>
          <div class="order-num">{{ $order->order_number }}</div>
          <div class="order-date">{{ $order->created_at->format('d M Y — H:i') }}</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
          <span class="status-badge {{ $badgeClass }}">{{ $badgeIcon }} {{ $order->status_label }}</span>
          <span class="order-card-arrow">←</span>
        </div>
      </div>

      <div class="order-card-meta">
        <span>💰 <strong>{{ number_format($order->total, 0) }} ج.م</strong></span>
        <span>📦 <strong>{{ $order->items->count() }}</strong> {{ $order->items->count() === 1 ? 'منتج' : 'منتجات' }}</span>
        <span>💳 {{ $order->payment_method_label }}</span>
      </div>

      @if($order->items->isNotEmpty())
      <div class="order-items-preview">
        @foreach($order->items->take(3) as $item)
          <span class="item-chip">{{ $item->product_name }}{{ $item->color ? ' / '.$item->color : '' }}{{ $item->size ? ' / '.$item->size : '' }}</span>
        @endforeach
        @if($order->items->count() > 3)
          <span class="item-chip">+{{ $order->items->count() - 3 }} أخرى</span>
        @endif
      </div>
      @endif
    </a>

  @empty
    <div class="empty-state">
      <div class="empty-icon">🛍️</div>
      <p>لا توجد طلبات بعد</p>
      <a href="{{ route('home') }}" class="btn-gold">تسوّق الآن</a>
    </div>
  @endforelse

  <div class="footer-links">
    <a href="{{ route('home') }}" class="link-muted">← العودة للمتجر</a>
    <form method="POST" action="{{ route('customer.logout') }}" class="logout-form">
      @csrf
      <button type="submit" class="btn-logout">تسجيل الخروج</button>
    </form>
  </div>

</div>

</body>
</html>
