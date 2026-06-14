<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>تتبع الطلب {{ $order->order_number }} — SEVA</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('styles.css') }}">
<style>
  body { background: #f7f3ee; min-height: 100vh; }

  .track-wrap {
    max-width: 620px;
    margin: 0 auto;
    padding: 24px 16px 60px;
  }

  .track-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 28px;
  }

  .track-logo {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: contain;
    background: #fff;
    padding: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,.1);
  }

  .track-brand { font-size: 1.3rem; font-weight: 900; color: #2c1a0e; }

  .track-card {
    background: #fff;
    border-radius: 18px;
    padding: 24px;
    margin-bottom: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
  }

  .track-card-title {
    font-size: .8rem;
    font-weight: 700;
    color: #b8860b;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 16px;
  }

  /* ===== ORDER META ===== */
  .track-meta { display: flex; flex-wrap: wrap; gap: 8px 24px; }
  .track-meta-item { font-size: .9rem; color: #555; }
  .track-meta-item strong { color: #2c1a0e; }
  .track-order-num {
    font-size: 1.1rem;
    font-weight: 900;
    color: #2c1a0e;
    margin-bottom: 8px;
    letter-spacing: .5px;
  }

  /* ===== STATUS STEPPER ===== */
  .stepper {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    position: relative;
    padding: 8px 0;
  }

  .stepper::before {
    content: '';
    position: absolute;
    top: 20px;
    right: 20px;
    left: 20px;
    height: 3px;
    background: #e8e0d6;
    z-index: 0;
  }

  .step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    flex: 1;
    position: relative;
    z-index: 1;
  }

  .step-dot {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e8e0d6;
    color: #aaa;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid #e8e0d6;
    transition: all .3s;
  }

  .step.done .step-dot {
    background: #25a244;
    border-color: #25a244;
    color: #fff;
  }

  .step.active .step-dot {
    background: #b8860b;
    border-color: #b8860b;
    color: #fff;
    box-shadow: 0 0 0 4px rgba(184,134,11,.2);
  }

  .step-label {
    font-size: .75rem;
    font-weight: 700;
    color: #aaa;
    text-align: center;
  }

  .step.done .step-label,
  .step.active .step-label { color: #2c1a0e; }

  /* ===== ITEMS TABLE ===== */
  .items-list { display: flex; flex-direction: column; gap: 12px; }

  .order-item-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f0ebe4;
    gap: 12px;
  }

  .order-item-row:last-child { border-bottom: none; padding-bottom: 0; }

  .order-item-img {
    width: 56px;
    height: 56px;
    border-radius: 10px;
    object-fit: cover;
    flex-shrink: 0;
    background: #f7f3ee;
  }

  .order-item-info { flex: 1; }
  .order-item-name { font-size: .9rem; font-weight: 700; color: #2c1a0e; }
  .order-item-meta { font-size: .78rem; color: #888; margin-top: 2px; }

  .order-item-price {
    font-size: .9rem;
    font-weight: 700;
    color: #2c1a0e;
    white-space: nowrap;
  }

  .track-total-row {
    display: flex;
    justify-content: space-between;
    padding-top: 14px;
    border-top: 2px solid #f0ebe4;
    font-weight: 700;
    font-size: 1rem;
    color: #2c1a0e;
  }

  /* ===== CUSTOMER INFO ===== */
  .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
  .info-item label { font-size: .75rem; color: #aaa; font-weight: 600; display: block; margin-bottom: 3px; }
  .info-item span { font-size: .9rem; color: #2c1a0e; font-weight: 600; }

  /* ===== WHATSAPP BTN ===== */
  .btn-wa-track {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 15px;
    background: #25d366;
    color: #092e18;
    border: none;
    border-radius: 14px;
    font-size: 1rem;
    font-weight: 700;
    font-family: 'Cairo', sans-serif;
    text-decoration: none;
    cursor: pointer;
    box-shadow: 0 4px 16px rgba(37,211,102,.25);
    transition: transform .2s, box-shadow .2s;
  }

  .btn-wa-track:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(37,211,102,.35);
  }

  .btn-back {
    display: block;
    text-align: center;
    margin-top: 14px;
    color: #b8860b;
    font-size: .9rem;
    font-weight: 600;
    text-decoration: none;
  }

  .btn-back:hover { text-decoration: underline; }

  /* ===== TIMELINE ===== */
  .timeline { display:flex; flex-direction:column; gap:0; }
  .timeline-item { display:flex; gap:14px; align-items:flex-start; position:relative; padding-bottom:16px; }
  .timeline-item:last-child { padding-bottom:0; }
  .timeline-item:not(:last-child)::before { content:''; position:absolute; right:19px; top:36px; bottom:0; width:2px; background:#e8e0d6; }
  .tl-icon { width:40px; height:40px; border-radius:50%; background:#f7f3ee; border:2px solid #e8e0d6; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; }
  .tl-body { padding-top:6px; flex:1; }
  .tl-label { font-size:.95rem; font-weight:800; color:#2c1a0e; }
  .tl-desc { font-size:.82rem; color:#7a6a55; margin-top:3px; line-height:1.5; }
  .tl-date { display:inline-flex; align-items:center; gap:5px; font-size:.75rem; color:#b8a898; margin-top:5px; background:#f7f3ee; padding:3px 9px; border-radius:20px; }

  @media (max-width: 480px) {
    .info-grid { grid-template-columns: 1fr; }
    .stepper::before { right: 16px; left: 16px; }
  }
</style>
</head>
<body>

<div class="track-wrap">

  {{-- Header --}}
  <div class="track-header">
    <img src="{{ asset('seva-logo-transparent.png') }}" alt="SEVA" class="track-logo">
    <span class="track-brand">SEVA</span>
  </div>

  {{-- Order Number & Date --}}
  @php
    $arMonths = ['يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'];
    $arDate = function($dt, $withTime = false) use ($arMonths) {
        $str = $dt->day . ' ' . $arMonths[$dt->month - 1] . ' ' . $dt->year;
        if ($withTime) $str .= ' — الساعة ' . $dt->format('H:i');
        return $str;
    };
    $statusDesc = [
        'pending_payment' => 'وصلنا طلبك! في انتظار تأكيد تحويل الدفع من قِبل فريقنا.',
        'new'             => 'تم تسجيل طلبك بنجاح وسيبدأ فريقنا في مراجعته قريباً.',
        'reviewed'        => 'راجع فريقنا طلبك وأصبح جاهزاً للتجهيز والشحن.',
        'completed'       => 'طلبك الآن مع شركة الشحن وفي طريقه إليك. 🚚',
        'delivered'       => 'وصل طلبك بنجاح. نتمنى أن ينال إعجابك! 💛',
        'cancelled'       => 'تم إلغاء الطلب. تواصل معنا لأي استفسار.',
    ];
  @endphp

  <div class="track-card">
    <div class="track-order-num">{{ $order->order_number }}</div>
    <div class="track-meta">
      <div class="track-meta-item">📅 <strong>{{ $arDate($order->created_at) }}</strong></div>
      <div class="track-meta-item">💳 <strong>{{ $order->payment_method_label }}</strong></div>
      <div class="track-meta-item">💰 <strong>{{ number_format($order->total, 0) }} ج.م</strong></div>
    </div>
  </div>

  {{-- Status Stepper --}}
  @php
    $steps = [
      'pending_payment' => 0,
      'new'             => 1,
      'reviewed'        => 2,
      'completed'       => 3,
      'delivered'       => 4,
      'cancelled'       => -1,
    ];
    $currentStep = $steps[$order->status] ?? 1;
    $isCancelled = $order->status === 'cancelled';
  @endphp

  <div class="track-card">
    <div class="track-card-title">حالة الطلب</div>

    @if($isCancelled)
      <div style="text-align:center; color:#e53e3e; font-weight:700; font-size:1rem; padding:8px 0;">
        ❌ تم إلغاء الطلب
      </div>
    @else
      <div class="stepper">
        <div class="step {{ $currentStep >= 1 ? ($currentStep > 1 ? 'done' : 'active') : '' }}">
          <div class="step-dot">🛍️</div>
          <div class="step-label">الطلب</div>
        </div>
        <div class="step {{ $currentStep >= 2 ? ($currentStep > 2 ? 'done' : 'active') : '' }}">
          <div class="step-dot">🔍</div>
          <div class="step-label">مراجعة</div>
        </div>
        <div class="step {{ $currentStep >= 3 ? ($currentStep > 3 ? 'done' : 'active') : '' }}">
          <div class="step-dot">🚚</div>
          <div class="step-label">شحن</div>
        </div>
        <div class="step {{ $currentStep >= 4 ? 'done' : '' }}">
          <div class="step-dot">📦</div>
          <div class="step-label">تسليم</div>
        </div>
      </div>
    @endif
  </div>

  {{-- Order Items --}}
  <div class="track-card">
    <div class="track-card-title">المنتجات</div>
    <div class="items-list">
      @foreach($order->items as $item)
        <div class="order-item-row">
          <img
            src="{{ $item->public_image }}"
            alt="{{ $item->product_name }}"
            class="order-item-img"
            onerror="this.src='{{ asset('seva-logo-transparent.png') }}'"
          >
          <div class="order-item-info">
            <div class="order-item-name">{{ $item->product_name }}</div>
            <div class="order-item-meta">
              @if($item->size) مقاس: {{ $item->size }} &nbsp;•&nbsp; @endif
              الكمية: {{ $item->quantity }}
            </div>
          </div>
          <div class="order-item-price">{{ number_format($item->subtotal, 0) }} ج.م</div>
        </div>
      @endforeach
    </div>
    <div class="track-total-row">
      <span>الإجمالي</span>
      <span>{{ number_format($order->total, 0) }} ج.م</span>
    </div>
  </div>

  {{-- Customer Info --}}
  <div class="track-card">
    <div class="track-card-title">بيانات التوصيل</div>
    <div class="info-grid">
      <div class="info-item">
        <label>الاسم</label>
        <span>{{ $order->customer_name }}</span>
      </div>
      <div class="info-item">
        <label>الهاتف</label>
        <span>{{ $order->customer_phone }}</span>
      </div>
      <div class="info-item">
        <label>المدينة</label>
        <span>{{ $order->customer_city }}</span>
      </div>
      <div class="info-item">
        <label>العنوان</label>
        <span>{{ $order->customer_address }}</span>
      </div>
      @if($order->customer_notes)
        <div class="info-item" style="grid-column: 1/-1">
          <label>ملاحظات</label>
          <span>{{ $order->customer_notes }}</span>
        </div>
      @endif
    </div>
  </div>

  {{-- Status Timeline --}}
  @if($order->statusLogs->isNotEmpty())
  <div class="track-card">
    <div class="track-card-title">سجل الطلب</div>
    <div class="timeline">
      @foreach($order->statusLogs as $log)
      <div class="timeline-item">
        <div class="tl-icon">{{ $log->icon }}</div>
        <div class="tl-body">
          <div class="tl-label">{{ $log->status_label }}</div>
          @if(isset($statusDesc[$log->status]))
          <div class="tl-desc">{{ $statusDesc[$log->status] }}</div>
          @endif
          <div class="tl-date">📅 {{ $arDate($log->created_at, true) }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif

  {{-- WhatsApp Contact --}}
  @php
    $waLines = ["مرحباً SEVA 👋", "استفسار عن طلبي: {$order->order_number}", "", "المنتجات:"];
    foreach ($order->items as $item) {
        $sizePart = $item->size ? " - مقاس {$item->size}" : '';
        $waLines[] = "• {$item->product_name}{$sizePart}";
        if ($item->public_image) $waLines[] = $item->public_image;
    }
    $waMsg = implode('%0a', array_map('rawurlencode', $waLines));
  @endphp
  <a href="https://wa.me/{{ $whatsappNumber }}?text={{ $waMsg }}" target="_blank" rel="noopener" class="btn-wa-track">
    <svg width="22" height="22" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    تواصل معنا على واتساب
  </a>

  <a href="{{ route('home') }}" class="btn-back">← العودة للمتجر</a>

</div>

@if(!in_array($order->status, ['delivered', 'cancelled']))
<script>
  setTimeout(function() { location.reload(); }, 30000);
</script>
@endif

</body>
</html>
