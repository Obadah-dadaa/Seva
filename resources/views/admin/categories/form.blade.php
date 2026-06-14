@csrf
<div class="form-grid">
    <div class="form-field">
        <label>اسم الفئة</label>
        <input type="text" name="name" value="{{ old('name', $category->name) }}" required>
    </div>
    <div class="form-field">
        <label>Slug</label>
        <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" placeholder="abayas">
    </div>
    <div class="form-field full">
        <label>الوصف</label>
        <textarea name="description">{{ old('description', $category->description) }}</textarea>
    </div>
    <div class="form-field full check-row">
        <label><input type="checkbox" name="active" value="1" {{ old('active', $category->exists ? $category->active : true) ? 'checked' : '' }}> مفعلة</label>
    </div>
    <div class="form-field full actions">
        <button class="btn-admin" type="submit">{{ $button }}</button>
        <a class="btn-admin btn-muted" href="{{ route('admin.categories.index') }}">رجوع</a>
    </div>
</div>
