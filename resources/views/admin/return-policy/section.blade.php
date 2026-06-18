@php
    $icon  = $section['icon'] ?? '';
    $stitle = $section['title'] ?? '';
    $type  = ($section['type'] ?? 'paragraph') === 'list' ? 'list' : 'paragraph';
    $body  = $section['body'] ?? '';
@endphp
<div class="admin-card section-card" style="margin-bottom:14px;">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:12px;">
        <strong style="color:#8b6a2e;">قسم</strong>
        <div class="actions" style="margin:0;">
            <button type="button" class="btn-admin btn-muted" data-action="up" title="تحريك لأعلى" style="min-height:36px;padding:6px 12px;">▲</button>
            <button type="button" class="btn-admin btn-muted" data-action="down" title="تحريك لأسفل" style="min-height:36px;padding:6px 12px;">▼</button>
            <button type="button" class="btn-admin btn-danger" data-action="remove" title="حذف القسم" style="min-height:36px;padding:6px 12px;">حذف ✕</button>
        </div>
    </div>
    <div class="form-grid">
        <div class="form-field">
            <label>الأيقونة (إيموجي)</label>
            <input type="text" name="sections[{{ $index }}][icon]" value="{{ $icon }}" placeholder="📅" maxlength="16">
        </div>
        <div class="form-field">
            <label>نوع المحتوى</label>
            <select name="sections[{{ $index }}][type]">
                <option value="paragraph" {{ $type === 'paragraph' ? 'selected' : '' }}>فقرة (نص)</option>
                <option value="list" {{ $type === 'list' ? 'selected' : '' }}>قائمة (نقاط)</option>
            </select>
        </div>
        <div class="form-field full">
            <label>عنوان القسم</label>
            <input type="text" name="sections[{{ $index }}][title]" value="{{ $stitle }}" placeholder="مدة الاسترداد">
        </div>
        <div class="form-field full">
            <label>المحتوى <span style="color:#8a8070;font-weight:400;">(للقائمة: كل نقطة في سطر)</span></label>
            <textarea name="sections[{{ $index }}][body]" rows="4">{{ $body }}</textarea>
        </div>
    </div>
</div>
