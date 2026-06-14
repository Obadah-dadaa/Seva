@extends('admin.layout')

@section('title', 'تسجيل الدخول')

@section('content')
<div class="admin-card" style="max-width: 460px; margin: 40px auto;">
    <div class="admin-head">
        <h1>دخول الأدمن</h1>
    </div>
    <form method="POST" action="{{ route('admin.login.post') }}" class="form-grid">
        @csrf
        <div class="form-field full">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="form-field full">
            <label>كلمة المرور</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-field full check-row">
            <label><input type="checkbox" name="remember" value="1"> تذكرني</label>
        </div>
        <div class="form-field full">
            <button class="btn-admin" type="submit">دخول</button>
        </div>
    </form>
</div>
@endsection
