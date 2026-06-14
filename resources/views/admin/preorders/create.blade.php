@extends('admin.layout')

@section('title', 'إضافة توصية')

@section('content')
<div class="admin-head"><h1>إضافة توصية</h1></div>
<div class="admin-card">
    <form method="POST" action="{{ route('admin.preorders.store') }}" enctype="multipart/form-data">
        @include('admin.preorders.form', ['button' => 'حفظ'])
    </form>
</div>
@endsection
