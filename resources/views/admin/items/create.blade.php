@extends('admin.layout')

@section('title', 'إضافة منتج')

@section('content')
<div class="admin-head"><h1>إضافة منتج</h1></div>
<div class="admin-card">
    <form method="POST" action="{{ route('admin.items.store') }}" enctype="multipart/form-data">
        @include('admin.items.form', ['button' => 'حفظ'])
    </form>
</div>
@endsection
