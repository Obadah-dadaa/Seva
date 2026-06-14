@csrf

<div class="form-grid">
    <div class="form-field">
        <label>اسم القطعة</label>
        <input type="text" name="name" value="{{ old('name', $preorder->name) }}" required>
    </div>
    <div class="form-field">
        <label>نوع التوصية</label>
        <input type="text" name="type" value="{{ old('type', $preorder->type) }}" placeholder="تفصيل خاص، وصاية إكسسوار..." required>
    </div>
    <div class="form-field">
        <label>رفع صورة</label>
        <input type="file" name="image" accept="image/*" {{ $preorder->image ? '' : '' }}>
        @if($preorder->image)
            <small>اترك الحقل فارغاً إذا لا تريد تغيير الصورة الحالية.</small>
        @endif
    </div>
    <div class="form-field">
        <label>أو رابط صورة</label>
        <input type="url" name="image_url" value="{{ old('image_url', preg_match('/^https?:\/\//', $preorder->image ?: '') ? $preorder->image : '') }}" placeholder="https://example.com/image.jpg">
        <small>استخدم إما رفع صورة أو رابط صورة.</small>
    </div>
    <div class="form-field">
        <label>ملاحظة السعر</label>
        <input type="text" name="price_note" value="{{ old('price_note', $preorder->price_note) }}" required>
    </div>
    <div class="form-field">
        <label>مدة التنفيذ</label>
        <input type="text" name="estimated_delivery" value="{{ old('estimated_delivery', $preorder->estimated_delivery) }}" required>
    </div>
    <div class="form-field">
        <label>الكمية</label>
        <input type="number" name="quantity" min="1" value="{{ old('quantity', $preorder->quantity ?? 1) }}" required>
    </div>
    <div class="form-field">
        <label>ترتيب العرض</label>
        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $preorder->sort_order ?? 0) }}" required>
    </div>
    <div class="form-field check-row">
        <label><input type="checkbox" name="active" value="1" {{ old('active', $preorder->exists ? $preorder->active : true) ? 'checked' : '' }}> مفعل</label>
    </div>
    <div class="form-field full">
        <label>الوصف</label>
        <textarea name="description" required>{{ old('description', $preorder->description) }}</textarea>
    </div>
    @if($preorder->image)
        <div class="form-field full">
            <label>الصورة الحالية</label>
            <img src="{{ $preorder->public_image }}" alt="{{ $preorder->name }}" style="width: 120px; height: 150px; object-fit: cover; border-radius: 8px;">
        </div>
    @endif
    <div class="form-field full actions">
        <button class="btn-admin" type="submit">{{ $button }}</button>
        <a class="btn-admin btn-muted" href="{{ route('admin.preorders.index') }}">رجوع</a>
    </div>
</div>
