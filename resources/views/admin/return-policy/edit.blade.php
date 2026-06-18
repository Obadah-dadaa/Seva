@extends('admin.layout')

@section('title', 'سياسة الترجيع')

@section('content')
<div class="admin-head">
    <h1>تعديل صفحة سياسة الترجيع</h1>
    <a class="btn-admin btn-muted" href="{{ route('returns') }}" target="_blank">معاينة الصفحة ↗</a>
</div>

<form method="POST" action="{{ route('admin.return-policy.update') }}">
    @csrf
    @method('PUT')

    {{-- Page header fields --}}
    <div class="admin-card" style="margin-bottom:18px;">
        <div class="form-grid">
            <div class="form-field">
                <label>الشارة (Badge)</label>
                <input type="text" name="badge" value="{{ old('badge', $policy->badge) }}" placeholder="خدمة العملاء">
            </div>
            <div class="form-field">
                <label>العنوان الرئيسي</label>
                <input type="text" name="title" value="{{ old('title', $policy->title) }}" required>
            </div>
            <div class="form-field full">
                <label>العنوان الفرعي</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $policy->subtitle) }}">
            </div>
        </div>
    </div>

    {{-- Sections --}}
    <div class="admin-head" style="margin:0 0 12px;">
        <h1 style="font-size:20px;">الأقسام</h1>
        <button type="button" class="btn-admin" onclick="addSection()">+ إضافة قسم</button>
    </div>
    <p style="color:#8a8070;font-size:13px;margin:-4px 0 14px;">
        نوع المحتوى: «فقرة» نص واحد، أو «قائمة» نقاط — اكتب كل نقطة في سطر مستقل.
        يمكنك استخدام <code>&lt;strong&gt;كلمة&lt;/strong&gt;</code> للتظليل بالخط العريض.
    </p>

    <div id="sectionsWrap">
        @php $sections = old('sections', $policy->sections ?? []); @endphp
        @forelse($sections as $i => $section)
            @include('admin.return-policy.section', ['index' => $i, 'section' => $section])
        @empty
        @endforelse
    </div>

    {{-- Note box --}}
    <div class="admin-card" style="margin-top:18px;">
        <div class="form-field full">
            <label>صندوق الملاحظة (الاستثناءات) — اتركه فارغاً لإخفائه</label>
            <textarea name="note" placeholder="استثناءات...">{{ old('note', $policy->note) }}</textarea>
        </div>
    </div>

    <div class="actions" style="margin-top:20px;">
        <button class="btn-admin" type="submit">💾 حفظ التغييرات</button>
        <a class="btn-admin btn-muted" href="{{ route('admin.dashboard') }}">رجوع</a>
    </div>
</form>

{{-- Template for a brand-new section (index placeholder __IDX__) --}}
<template id="sectionTemplate">
    @include('admin.return-policy.section', ['index' => '__IDX__', 'section' => ['icon' => '', 'title' => '', 'type' => 'paragraph', 'body' => '']])
</template>

<script>
(function () {
    var wrap = document.getElementById('sectionsWrap');
    var tpl  = document.getElementById('sectionTemplate').innerHTML;
    var next = {{ is_array($sections) ? count($sections) : 0 }};

    window.addSection = function () {
        var html = tpl.replace(/__IDX__/g, next++);
        var div = document.createElement('div');
        div.innerHTML = html.trim();
        wrap.appendChild(div.firstElementChild);
    };

    wrap.addEventListener('click', function (e) {
        var card = e.target.closest('.section-card');
        if (!card) return;

        if (e.target.closest('[data-action="remove"]')) {
            card.remove();
        } else if (e.target.closest('[data-action="up"]')) {
            var prev = card.previousElementSibling;
            if (prev) wrap.insertBefore(card, prev);
        } else if (e.target.closest('[data-action="down"]')) {
            var nextEl = card.nextElementSibling;
            if (nextEl) wrap.insertBefore(nextEl, card);
        }
    });

    // Start with one empty section if the page has none yet
    if (!wrap.querySelector('.section-card')) addSection();
})();
</script>
@endsection
