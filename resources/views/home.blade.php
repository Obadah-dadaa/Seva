<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>SEVA — أناقة بلا حدود</title>
<link rel="icon" type="image/png" href="{{ asset('seva-logo-transparent.png') }}">
<link rel="apple-touch-icon" href="{{ asset('seva-logo-transparent.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('styles.css') }}">
</head>
<body>

<!-- ====== NAVBAR ====== -->
<nav class="navbar">
  <div class="nav-inner">

    <!-- يمين: لوغو + روابط -->
    <div class="nav-right">
      <button class="nav-logo" onclick="_logoTap()" aria-label="SEVA">
        <img src="{{ asset('seva-logo-transparent.png') }}" alt="SEVA Logo" class="nav-logo-circle">
      </button>
      <div class="nav-links" id="navLinks">
        <button class="nav-link active" onclick="showPage('home')" data-i18n="nav.home">الرئيسية</button>
        <button class="nav-link" onclick="showPage('products')" data-i18n="nav.products">المنتجات</button>
        <button class="nav-link" onclick="showPage('preorders')" data-i18n="nav.preorders">وصّيها لك</button>
        <button class="nav-link" onclick="showPage('abayas')" data-i18n="nav.abayas">عبايات</button>
        <button class="nav-link" onclick="showPage('women')" data-i18n="nav.women">ملابس نسائي</button>
        <button class="nav-link" onclick="showPage('men')" data-i18n="nav.men">ملابس رجالي</button>
        <button class="nav-link" onclick="showPage('accessories')" data-i18n="nav.accessories">اكسسوارات</button>
      </div>
    </div>

    <!-- يسار: يوزر + لغة + بحث + سلة -->
    <div class="nav-actions">
      {{-- User menu --}}
      <div class="user-menu-wrap" id="userMenuWrap">
        @auth
          @if(!auth()->user()->is_admin)
            <button class="icon-btn user-menu-btn" onclick="toggleUserMenu()" id="userMenuBtn" title="حسابي" aria-label="حسابي">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </button>
            <div class="user-dropdown" id="userDropdown">
              <a href="{{ route('customer.orders') }}" class="user-dd-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                <span data-i18n="nav.myorders">طلباتي</span>
              </a>
              <form method="POST" action="{{ route('customer.logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="user-dd-item user-dd-logout">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                  <span data-i18n="nav.logout">تسجيل خروج</span>
                </button>
              </form>
            </div>
          @endif
        @else
          <a href="{{ route('login') }}" class="icon-btn user-menu-btn" title="تسجيل دخول" aria-label="تسجيل دخول">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </a>
        @endauth
      </div>
      <button class="icon-btn lang-btn" onclick="toggleLang()" id="langBtn">EN</button>
      <button class="icon-btn nav-search-btn" onclick="toggleSearch()" data-i18n-title="nav.search.title" title="بحث">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      </button>
      <button class="icon-btn nav-cart-btn" onclick="toggleCart()" data-i18n-title="nav.cart.title" title="السلة">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        <span class="cart-badge" id="cartBadge">0</span>
      </button>
    </div>

  </div>
</nav>

<!-- ====== PAGES ====== -->

<!-- HOME PAGE -->
<div class="page active" id="page-home">

  <!-- HERO -->
  <section class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <div class="hero-text">
        <span class="hero-badge" data-i18n="hero.badge">✦ مجموعة جديدة ٢٠٢٦</span>
        <h1 class="hero-title" data-i18n-html="hero.title">أناقة<br>بلا حدود</h1>
        <p class="hero-subtitle" data-i18n="hero.subtitle">Elegance Without Limits</p>
        <p class="hero-desc" data-i18n="hero.desc">اكتشفي أحدث صيحات الموضة والعبايات الفاخرة. تصاميم عصرية تجمع الأصالة والحداثة لتناسب ذوقك الراقي</p>
        <div class="hero-btns">
          <button class="btn-primary" onclick="showPage('products')">
            <span data-i18n="hero.btn">تسوق الآن</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M15 19l-7-7 7-7"/></svg>
          </button>
        </div>
      </div>
    </div>
    <div class="hero-watermark">SEVA</div>
  </section>

  <!-- FEATURES -->
  <div class="features-bar">
    <div class="features-inner">
      <div class="feature-item">
        <div class="feature-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8"/></svg>
        </div>
        <div class="feature-text"><p data-i18n="feat1.title">توصيل سريع</p><p data-i18n="feat1.sub">لجميع أنحاء مصر</p></div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="feature-text"><p data-i18n="feat2.title">ضمان الجودة</p><p data-i18n="feat2.sub">منتجات أصلية ١٠٠٪</p></div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </div>
        <div class="feature-text"><p data-i18n="feat3.title">استبدال مجاني</p><p data-i18n="feat3.sub">خلال ١٤ يوم</p></div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div class="feature-text"><p data-i18n="feat4.title">أحدث الصيحات</p><p data-i18n="feat4.sub">تحديث أسبوعي</p></div>
      </div>
    </div>
  </div>

  <!-- FEATURED PRODUCTS -->
  <section class="products-section">
    <div class="section-header">
      <button class="btn-view-all" onclick="showPage('products')" data-i18n="sec.viewall">عرض الكل</button>
      <div class="section-title">
        <span data-i18n="sec.featured.label">مختارات</span>
        <h2 data-i18n="sec.featured.title">منتجات مميزة</h2>
      </div>
    </div>
    <div class="products-grid" id="homeGrid"></div>
  </section>

  <!-- PREORDER PRODUCTS -->
  <section class="preorder-section">
    <div class="preorder-inner">
      <div class="preorder-copy">
        <span class="preorder-kicker" data-i18n="preorder.kicker">خدمة خاصة</span>
        <h2 data-i18n="preorder.title">وصّيها لك</h2>
        <p data-i18n="preorder.desc">منتجات وأفكار نقدر نأمنها أو نفصلها حسب طلبك، بدون إضافتها للسلة. اختار القطعة وكمّل التفاصيل معنا على واتساب.</p>
        <button class="btn-primary preorder-view-btn" onclick="showPage('preorders')">
          <span data-i18n="preorder.viewall">عرض التواصي</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M15 19l-7-7 7-7"/></svg>
        </button>
      </div>
      <div class="preorder-slider-wrap">
        <button class="preorder-slider-btn preorder-slider-next" onclick="scrollPreorderSlider('homePreorderGrid', 1)" aria-label="التالي">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
        </button>
        <div class="preorder-grid preorder-slider" id="homePreorderGrid"></div>
        <button class="preorder-slider-btn preorder-slider-prev" onclick="scrollPreorderSlider('homePreorderGrid', -1)" aria-label="السابق">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M15 19l-7-7 7-7"/></svg>
        </button>
      </div>
    </div>
  </section>

  <!-- SHIPPING BANNER -->
  <div class="shipping-banner">
    <div class="shipping-inner">
      <div class="shipping-text">
        <h3 data-i18n="ship.title">شحن لكافة أنحاء مصر</h3>
        <p data-i18n="ship.desc">توصيل موثوق وسريع لباب منزلك في جميع المحافظات، مع إمكانية تتبع الطلب لحظة بلحظة</p>
      </div>
      <div class="payment-area">
        <h3 data-i18n="pay.title">طرق الدفع المتاحة</h3>
        <div class="payment-methods">
          <div class="payment-chip cod" data-i18n="checkout.cod">كاش عند الاستلام</div>
          <div class="payment-chip vf" data-i18n="checkout.vodafone">فودافون كاش</div>
          <div class="payment-chip ip" data-i18n="checkout.instapay">انستا باي</div>
        </div>
      </div>
    </div>
  </div>

  <!-- LATEST PRODUCTS -->
  <section class="products-section" style="background: var(--cream2);">
    <div class="section-header">
      <button class="btn-view-all" onclick="showPage('products')" data-i18n="sec.viewall">عرض الكل</button>
      <div class="section-title">
        <span data-i18n="sec.latest.label">جديد</span>
        <h2 data-i18n="sec.latest.title">أحدث المنتجات</h2>
      </div>
    </div>
    <div class="products-grid" id="latestGrid"></div>
  </section>
</div>

<!-- PREORDERS PAGE -->
<div class="page" id="page-preorders">
  <div class="page-header">
    <div class="page-header-inner">
      <div class="breadcrumb"><span data-i18n="bc.home">الرئيسية</span> / <span data-i18n="page.preorders.crumb">وصّيها لك</span></div>
      <h1 data-i18n="page.preorders.title">وصّيها لك</h1>
      <p data-i18n="page.preorders.desc">اختار القطعة وراسلنا على واتساب لنثبت التفاصيل والمقاس والوقت المناسب.</p>
    </div>
  </div>
  <section class="products-section">
    <div class="preorder-page-slider-shell">
      <button class="preorder-slider-btn preorder-slider-next" onclick="scrollPreorderSlider('allPreorderGrid', 1)" aria-label="التالي">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
      </button>
      <div class="preorder-grid preorder-page-grid preorder-slider" id="allPreorderGrid"></div>
      <button class="preorder-slider-btn preorder-slider-prev" onclick="scrollPreorderSlider('allPreorderGrid', -1)" aria-label="السابق">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M15 19l-7-7 7-7"/></svg>
      </button>
    </div>
  </section>
</div>

<!-- PRODUCTS PAGE -->
<div class="page" id="page-products">
  <div class="page-header">
    <div class="page-header-inner">
      <div class="breadcrumb"><span data-i18n="bc.home">الرئيسية</span> / <span data-i18n="page.products.crumb">جميع المنتجات</span></div>
      <h1 data-i18n="page.products.title">جميع المنتجات</h1>
      <p data-i18n="page.products.desc">اكتشف مجموعتنا الكاملة من الأزياء الراقية</p>
    </div>
  </div>
  <section class="products-section">
    <div class="filter-bar" id="filterBar">
      <div class="filter-cats" id="filterCats">
        <button class="filter-cat-btn active" onclick="setFilterCat(this,'')">الكل</button>
      </div>
      <div class="filter-controls">
        <select id="sizeSelect" onchange="applyFilters()" aria-label="فلترة بالمقاس">
          <option value="">كل المقاسات</option>
        </select>
        <select id="sortSelect" onchange="applyFilters()" aria-label="الترتيب">
          <option value="default">ترتيب افتراضي</option>
          <option value="price-asc">السعر: من الأقل</option>
          <option value="price-desc">السعر: من الأعلى</option>
          <option value="newest">الأحدث</option>
        </select>
      </div>
    </div>
    <div class="products-grid" id="allGrid"></div>
  </section>
</div>

<!-- ABAYAS PAGE -->
<div class="page" id="page-abayas">
  <div class="page-header">
    <div class="page-header-inner">
      <div class="breadcrumb"><span data-i18n="bc.home">الرئيسية</span> / <span data-i18n="page.abayas.crumb">عبايات</span></div>
      <h1 data-i18n="page.abayas.title">عبايات فاخرة</h1>
      <p data-i18n="page.abayas.desc">تصاميم عصرية تجمع الأصالة والأناقة</p>
    </div>
  </div>
  <section class="products-section">
    <div class="products-grid" id="abayasGrid"></div>
  </section>
</div>

<!-- WOMEN PAGE -->
<div class="page" id="page-women">
  <div class="page-header">
    <div class="page-header-inner">
      <div class="breadcrumb"><span data-i18n="bc.home">الرئيسية</span> / <span data-i18n="page.women.crumb">ملابس نسائي</span></div>
      <h1 data-i18n="page.women.title">ملابس نسائي</h1>
      <p data-i18n="page.women.desc">أحدث صيحات الموضة النسائية</p>
    </div>
  </div>
  <section class="products-section">
    <div class="products-grid" id="womenGrid"></div>
  </section>
</div>

<!-- MEN PAGE -->
<div class="page" id="page-men">
  <div class="page-header">
    <div class="page-header-inner">
      <div class="breadcrumb"><span data-i18n="bc.home">الرئيسية</span> / <span data-i18n="page.men.crumb">ملابس رجالي</span></div>
      <h1 data-i18n="page.men.title">ملابس رجالي</h1>
      <p data-i18n="page.men.desc">أناقة رجالية بلمسة عصرية</p>
    </div>
  </div>
  <section class="products-section">
    <div class="products-grid" id="menGrid"></div>
  </section>
</div>

<!-- ACCESSORIES PAGE -->
<div class="page" id="page-accessories">
  <div class="accessories-hero">
    <div class="accessories-hero-inner">
      <h1 data-i18n="page.acc.title">✦ اكسسوارات فاخرة</h1>
      <p data-i18n="page.acc.desc">إضافات تكمل إطلالتك بأناقة لا مثيل لها</p>
    </div>
  </div>
  <section class="products-section">
    <div class="products-grid" id="accessoriesGrid"></div>
  </section>
</div>

<!-- FOOTER (repeated for all pages via JS) -->
<footer id="mainFooter">
  <div class="footer-inner">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="footer-logo-mark" dir="ltr">
          <div class="footer-s-badge" style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold-dim));display:flex;align-items:center;justify-content:center;font-weight:900;font-size:18px;color:var(--dark)">S</div>
          <span style="font-size:22px;font-weight:900;letter-spacing:6px;color:var(--white)">EVA</span>
        </div>
        <p data-i18n="footer.brand">أناقة بلا حدود — متجرك المفضل للأزياء الراقية والعبايات الفاخرة</p>
      </div>
      <div class="footer-col">
        <h4 data-i18n="footer.links">روابط سريعة</h4>
        <ul>
          <li><button onclick="showPage('home')" data-i18n="nav.home">الرئيسية</button></li>
          <li><button onclick="showPage('products')" data-i18n="nav.products">المنتجات</button></li>
          <li><button onclick="showPage('preorders')" data-i18n="nav.preorders">وصّيها لك</button></li>
          <li><button onclick="showPage('abayas')" data-i18n="nav.abayas">عبايات</button></li>
          <li><button onclick="showPage('women')" data-i18n="nav.women">ملابس نسائي</button></li>
          <li><button onclick="showPage('men')" data-i18n="nav.men">ملابس رجالي</button></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4 data-i18n="footer.service">خدمة العملاء</h4>
        <ul>
          <li><a href="{{ route('returns') }}" data-i18n="footer.returns">سياسة الاسترداد</a></li>
          <li><a href="{{ route('contact') }}" data-i18n="footer.contact.us">اتصل بنا</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4 data-i18n="footer.contact">تواصل معنا</h4>
        <div class="footer-contact">
          <div>📞 01070156964</div>
          <div>📧 info@seva-store.com</div>
          <div>📍 القاهرة، مصر</div>
        </div>
      </div>
    </div>
    <div class="footer-bottom" data-i18n="footer.copy">© 2026 SEVA. جميع الحقوق محفوظة</div>
  </div>
</footer>

<!-- ====== PRODUCT MODAL ====== -->
<div class="modal-backdrop" id="productModal" onclick="closeModal(event)">
  <div class="modal-box" id="modalBox">
    <div class="modal-imgs" id="modalImgs">
      <img id="modalImg" src="" alt="">
      <span class="modal-badge-discount" id="modalDiscBadge" style="display:none"></span>
      <div id="modalGallery" style="display:none;"></div>
    </div>
    <div class="modal-detail">
      <button class="modal-close" onclick="closeModalDirect()">✕</button>

      <div>
        <span class="modal-cat-badge" id="modalCat"></span>
      </div>

      <div>
        <h2 class="modal-title" id="modalTitle"></h2>
        <p class="modal-subtitle" id="modalType"></p>
      </div>

      <div class="modal-price-row">
        <span class="modal-price" id="modalPrice"></span>
        <span class="modal-price-old" id="modalOldPrice"></span>
      </div>

      <div id="modalSizesBlock">
        <p class="modal-section-label" data-i18n="modal.size.label">اختر المقاس</p>
        <div class="modal-sizes" id="modalSizes"></div>
      </div>

      <div class="modal-qty-row">
        <span class="qty-label" data-i18n="modal.qty.label">الكمية</span>
        <div class="qty-ctrl">
          <button class="qty-btn" onclick="changeQty(1)">+</button>
          <span class="qty-num" id="qtyNum">1</span>
          <button class="qty-btn" onclick="changeQty(-1)">−</button>
        </div>
      </div>

      <div class="modal-features" id="modalFeatures"></div>

      <div id="modalOutOfStock" style="display:none; text-align:center; padding:12px 16px; background:#fff8f0; border:1px solid #f0c070; border-radius:12px; color:#8b5e00; font-weight:700; font-size:.9rem;">
        نفذت الكمية — سيتوفر قريباً ✨
      </div>

      <div class="modal-actions" id="modalActions">
        <button class="btn-add-cart" id="btnAddCart" onclick="addToCart()" data-i18n="modal.add">اطلب الآن</button>
        <button class="btn-remove-cart" id="btnRemoveCart" onclick="removeFromCart()" data-i18n="modal.remove">حذف من السلة</button>
      </div>
    </div>
  </div>
</div>

<!-- ====== CART SIDEBAR ====== -->
<div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>

<button class="cart-fab" id="cartFab" onclick="toggleCart()" aria-label="السلة">
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
  <span class="cart-fab-badge" id="cartFabBadge">0</span>
</button>

<div class="cart-sidebar" id="cartSidebar">
  <div class="cart-drag-handle" id="cartDragHandle"><span></span></div>
  <div class="cart-header">
    <button class="cart-close-btn" onclick="toggleCart()">✕</button>
    <span class="cart-title" data-i18n="cart.title">🛒 سلة التسوق</span>
  </div>
  <div class="cart-items" id="cartItems"></div>
  <div class="cart-footer" id="cartFooter" style="display:none">
    <div class="cart-total">
      <span data-i18n="cart.total">الإجمالي</span>
      <span id="cartTotal">0 ج.م</span>
    </div>
    <button class="btn-checkout" onclick="openCheckout()" data-i18n="cart.checkout">إتمام الطلب ✦</button>
  </div>
</div>

<!-- ====== CHECKOUT MODAL ====== -->
<div class="modal-backdrop" id="checkoutModal" onclick="closeCheckoutOutside(event)">
  <div class="checkout-box">
    <button class="modal-close" onclick="closeCheckout()">✕</button>
    <div class="checkout-step" id="checkoutCustomerStep">
      <h2 data-i18n="checkout.customer.title">بيانات التوصيل</h2>
      <div class="checkout-form">
        <input id="checkoutName" type="text" data-i18n-placeholder="checkout.name" placeholder="الاسم الكامل">
        <input id="checkoutPhone" type="tel" data-i18n-placeholder="checkout.phone" placeholder="رقم الهاتف">
        <input id="checkoutCity" type="text" data-i18n-placeholder="checkout.city" placeholder="المدينة / المحافظة">
        <textarea id="checkoutAddress" data-i18n-placeholder="checkout.address" placeholder="العنوان التفصيلي"></textarea>
        <textarea id="checkoutNotes" data-i18n-placeholder="checkout.notes" placeholder="ملاحظات إضافية"></textarea>
      </div>
      <button class="btn-checkout" onclick="showPaymentStep()" data-i18n="checkout.next">التالي</button>
    </div>
    <div class="checkout-step" id="checkoutPaymentStep" style="display:none">
      <h2 data-i18n="checkout.payment.title">طريقة الدفع</h2>
      <div class="checkout-payments">
        <button class="payment-option" onclick="submitOrder('cash_on_delivery')" data-i18n="checkout.cod">كاش عند الاستلام</button>
        <button class="payment-option" onclick="showPaymentReference('vodafone_cash')" data-i18n="checkout.vodafone">فودافون كاش</button>
        <button class="payment-option" onclick="showPaymentReference('instapay')" data-i18n="checkout.instapay">انستا باي</button>
      </div>
      <button class="btn-admin-like" onclick="backToCustomerStep()" data-i18n="checkout.back">رجوع</button>
    </div>
    <div class="checkout-step" id="checkoutReferenceStep" style="display:none">
      <h2 data-i18n="checkout.reference.title">تأكيد الدفع</h2>
      <p class="checkout-help" data-i18n="checkout.reference.help">بعد التحويل، أدخل رقم العملية أو اسم الحساب الذي تم التحويل منه. سيتم مراجعة الدفع قبل تأكيد الطلب.</p>
      <input id="checkoutPaymentReference" type="text" data-i18n-placeholder="checkout.reference.placeholder" placeholder="رقم العملية / مرجع التحويل">
      <button class="btn-checkout" onclick="submitReferencedOrder()" data-i18n="checkout.send">إرسال الطلب للمراجعة</button>
      <button class="btn-admin-like" onclick="backToPaymentStep()" data-i18n="checkout.back">رجوع</button>
    </div>
  </div>
</div>

<!-- ====== SEARCH OVERLAY ====== -->
<div class="search-overlay" id="searchOverlay" onclick="closeSearchOutside(event)">
  <div class="search-box">
    <div class="search-input-row">
      <button class="search-submit" onclick="doSearch()">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      </button>
      <input class="search-input" id="searchInput" type="text" placeholder="ابحث عن منتج..." data-i18n-placeholder="search.placeholder" oninput="doSearch()">
    </div>
    <div class="search-results" id="searchResults"></div>
  </div>
</div>

<!-- ====== ORDER SUCCESS MODAL ====== -->
<div class="modal-backdrop" id="orderSuccessModal">
  <div class="order-success-box">
    <div class="success-check">✅</div>
    <h2 id="successTitle"></h2>
    <p id="successOrderNum" class="success-order-num"></p>
    <a id="successTrackBtn" href="#" class="btn-checkout success-track-btn"></a>
    <a id="successWhatsAppBtn" href="#" target="_blank" rel="noopener" class="btn-whatsapp btn-whatsapp-lg"></a>
    <button id="successCloseBtn" onclick="closeOrderSuccess()" class="btn-admin-like success-close-btn"></button>
  </div>
</div>

<!-- ====== TRACK SEARCH MODAL ====== -->
<div class="modal-backdrop" id="trackSearchModal" onclick="closeTrackModal(event)">
  <div class="order-success-box" style="gap:16px; max-width:380px;">
    <div style="font-size:2rem;">🔍</div>
    <h2 style="margin:0; font-size:1.2rem;" data-i18n="track.modal.title">تتبع طلبك</h2>
    <p style="margin:0; font-size:.85rem; color:#888;" data-i18n="track.modal.hint">أدخل رقم الطلب الذي وصلك بعد إتمام الشراء</p>
    <input id="trackOrderInput" type="text" placeholder="SEVA-20260614-123"
      style="width:100%; border:2px solid #e8e0d6; border-radius:12px; padding:12px 16px; font:700 .95rem Cairo,sans-serif; text-align:center; outline:none; box-sizing:border-box;"
      onkeydown="if(event.key==='Enter') goToTrack()">
    <button onclick="goToTrack()" class="btn-checkout" style="width:100%; margin:0;" data-i18n="track.modal.btn">تتبع الطلب</button>
    <button onclick="closeTrackModal()" class="btn-admin-like success-close-btn" style="margin:0;" data-i18n="success.close">إغلاق</button>
  </div>
</div>

<!-- ====== TOAST ====== -->
<div class="toast" id="toast"></div>

<script>
  window.SEVA_PRODUCTS = @json($items);
  window.SEVA_PREORDER_PRODUCTS = @json($preorderProducts);
  window.SEVA_WHATSAPP_NUMBER = @json($whatsappNumber);
  window.SEVA_ORDER_STORE_URL = @json(route('orders.store'));
  window.SEVA_TRACK_BASE_URL = @json(url('/track'));
  window.SEVA_LOGGED_IN     = @json(auth()->check() && auth()->user() && !auth()->user()->is_admin);
  window.SEVA_LOGIN_URL     = @json(route('login'));
  window.SEVA_FLASH         = @json(session('seva_toast'));
  window.SEVA_PLACEHOLDER_IMAGE = @json(asset('seva-logo-transparent.png'));
</script>
<script src="{{ asset('script.js') }}"></script>
</body>
</html>
