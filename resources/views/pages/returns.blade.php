<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $policy->title }} — SEVA</title>
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
  .section p{font-size:.9rem;color:#444;line-height:1.8}
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
    @if($policy->badge)
      <div style="text-align:center"><span class="badge">{{ $policy->badge }}</span></div>
    @endif
    <h1>{{ $policy->title }}</h1>
    @if($policy->subtitle)
      <p class="subtitle">{{ $policy->subtitle }}</p>
    @endif
    <hr class="divider">

    @foreach($policy->sections ?? [] as $section)
      <div class="section">
        <div class="section-title">
          @if(!empty($section['icon']))<span class="icon">{{ $section['icon'] }}</span>@endif
          {{ $section['title'] ?? '' }}
        </div>
        @if(($section['type'] ?? 'paragraph') === 'list')
          <ul>
            @foreach(preg_split('/\r\n|\r|\n/', $section['body'] ?? '') as $line)
              @if(trim($line) !== '')<li>{!! trim($line) !!}</li>@endif
            @endforeach
          </ul>
        @else
          <p>{!! nl2br($section['body'] ?? '') !!}</p>
        @endif
      </div>
    @endforeach

    @if($policy->note)
      <hr class="divider">
      <div class="note">{!! $policy->note !!}</div>
    @endif
  </div>

  <a href="{{ route('home') }}" class="back-btn">← العودة للمتجر</a>

</div>
</body>
</html>
