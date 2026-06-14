<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>سياسة الاسترداد والتبديل — SEVA</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
<style>
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  body{background:#f7f3ee;min-height:100vh;font-family:Cairo,sans-serif;color:#2c1a0e;padding:32px 16px 48px}
  .container{max-width:700px;margin:0 auto}
  .brand{display:flex;align-items:center;gap:10px;justify-content:center;margin-bottom:36px}
  .brand img{width:46px;height:46px;border-radius:50%;object-fit:contain;background:#f7f3ee;padding:4px}
  .brand span{font-size:1.5rem;font-weight:900;color:#2c1a0e;letter-spacing:1px}
  .card{background:#fff;border-radius:20px;padding:36px 32px;box-shadow:0 4px 24px rgba(0,0,0,.07);margin-bottom:20px}
  h1{font-size:1.4rem;font-weight:900;color:#2c1a0e;margin-bottom:8px;text-align:center}
  .subtitle{text-align:center;color:#aaa;font-size:.88rem;margin-bottom:28px}
  .divider{border:none;border-top:1.5px solid #f0ebe4;margin:20px 0}
  .section{margin-bottom:22px}
  .section-title{font-size:1rem;font-weight:900;color:#b8860b;margin-bottom:10px;display:flex;align-items:center;gap:8px}
  .section-title .icon{font-size:1.1rem}
  ul{padding-right:20px;display:flex;flex-direction:column;gap:7px}
  ul li{font-size:.9rem;color:#444;line-height:1.7}
  .note{background:#fffbf0;border:1.5px solid #f0d080;border-radius:12px;padding:14px 16px;font-size:.87rem;color:#7a5c00;font-weight:600;line-height:1.7}
  .back-btn{display:block;text-align:center;margin-top:24px;color:#b8860b;font-size:.88rem;font-weight:700;text-decoration:none}
  .back-btn:hover{text-decoration:underline}
  .badge{display:inline-block;background:linear-gradient(135deg,#c9a84c,#8b6a2e);color:#fff;font-size:.75rem;font-weight:700;padding:3px 10px;border-radius:20px;margin-bottom:20px;letter-spacing:.5px}
</style>
</head>
<body>
<div class="container">

  <div class="brand">
    <img src="{{ asset('seva-logo-transparent.png') }}" alt="SEVA">
    <span>SEVA</span>
  </div>

  <div class="card">
    <div style="text-align:center"><span class="badge">خدمة العملاء</span></div>
    <h1>سياسة الاسترداد والتبديل</h1>
    <p class="subtitle">رضاكم غايتنا — نضمن لكم تجربة تسوق مريحة وآمنة</p>
    <hr class="divider">

    <div class="section">
      <div class="section-title"><span class="icon">📅</span> مدة الاسترداد</div>
      <p style="font-size:.9rem;color:#444;line-height:1.8">يحق لك طلب الاسترداد أو التبديل خلال <strong>14 يوماً</strong> من تاريخ استلام طلبك.</p>
    </div>

    <div class="section">
      <div class="section-title"><span class="icon">✅</span> شروط الاسترداد</div>
      <ul>
        <li>المنتج في حالته الأصلية دون استخدام أو غسيل</li>
        <li>البطاقات والعلامات التجارية لا تزال مرفقة</li>
        <li>التغليف الأصلي محفوظ قدر الإمكان</li>
        <li>رقم الطلب متاح للتحقق</li>
      </ul>
    </div>

    <div class="section">
      <div class="section-title"><span class="icon">🔄</span> كيفية طلب الاسترداد</div>
      <ul>
        <li>تواصل معنا عبر واتساب أو الهاتف</li>
        <li>أذكر رقم طلبك والسبب</li>
        <li>سنتواصل معك لترتيب استلام المنتج</li>
      </ul>
    </div>

    <div class="section">
      <div class="section-title"><span class="icon">💳</span> إعادة المبلغ</div>
      <p style="font-size:.9rem;color:#444;line-height:1.8">يُعاد المبلغ بنفس طريقة الدفع الأصلية خلال <strong>3–5 أيام عمل</strong> من استلام المنتج والتحقق منه.</p>
    </div>

    <hr class="divider">

    <div class="note">
      <strong>⚠️ استثناءات —</strong> لا يشمل الاسترداد: المنتجات المفصّلة أو المخصصة حسب الطلب، وبعض الإكسسوارات لأسباب صحية. في حال الشك، تواصل معنا قبل الشراء.
    </div>
  </div>

  <a href="{{ route('home') }}" class="back-btn">← العودة للمتجر</a>

</div>
</body>
</html>
