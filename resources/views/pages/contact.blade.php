<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>اتصل بنا — SEVA</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
<style>
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  body{background:#f7f3ee;min-height:100vh;font-family:Cairo,sans-serif;color:#2c1a0e;padding:32px 16px 48px}
  .container{max-width:600px;margin:0 auto}
  .brand{display:flex;align-items:center;gap:10px;justify-content:center;margin-bottom:36px}
  .brand img{width:46px;height:46px;border-radius:50%;object-fit:contain;background:#f7f3ee;padding:4px}
  .brand span{font-size:1.5rem;font-weight:900;color:#2c1a0e;letter-spacing:1px}
  .card{background:#fff;border-radius:20px;padding:36px 32px;box-shadow:0 4px 24px rgba(0,0,0,.07);margin-bottom:20px}
  h1{font-size:1.4rem;font-weight:900;color:#2c1a0e;margin-bottom:8px;text-align:center}
  .subtitle{text-align:center;color:#aaa;font-size:.88rem;margin-bottom:28px}
  .divider{border:none;border-top:1.5px solid #f0ebe4;margin:22px 0}
  .badge{display:inline-block;background:linear-gradient(135deg,#c9a84c,#8b6a2e);color:#fff;font-size:.75rem;font-weight:700;padding:3px 10px;border-radius:20px;margin-bottom:20px;letter-spacing:.5px}
  .contact-list{display:flex;flex-direction:column;gap:14px}
  .contact-item{display:flex;align-items:center;gap:14px;padding:16px 18px;background:#faf8f5;border-radius:14px;border:1.5px solid #f0ebe4;text-decoration:none;color:#2c1a0e;transition:border-color .2s,box-shadow .2s}
  .contact-item:hover{border-color:#c9a84c;box-shadow:0 2px 10px rgba(201,168,76,.15)}
  .contact-icon{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;background:linear-gradient(135deg,#c9a84c22,#c9a84c11)}
  .contact-info{}
  .contact-label{font-size:.78rem;color:#aaa;font-weight:600;margin-bottom:2px}
  .contact-value{font-size:.95rem;font-weight:700;color:#2c1a0e;direction:ltr;text-align:right}
  .hours-note{text-align:center;margin-top:20px;font-size:.82rem;color:#aaa;font-weight:600}
  .back-btn{display:block;text-align:center;margin-top:24px;color:#b8860b;font-size:.88rem;font-weight:700;text-decoration:none}
  .back-btn:hover{text-decoration:underline}
</style>
</head>
<body>
<div class="container">

  <div class="brand">
    <img src="{{ asset('seva-logo-transparent.png') }}" alt="SEVA">
    <span>SEVA</span>
  </div>

  @php
    $phone = preg_replace('/\D+/', '', config('services.seva_whatsapp', '201234567890'));
  @endphp

  <div class="card">
    <div style="text-align:center"><span class="badge">نحن هنا لمساعدتك</span></div>
    <h1>اتصل بنا</h1>
    <p class="subtitle">فريق خدمة العملاء جاهز للإجابة على كل استفساراتك</p>
    <hr class="divider">

    <div class="contact-list">

      <a href="https://wa.me/{{ $phone }}" target="_blank" class="contact-item">
        <div class="contact-icon">💬</div>
        <div class="contact-info">
          <div class="contact-label">واتساب</div>
          <div class="contact-value">+{{ $phone }}</div>
        </div>
      </a>

      <a href="tel:+{{ $phone }}" class="contact-item">
        <div class="contact-icon">📞</div>
        <div class="contact-info">
          <div class="contact-label">هاتف</div>
          <div class="contact-value">+{{ $phone }}</div>
        </div>
      </a>

      <a href="mailto:info@seva.com" class="contact-item">
        <div class="contact-icon">📧</div>
        <div class="contact-info">
          <div class="contact-label">البريد الإلكتروني</div>
          <div class="contact-value">info@seva.com</div>
        </div>
      </a>

      <div class="contact-item" style="cursor:default">
        <div class="contact-icon">📍</div>
        <div class="contact-info">
          <div class="contact-label">الموقع</div>
          <div class="contact-value">القاهرة، مصر</div>
        </div>
      </div>

    </div>

    <p class="hours-note">🕐 ساعات العمل: السبت – الخميس &nbsp;|&nbsp; 9 ص – 9 م</p>
  </div>

  <a href="{{ route('home') }}" class="back-btn">← العودة للمتجر</a>

</div>
</body>
</html>
