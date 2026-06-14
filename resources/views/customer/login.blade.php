<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>تسجيل الدخول — SEVA</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('styles.css') }}">
<style>
  body { background:#f7f3ee; min-height:100vh; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:24px 16px; }
  .auth-box { background:#fff; border-radius:20px; padding:36px 32px; width:100%; max-width:400px; box-shadow:0 4px 24px rgba(0,0,0,.08); }
  .auth-logo { display:flex; align-items:center; gap:10px; justify-content:center; margin-bottom:28px; }
  .auth-logo img { width:46px; height:46px; border-radius:50%; object-fit:contain; background:#f7f3ee; padding:4px; }
  .auth-logo span { font-size:1.4rem; font-weight:900; color:#2c1a0e; }
  .auth-title { font-size:1.2rem; font-weight:900; color:#2c1a0e; text-align:center; margin-bottom:6px; }
  .auth-sub { font-size:.85rem; color:#aaa; text-align:center; margin-bottom:24px; }
  .field { display:flex; flex-direction:column; gap:6px; margin-bottom:16px; }
  .field label { font-size:.82rem; font-weight:700; color:#555; }
  .field input { border:1.5px solid #e8e0d6; border-radius:12px; padding:12px 14px; font:600 .95rem Cairo,sans-serif; color:#2c1a0e; outline:none; transition:border-color .2s; }
  .field input:focus { border-color:#c9a84c; }
  .btn-auth { width:100%; padding:14px; background:linear-gradient(135deg,#c9a84c,#8b6a2e); color:#fff; border:none; border-radius:14px; font:900 1rem Cairo,sans-serif; cursor:pointer; margin-top:8px; transition:opacity .2s; }
  .btn-auth:hover { opacity:.9; }
  .auth-link { text-align:center; margin-top:18px; font-size:.85rem; color:#888; }
  .auth-link a { color:#b8860b; font-weight:700; text-decoration:none; }
  .auth-link a:hover { text-decoration:underline; }
  .back-store { display:block; text-align:center; margin-top:16px; color:#b8860b; font-size:.85rem; font-weight:600; text-decoration:none; }
  .back-store:hover { text-decoration:underline; }
</style>
</head>
<body>

<div class="auth-box">
  <div class="auth-logo">
    <img src="{{ asset('seva-logo-transparent.png') }}" alt="SEVA">
    <span>SEVA</span>
  </div>

  <div class="auth-title">تسجيل الدخول</div>
  <div class="auth-sub">ادخل لحسابك لمتابعة طلباتك</div>

  @if($errors->any())
    <div style="background:#fff0f0;border:1px solid #f5c6c6;border-radius:10px;padding:12px 14px;margin-bottom:16px;color:#c0392b;font-size:.85rem;font-weight:600;">
      {{ $errors->first() }}
    </div>
  @endif

  <form method="POST" action="{{ route('customer.login.post') }}">
    @csrf
    <div class="field">
      <label>رقم الهاتف</label>
      <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="01012345678" required autofocus>
    </div>
    <div class="field">
      <label>كلمة المرور</label>
      <input type="password" name="password" placeholder="••••••••" required>
    </div>
    <label style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:#555;margin-bottom:8px;cursor:pointer;">
      <input type="checkbox" name="remember" style="width:auto;accent-color:#c9a84c;">
      تذكرني
    </label>
    <button type="submit" class="btn-auth">دخول</button>
  </form>

  <div class="auth-link">
    ليس لديك حساب؟ <a href="{{ route('customer.register') }}">سجّل الآن</a>
  </div>
</div>

<a href="{{ route('home') }}" class="back-store">← العودة للمتجر بدون تسجيل دخول</a>

</body>
</html>
