@extends('admin.layout')

@section('title', 'الفئات')

@section('content')
<div class="admin-head">
    <h1>الفئات</h1>
    <a class="btn-admin" href="{{ route('admin.categories.create') }}">إضافة فئة</a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
        <tr><th>الاسم</th><th>الرابط</th><th>الحالة</th><th>إجراءات</th></tr>
        </thead>
        <tbody>
        @forelse($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>{{ $category->active ? 'مفعلة' : 'مخفية' }}</td>
                <td>
                    <div class="actions">
                        <a class="btn-admin btn-muted" href="{{ route('admin.categories.edit', $category) }}">تعديل</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('حذف الفئة؟')">
                            @csrf
                            @method('DELETE')
                            <button class="btn-admin btn-muted btn-danger" type="submit">حذف</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">لا يوجد فئات بعد.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top: 16px;">{{ $categories->links() }}</div>
</div>
@endsection
