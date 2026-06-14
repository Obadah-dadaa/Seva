@extends('admin.layout')

@section('title', 'تعديل فئة')

@section('content')
<div class="admin-head"><h1>تعديل فئة</h1></div>
<div class="admin-card">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
        @method('PUT')
        @include('admin.categories.form', ['button' => 'تحديث'])
    </form>
</div>
@endsection
