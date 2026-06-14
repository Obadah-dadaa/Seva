@extends('admin.layout')

@section('title', 'المنتجات')

@section('content')
<div class="admin-head">
    <h1>المنتجات</h1>
    <a class="btn-admin" href="{{ route('admin.items.create') }}">إضافة منتج</a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
        <tr>
            <th>الصورة</th>
            <th>الاسم</th>
            <th>الفئة</th>
            <th>السعر</th>
            <th>المخزون</th>
            <th>المقاسات</th>
            <th>الحالة</th>
            <th>إجراءات</th>
        </tr>
        </thead>
        <tbody>
        @forelse($items as $item)
            <tr>
                <td><img src="{{ $item->public_image }}" alt="{{ $item->name }}"></td>
                <td>{{ $item->name }}</td>
                <td>{{ optional($item->category)->name }}</td>
                <td>{{ number_format($item->price) }}</td>
                <td>
                    @if($item->stock === 0)
                        <strong style="color:#c0392b">{{ number_format($item->stock) }}</strong>
                    @elseif($item->stock === 1)
                        <span style="color:#e67e22;font-weight:700;">⚠️ {{ number_format($item->stock) }}</span>
                    @else
                        <strong style="color:#8b6a2e">{{ number_format($item->stock) }}</strong>
                    @endif
                </td>
                <td>{{ implode(', ', $item->sizes ?: []) }}</td>
                <td>{{ $item->active ? 'مفعل' : 'مخفي' }}</td>
                <td>
                    <div class="actions">
                        <a class="btn-admin btn-muted" href="{{ route('admin.items.edit', $item) }}">تعديل</a>
                        <form method="POST" action="{{ route('admin.items.destroy', $item) }}" onsubmit="return confirm('حذف المنتج؟')">
                            @csrf
                            @method('DELETE')
                            <button class="btn-admin btn-muted btn-danger" type="submit">حذف</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="8">لا يوجد منتجات بعد.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top: 16px;">{{ $items->links() }}</div>
</div>
@endsection
