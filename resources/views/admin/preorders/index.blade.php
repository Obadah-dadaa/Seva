@extends('admin.layout')

@section('title', 'التواصي')

@section('content')
<div class="admin-head">
    <h1>التواصي</h1>
    <a class="btn-admin" href="{{ route('admin.preorders.create') }}">إضافة توصية</a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
        <tr>
            <th>الصورة</th>
            <th>الاسم</th>
            <th>النوع</th>
            <th>السعر</th>
            <th>مدة التنفيذ</th>
            <th>الكمية</th>
            <th>الترتيب</th>
            <th>الحالة</th>
            <th>إجراءات</th>
        </tr>
        </thead>
        <tbody>
        @forelse($preorders as $preorder)
            <tr>
                <td><img src="{{ $preorder->public_image }}" alt="{{ $preorder->name }}"></td>
                <td>{{ $preorder->name }}</td>
                <td>{{ $preorder->type }}</td>
                <td>{{ $preorder->price_note }}</td>
                <td>{{ $preorder->estimated_delivery }}</td>
                <td>{{ number_format($preorder->quantity ?: 1) }}</td>
                <td>{{ $preorder->sort_order }}</td>
                <td>{{ $preorder->active ? 'مفعل' : 'مخفي' }}</td>
                <td>
                    <div class="actions">
                        <a class="btn-admin btn-muted" href="{{ route('admin.preorders.edit', $preorder) }}">تعديل</a>
                        <form method="POST" action="{{ route('admin.preorders.destroy', $preorder) }}" onsubmit="return confirm('حذف هذه التوصية؟')">
                            @csrf
                            @method('DELETE')
                            <button class="btn-admin btn-muted btn-danger" type="submit">حذف</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="9">لا توجد تواصي بعد.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top: 16px;">{{ $preorders->links() }}</div>
</div>
@endsection
