@extends('admin.layout')

@section('title', 'تعديل توصية')

@section('content')
<div class="admin-head"><h1>تعديل توصية</h1></div>
<div class="admin-card">
    <form method="POST" action="{{ route('admin.preorders.update', $preorder) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.preorders.form', ['button' => 'تحديث'])
    </form>
</div>
@endsection
