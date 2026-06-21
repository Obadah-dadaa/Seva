<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة الإدارة') - SEVA</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    <style>
        body { background: #f5efe3; }
        .admin-shell { min-height: 100vh; }
        .admin-topbar { background: rgb(67 58 41 / 97%); border-bottom: 1px solid rgba(201,168,76,.25); }
        .admin-topbar-inner { max-width: 1280px; margin: 0 auto; padding: 12px 24px; display: grid; grid-template-columns: auto 1fr; align-items: center; gap: 18px; }
        .admin-brand { min-width: 0; display: flex; align-items: center; gap: 12px; color: #fff; font-weight: 900; text-decoration: none; }
        .admin-brand img { width: auto; height: 68px; flex: 0 0 auto; }
        .admin-brand span { min-width: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .admin-nav { min-width: 0; display: flex; gap: 8px; flex-wrap: wrap; align-items: center; justify-content: flex-end; }
        .admin-nav form { margin: 0; }
        .admin-nav a, .admin-nav button { min-height: 42px; border: 1px solid rgba(201,168,76,.28); background: rgba(255,255,255,.06); color: #fff; padding: 9px 16px; border-radius: 22px; font: 700 13px Cairo, sans-serif; text-decoration: none; cursor: pointer; white-space: nowrap; }
        .admin-nav a.active, .admin-nav a:hover, .admin-nav button:hover { background: linear-gradient(135deg, #c9a84c, #8b6a2e); color: #0f0e0c; }
        .admin-main { max-width: 1280px; margin: 0 auto; padding: 28px 24px 56px; }
        .admin-head { display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 22px; }
        .admin-head h1 { margin: 0; font-size: 30px; font-weight: 900; color: #2c2a25; }
        .admin-card { background: #fff; border: 1px solid rgba(0,0,0,.07); border-radius: 8px; padding: 20px; box-shadow: 0 12px 32px rgba(0,0,0,.06); }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px; }
        .stat strong { display: block; font-size: 32px; color: #8b6a2e; }
        .stat span { color: #8a8070; font-size: 13px; }
        .admin-table { width: 100%; border-collapse: collapse; text-align: right; }
        .admin-table th, .admin-table td { padding: 12px; border-bottom: 1px solid rgba(0,0,0,.07); vertical-align: middle; }
        .admin-table th { color: #8b6a2e; font-size: 13px; }
        .admin-table img { width: 54px; height: 68px; object-fit: cover; border-radius: 8px; }
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .form-field { display: flex; flex-direction: column; gap: 6px; }
        .form-field.full { grid-column: 1 / -1; }
        label { font-weight: 700; font-size: 13px; color: #2c2a25; }
        input, select, textarea { width: 100%; border: 1px solid rgba(0,0,0,.14); border-radius: 8px; padding: 11px 12px; font: 600 14px Cairo, sans-serif; background: #fff; color: #2c2a25; }
        textarea { min-height: 110px; resize: vertical; }
        .check-row { display: flex; gap: 18px; align-items: center; flex-wrap: wrap; }
        .check-row label { display: flex; gap: 8px; align-items: center; }
        .check-row input { width: auto; }
        .btn-admin { min-height: 42px; border: none; background: linear-gradient(135deg, #c9a84c, #8b6a2e); color: #0f0e0c; border-radius: 26px; padding: 11px 20px; font: 900 14px Cairo, sans-serif; text-decoration: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; }
        .btn-muted { border: 1px solid #c9a84c; background: transparent; color: #8b6a2e; }
        .btn-danger { border-color: #c0392b; color: #c0392b; }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .alert { margin-bottom: 16px; background: rgba(201,168,76,.14); border: 1px solid rgba(201,168,76,.28); padding: 12px 14px; border-radius: 8px; color: #2c2a25; }
        .errors { margin-bottom: 16px; background: rgba(192,57,43,.08); border: 1px solid rgba(192,57,43,.22); padding: 12px 18px; border-radius: 8px; color: #922b21; }

        @media (max-width: 900px) {
            .admin-topbar-inner { grid-template-columns: 1fr; gap: 10px; padding: 10px 16px 14px; }
            .admin-brand { width: 100%; justify-content: center; }
            .admin-brand img { height: 58px; }
            .admin-nav { width: 100%; display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 8px; justify-content: stretch; }
            .admin-nav a, .admin-nav button { width: 100%; padding: 9px 8px; font-size: 12px; border-radius: 14px; text-align: center; }
            .admin-nav form { width: 100%; }
        }

        @media (max-width: 760px) {
            .admin-main { padding: 20px 14px 42px; }
            .stats-grid, .form-grid { grid-template-columns: 1fr; }
            .admin-head { align-items: stretch; flex-direction: column; gap: 12px; }
            .admin-head h1 { font-size: 24px; }
            .admin-head .btn-admin { width: 100%; }
            .admin-card { padding: 14px; overflow-x: auto; }
            .admin-table { min-width: 680px; }
            .actions { display: grid; grid-template-columns: 1fr; }
            .actions .btn-admin, .actions form, .actions button { width: 100%; }
        }

        @media (max-width: 430px) {
            .admin-topbar-inner { padding-inline: 12px; }
            .admin-brand { justify-content: flex-start; }
            .admin-brand img { height: 50px; }
            .admin-brand span { font-size: 14px; }
            .admin-nav { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .admin-nav a, .admin-nav button { min-height: 40px; font-size: 12px; padding-inline: 6px; }
            .admin-main { padding-inline: 10px; }
        }
    </style>
</head>
<body>
<div class="admin-shell">
    @if(auth()->check() && auth()->user()->is_admin)
    <header class="admin-topbar">
        <div class="admin-topbar-inner">
            <a class="admin-brand" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('seva-logo-transparent.png') }}" alt="SEVA">
                <span>لوحة إدارة SEVA</span>
            </a>
            <nav class="admin-nav">
                <a href="{{ route('home') }}">الموقع</a>
                @auth
                    <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">الرئيسية</a>
                    <a id="adminOrderNotificationsLink" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">الطلبات <span id="adminOrderNotificationsCount" style="display:inline-block;background:#c9a84c;color:#1a1000;border-radius:50%;width:20px;height:20px;line-height:20px;text-align:center;font-size:11px;font-weight:900;margin-right:3px;">{{ \App\Models\Order::whereIn('status', ['new', 'pending_payment'])->count() }}</span></a>
                    <button id="adminSoundBtn" onclick="enableAdminSound()" title="انقر لتفعيل الصوت والإشعارات" style="border-color:rgba(201,168,76,.6);background:rgba(201,168,76,.12);color:#c9a84c;position:relative;" aria-label="تفعيل الصوت">🔔<span id="adminSoundMuted" style="position:absolute;top:2px;left:2px;font-size:9px;line-height:1;">✕</span></button>
                    <a class="{{ request()->routeIs('admin.items.*') ? 'active' : '' }}" href="{{ route('admin.items.index') }}">المنتجات</a>
                    <a class="{{ request()->routeIs('admin.preorders.*') ? 'active' : '' }}" href="{{ route('admin.preorders.index') }}">التواصي</a>
                    <a class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">الفئات</a>
                    <a class="{{ request()->routeIs('admin.return-policy.*') ? 'active' : '' }}" href="{{ route('admin.return-policy.edit') }}">سياسة الترجيع</a>
                    <form method="POST" action="{{ route('admin.logout') }}">@csrf<button type="submit">خروج</button></form>
                @endauth
            </nav>
        </div>
    </header>
    @endif

    {{-- New order banner --}}
    <style>
        @keyframes seva-pulse { 0%,100%{opacity:1} 50%{opacity:.82} }
        #adminNewOrderBanner.visible { animation: seva-pulse .7s ease-in-out infinite; }
        #adminNewOrderBanner .banner-close { float:left; background:rgba(0,0,0,.18); border:none; border-radius:50%; width:26px; height:26px; line-height:26px; text-align:center; cursor:pointer; font-size:14px; color:#1a1000; margin-right:6px; }
    </style>
    <div id="adminNewOrderBanner" style="position:fixed;top:0;left:0;right:0;z-index:9999;background:linear-gradient(135deg,#c9a84c,#8b6a2e);color:#1a1000;text-align:center;padding:14px 20px;font-weight:900;font-size:1rem;cursor:pointer;box-shadow:0 6px 24px rgba(0,0,0,.35);transform:translateY(-100%);transition:transform .35s cubic-bezier(.34,1.56,.64,1);">
        <button class="banner-close" onclick="event.stopPropagation();closeBanner()" title="إغلاق">✕</button>
        <span id="adminBannerText"></span> &nbsp;—&nbsp; <strong>انقر للعرض</strong>
    </div>

    <main class="admin-main">
        @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="errors">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        @yield('content')
    </main>
</div>
@if(auth()->check() && auth()->user()->is_admin)
<script>
(function() {
    var endpoint  = @json(route('admin.orders.notifications'));
    var ordersUrl = @json(route('admin.orders.index'));
    var countEl   = document.getElementById('adminOrderNotificationsCount');
    var link      = document.getElementById('adminOrderNotificationsLink');
    var soundBtn  = document.getElementById('adminSoundBtn');
    var mutedEl   = document.getElementById('adminSoundMuted');
    var originalTitle = document.title;

    // Track last notified order ID in memory only (no localStorage)
    var lastNotifiedId = 0;
    var firstPoll = true;
    var audioCtx = null;
    var soundEnabled = localStorage.getItem('seva_admin_sound') === '1';
    var titleTimer = null;

    // ── UI helper ─────────────────────────────────────────────────────
    function updateSoundBtn(enabled) {
        if (soundBtn) {
            soundBtn.style.background = enabled ? 'linear-gradient(135deg,#c9a84c,#8b6a2e)' : 'rgba(201,168,76,.12)';
            soundBtn.style.color      = enabled ? '#1a1000' : '#c9a84c';
            soundBtn.title            = enabled ? 'الصوت مفعّل — انقر للإيقاف' : 'انقر لتفعيل الصوت والإشعارات';
        }
        if (mutedEl) mutedEl.style.display = enabled ? 'none' : '';
    }

    // ── Restore saved state on load ───────────────────────────────────
    if (soundEnabled) {
        try { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); } catch(e) { soundEnabled = false; }
        updateSoundBtn(soundEnabled);
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }

    // Resume suspended AudioContext on first user gesture (browser policy)
    document.addEventListener('click', function() {
        if (audioCtx && audioCtx.state === 'suspended') audioCtx.resume();
    }, true);

    // ── Sound toggle button ───────────────────────────────────────────
    window.enableAdminSound = function() {
        var enabling = !soundEnabled;
        try {
            audioCtx = audioCtx || new (window.AudioContext || window.webkitAudioContext)();
            audioCtx.resume().then(function() {
                soundEnabled = enabling;
                localStorage.setItem('seva_admin_sound', enabling ? '1' : '0');
                updateSoundBtn(enabling);
                if (enabling) {
                    playChime(1);
                    if ('Notification' in window && Notification.permission === 'default') {
                        Notification.requestPermission();
                    }
                }
            });
        } catch(e) {}
    };

    // ── Chime ─────────────────────────────────────────────────────────
    function playChime(times) {
        if (!soundEnabled || !audioCtx) return;
        times = times || 3;
        for (var i = 0; i < times; i++) {
            (function(offset) {
                setTimeout(function() {
                    try {
                        var comp = audioCtx.createDynamicsCompressor();
                        comp.threshold.setValueAtTime(-6, audioCtx.currentTime);
                        comp.ratio.setValueAtTime(10, audioCtx.currentTime);
                        comp.connect(audioCtx.destination);

                        var master = audioCtx.createGain();
                        master.gain.setValueAtTime(1.0, audioCtx.currentTime);
                        master.connect(comp);

                        [
                            { s: 0,    f: 880,     d: 0.22 },
                            { s: 0.22, f: 1108.73, d: 0.22 },
                            { s: 0.44, f: 1318.51, d: 0.42 },
                        ].forEach(function(t) {
                            var o1 = audioCtx.createOscillator();
                            var o2 = audioCtx.createOscillator();
                            var g  = audioCtx.createGain();
                            var st = audioCtx.currentTime + t.s;
                            var en = st + t.d;
                            o1.type = 'triangle'; o1.frequency.setValueAtTime(t.f, st);
                            o2.type = 'sine';     o2.frequency.setValueAtTime(t.f * 2, st);
                            g.gain.setValueAtTime(0.001, st);
                            g.gain.exponentialRampToValueAtTime(0.95, st + 0.015);
                            g.gain.exponentialRampToValueAtTime(0.001, en);
                            o1.connect(g); o2.connect(g); g.connect(master);
                            o1.start(st); o2.start(st);
                            o1.stop(en + 0.02); o2.stop(en + 0.02);
                        });
                    } catch(e) {}
                }, offset * 750);
            })(i);
        }
    }

    // ── Banner ────────────────────────────────────────────────────────
    window.closeBanner = function() {
        var b = document.getElementById('adminNewOrderBanner');
        if (b) { b.style.transform = 'translateY(-100%)'; b.classList.remove('visible'); }
        clearTimeout(titleTimer);
        document.title = originalTitle;
    };

    function showBanner(data) {
        var b   = document.getElementById('adminNewOrderBanner');
        var txt = document.getElementById('adminBannerText');
        if (!b || !txt) return;
        txt.textContent = '🛍️ طلب جديد رقم ' + (data.latest_number || '') + ' — انقر للعرض';
        b.onclick = function() { window.location.href = ordersUrl; };
        b.style.transform = 'translateY(0)';
        b.classList.add('visible');
    }

    function flashTitle() {
        clearTimeout(titleTimer);
        var on = true;
        (function tick() {
            document.title = on ? '🔔 طلب جديد! — SEVA' : originalTitle;
            on = !on;
            titleTimer = setTimeout(tick, 750);
        })();
        setTimeout(function() {
            clearTimeout(titleTimer);
            document.title = originalTitle;
        }, 30000);
    }

    // ── Notify ────────────────────────────────────────────────────────
    function notify(data) {
        playChime(3);
        showBanner(data);
        flashTitle();

        if ('Notification' in window && Notification.permission === 'granted') {
            try {
                var n = new Notification('🛍️ طلب جديد — SEVA', {
                    body: 'رقم الطلب: ' + (data.latest_number || '—'),
                    tag: 'seva-order-' + data.latest_id,
                    requireInteraction: true,
                });
                n.onclick = function() { window.focus(); window.location.href = ordersUrl; };
            } catch(e) {}
        }
    }

    // ── Poll ──────────────────────────────────────────────────────────
    function pollOrders() {
        fetch(endpoint + '?after_id=0', { headers: { Accept: 'application/json' } })
            .then(function(r) { return r.ok ? r.json() : null; })
            .then(function(data) {
                if (!data) return;

                // Update badge
                if (countEl) countEl.textContent = data.count || 0;
                if (link) link.style.boxShadow = data.count > 0 ? '0 0 0 2px rgba(201,168,76,.6)' : '';

                if (!data.latest_id) { firstPoll = false; return; }

                if (firstPoll) {
                    // Seed — don't notify for orders that already existed
                    lastNotifiedId = data.latest_id;
                    firstPoll = false;
                    return;
                }

                if (data.latest_id > lastNotifiedId) {
                    lastNotifiedId = data.latest_id;
                    notify(data);
                }
            })
            .catch(function() {});
    }

    pollOrders();
    setInterval(pollOrders, 6000);

    // ── Web Push registration ──────────────────────────────────────────
    var VAPID_PUBLIC_KEY = @json(env('VAPID_PUBLIC_KEY', ''));
    var PUSH_SUBSCRIBE_URL   = @json(route('push.subscribe'));
    var PUSH_UNSUBSCRIBE_URL = @json(route('push.unsubscribe'));
    var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]') ?
        document.querySelector('meta[name="csrf-token"]').content : '';

    function urlB64ToUint8Array(base64String) {
        var padding = '='.repeat((4 - base64String.length % 4) % 4);
        var b64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        var rawData = window.atob(b64);
        var outputArray = new Uint8Array(rawData.length);
        for (var i = 0; i < rawData.length; ++i) { outputArray[i] = rawData.charCodeAt(i); }
        return outputArray;
    }

    function sendSubToServer(sub) {
        var json = sub.toJSON();
        fetch(PUSH_SUBSCRIBE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
            body: JSON.stringify({
                endpoint: json.endpoint,
                p256dh:   json.keys && json.keys.p256dh,
                auth:     json.keys && json.keys.auth,
            }),
        }).catch(function() {});
    }

    function registerWebPush() {
        if (!VAPID_PUBLIC_KEY || !('serviceWorker' in navigator) || !('PushManager' in window)) return;

        navigator.serviceWorker.register('/sw.js').then(function(reg) {
            reg.pushManager.getSubscription().then(function(existing) {
                if (existing) { sendSubToServer(existing); return; }

                Notification.requestPermission().then(function(perm) {
                    if (perm !== 'granted') return;
                    reg.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: urlB64ToUint8Array(VAPID_PUBLIC_KEY),
                    }).then(function(sub) {
                        sendSubToServer(sub);
                    }).catch(function(e) { console.warn('Push subscribe failed:', e); });
                });
            });
        }).catch(function(e) { console.warn('SW register failed:', e); });
    }

    // Register SW immediately — no click needed for subscription (only notification permission prompt)
    registerWebPush();
}());
</script>
@endif
</body>
</html>
