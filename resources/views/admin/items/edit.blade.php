@extends('admin.layout')

@section('title', 'تعديل منتج')

@section('content')
<div class="admin-head"><h1>تعديل منتج</h1></div>
<div class="admin-card">
    <form method="POST" action="{{ route('admin.items.update', $item) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.items.form', ['button' => 'تحديث'])
    </form>
</div>
@endsection
