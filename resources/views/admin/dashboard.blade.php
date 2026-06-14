@extends('admin.layout')

@section('title', 'الرئيسية')

@section('content')
<div class="admin-head">
    <h1>الرئيسية</h1>
    <div class="actions">
        <a class="btn-admin" href="{{ route('admin.items.create') }}">إضافة منتج</a>
        <a class="btn-admin btn-muted" href="{{ route('admin.preorders.create') }}">إضافة توصية</a>
    </div>
</div>

<div class="stats-grid">
    <div class="admin-card stat"><strong>{{ $itemsCount }}</strong><span>كل المنتجات</span></div>
    <div class="admin-card stat"><strong>{{ $activeItemsCount }}</strong><span>منتجات مفعلة</span></div>
    <div class="admin-card stat"><strong>{{ $newOrdersCount }}</strong><span>طلبات جديدة</span></div>
    <div class="admin-card stat"><strong>{{ $preordersCount }}</strong><span>التواصي</span></div>
    <div class="admin-card stat"><strong>{{ $categoriesCount }}</strong><span>الفئات</span></div>
</div>

<div class="admin-card">
    <div class="admin-head">
        <h1 style="font-size: 22px;">آخر المنتجات</h1>
        <a class="btn-admin btn-muted" href="{{ route('admin.items.index') }}">عرض الكل</a>
    </div>
    <table class="admin-table">
        <thead>
        <tr><th>الصورة</th><th>الاسم</th><th>الفئة</th><th>السعر</th><th>الحالة</th></tr>
        </thead>
        <tbody>
        @forelse($latestItems as $item)
            <tr>
                <td><img src="{{ $item->public_image }}" alt="{{ $item->name }}"></td>
                <td>{{ $item->name }}</td>
                <td>{{ optional($item->category)->name }}</td>
                <td>{{ number_format($item->price) }}</td>
                <td>{{ $item->active ? 'مفعل' : 'مخفي' }}</td>
            </tr>
        @empty
            <tr><td colspan="5">لا يوجد منتجات بعد.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
