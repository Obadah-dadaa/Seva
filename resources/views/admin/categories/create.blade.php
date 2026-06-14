@extends('admin.layout')

@section('title', 'إضافة فئة')

@section('content')
<div class="admin-head"><h1>إضافة فئة</h1></div>
<div class="admin-card">
    <form method="POST" action="{{ route('admin.categories.store') }}">
        @include('admin.categories.form', ['button' => 'حفظ'])
    </form>
</div>
@endsection
