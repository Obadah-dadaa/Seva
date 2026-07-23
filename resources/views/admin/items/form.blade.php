@csrf
@php
    $selectedSizes = old('sizes', $item->sizes ?: []);
    $selectedColors = old('colors', $item->colors ?: []);
    $variantStockData = old('variant_stock', $variantStock ?? []);
@endphp

<div class="form-grid">
    <div class="form-field">
        <label>اسم المنتج</label>
        <input type="text" name="name" value="{{ old('name', $item->name) }}" required>
    </div>
    <div class="form-field">
        <label>الفئة</label>
        <select name="category_id" required>
            <option value="">اختر الفئة</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-field">
        <label>نوع المنتج</label>
        <input type="text" name="type" value="{{ old('type', $item->type) }}" placeholder="عباية، فستان، اكسسوار" required>
    </div>
    <div class="form-field">
        <label>الصورة الرئيسية</label>
        <input type="file" name="image" accept="image/*" {{ $item->image ? '' : 'required' }}>
        @if($item->image)
            <div style="margin-top:8px;"><img src="{{ $item->public_image }}" style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid #e8e0d6;"></div>
            <small>الصورة الحالية أعلاه — اترك الحقل فارغ إذا لا تريد تغييرها.</small>
        @endif
    </div>
    <div class="form-field full">
        <label>صور إضافية (معرض الصور)</label>
        @if($item->exists && $item->relationLoaded('images') && $item->images->count() > 0)
            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:10px;">
                @foreach($item->images as $img)
                    <div id="gthumb-{{ $img->id }}" style="position:relative;display:inline-block;">
                        <img src="{{ $img->public_image }}" style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid #e8e0d6;display:block;transition:opacity .2s;">
                        <span onclick="toggleGalleryDelete({{ $img->id }})" title="حذف هذه الصورة" style="position:absolute;top:-8px;right:-8px;background:#e74c3c;color:#fff;border-radius:50%;width:22px;height:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:16px;line-height:1;user-select:none;transition:background .2s;">×</span>
                        <input type="checkbox" name="delete_images[]" value="{{ $img->id }}" id="del-{{ $img->id }}" style="display:none;">
                    </div>
                @endforeach
            </div>
            <small style="color:#888;display:block;margin-bottom:8px;">اضغط × لتحديد صورة للحذف (تظهر باهتة)، اضغط مرة أخرى للإلغاء.</small>
        @endif
        <input type="file" name="gallery_images[]" accept="image/*" multiple>
        <small>يمكنك رفع عدة صور دفعة واحدة.</small>
    </div>
    <div class="form-field">
        <label>السعر بعد الخصم</label>
        <input type="number" step="0.01" name="price" value="{{ old('price', $item->price) }}" required>
    </div>
    <div class="form-field">
        <label>السعر قبل الخصم</label>
        <input type="number" step="0.01" name="old_price" value="{{ old('old_price', $item->old_price) }}" required>
    </div>
    <div class="form-field">
        <label>إجمالي المخزون (محسوب تلقائياً)</label>
        <input type="number" value="{{ $item->stock ?? 0 }}" readonly disabled style="background:#f3efe8;">
        <small>يُحسب من مجموع مخزون كل تركيبة لون/مقاس أدناه.</small>
    </div>
    <div class="form-field">
        <label>الخامة</label>
        <input type="text" name="material" value="{{ old('material', $item->material) }}" required>
    </div>
    <div class="form-field">
        <label>الألوان</label>
        <select name="colors[]" id="colorsSelect" multiple required size="8">
            @foreach($colors as $color)
                <option value="{{ $color }}" {{ in_array($color, $selectedColors) ? 'selected' : '' }}>{{ $color }}</option>
            @endforeach
        </select>
        <small>اضغط Ctrl لاختيار أكثر من لون.</small>
        <div style="display:flex;gap:8px;margin-top:8px;">
            <input type="text" id="newColorInput" placeholder="إضافة لون جديد" style="flex:1;">
            <button type="button" class="btn-admin" id="addColorBtn">إضافة</button>
        </div>
        <small id="newColorError" style="color:#c0392b;display:none;"></small>
    </div>
    <div class="form-field">
        <label>المقاسات</label>
        <select name="sizes[]" id="sizesSelect" multiple required size="8">
            @foreach($sizes as $size)
                <option value="{{ $size }}" {{ in_array($size, $selectedSizes) ? 'selected' : '' }}>{{ $size }}</option>
            @endforeach
        </select>
        <small>اضغط Ctrl لاختيار أكثر من مقاس.</small>
    </div>
    <div class="form-field full">
        <label>مخزون كل تركيبة (لون × مقاس)</label>
        <div id="variantStockWrap" style="overflow-x:auto;"></div>
        <small>حدد الألوان والمقاسات أعلاه أولاً، ثم أدخل الكمية المتاحة لكل تركيبة على حدة. الخانات المظللة باللون الأحمر بمخزون 0 ولن تظهر للعميل.</small>
    </div>
    <div class="form-field full">
        <label>الوصف</label>
        <textarea name="description" required>{{ old('description', $item->description) }}</textarea>
    </div>
    <div class="form-field full check-row">
        <label><input type="checkbox" name="featured" value="1" {{ old('featured', $item->featured) ? 'checked' : '' }}> منتج مميز</label>
        <label><input type="checkbox" name="active" value="1" {{ old('active', $item->exists ? $item->active : true) ? 'checked' : '' }}> مفعل</label>
    </div>
    <div class="form-field full actions">
        <button class="btn-admin" type="submit">{{ $button }}</button>
        <a class="btn-admin btn-muted" href="{{ route('admin.items.index') }}">رجوع</a>
    </div>
</div>
<script>
function toggleGalleryDelete(id) {
    var thumb = document.getElementById('gthumb-' + id);
    var cb    = document.getElementById('del-' + id);
    cb.checked = !cb.checked;
    thumb.querySelector('img').style.opacity  = cb.checked ? '0.3'     : '1';
    thumb.querySelector('span').style.background = cb.checked ? '#27ae60' : '#e74c3c';
    thumb.querySelector('span').textContent   = cb.checked ? '↺'     : '×';
}

// ===== Per (color, size) variant stock matrix =====
(function () {
    var existingStock = @json($variantStockData);
    var currentStock = {}; // color -> size -> value, tracks in-progress edits across rebuilds

    Object.keys(existingStock || {}).forEach(function (color) {
        currentStock[color] = Object.assign({}, existingStock[color]);
    });

    function selectedValues(select) {
        return Array.from(select.selectedOptions).map(function (o) { return o.value; });
    }

    function rebuildVariantTable() {
        var colorsSelect = document.getElementById('colorsSelect');
        var sizesSelect = document.getElementById('sizesSelect');
        var wrap = document.getElementById('variantStockWrap');
        if (!colorsSelect || !sizesSelect || !wrap) return;

        var colors = selectedValues(colorsSelect);
        var sizes = selectedValues(sizesSelect);

        // Capture any values currently on screen before rebuilding.
        wrap.querySelectorAll('input[data-color][data-size]').forEach(function (input) {
            var c = input.dataset.color, s = input.dataset.size;
            currentStock[c] = currentStock[c] || {};
            currentStock[c][s] = input.value;
        });

        if (!colors.length || !sizes.length) {
            wrap.innerHTML = '<p style="color:#aaa;font-size:.85rem;">اختر الألوان والمقاسات أولاً.</p>';
            return;
        }

        var html = '<table style="border-collapse:collapse;min-width:100%;">';
        html += '<tr><th style="padding:6px 10px;border:1px solid #e8e0d6;background:#f7f3ee;">اللون \\ المقاس</th>';
        sizes.forEach(function (size) {
            html += '<th style="padding:6px 10px;border:1px solid #e8e0d6;background:#f7f3ee;">' + size + '</th>';
        });
        html += '</tr>';

        colors.forEach(function (color) {
            html += '<tr><th style="padding:6px 10px;border:1px solid #e8e0d6;background:#f7f3ee;">' + color + '</th>';
            sizes.forEach(function (size) {
                var value = (currentStock[color] && currentStock[color][size] !== undefined) ? currentStock[color][size] : 0;
                var zero = Number(value) <= 0;
                html += '<td style="padding:4px;border:1px solid #e8e0d6;' + (zero ? 'background:#fbe1df;' : '') + '">' +
                    '<input type="number" min="0" style="width:70px;" ' +
                    'data-color="' + color + '" data-size="' + size + '" ' +
                    'name="variant_stock[' + color + '][' + size + ']" value="' + value + '">' +
                    '</td>';
            });
            html += '</tr>';
        });
        html += '</table>';

        wrap.innerHTML = html;

        wrap.querySelectorAll('input[data-color][data-size]').forEach(function (input) {
            input.addEventListener('input', function () {
                input.closest('td').style.background = Number(input.value) > 0 ? '' : '#fbe1df';
            });
        });
    }

    document.getElementById('colorsSelect').addEventListener('change', rebuildVariantTable);
    document.getElementById('sizesSelect').addEventListener('change', rebuildVariantTable);
    rebuildVariantTable();

    var form = document.getElementById('variantStockWrap').closest('form');

    if (form) {
        form.addEventListener('submit', function (e) {
            var zeroCount = 0;
            document.querySelectorAll('#variantStockWrap input[data-color][data-size]').forEach(function (input) {
                if (Number(input.value) <= 0) zeroCount++;
            });
            if (zeroCount > 0) {
                var proceed = confirm('يوجد ' + zeroCount + ' تركيبة لون/مقاس بمخزون 0، لن تظهر هذه التركيبات للعميل. هل تريد المتابعة؟');
                if (!proceed) e.preventDefault();
            }
        });
    }
})();

// ===== Add a new color inline =====
(function () {
    var btn = document.getElementById('addColorBtn');
    var input = document.getElementById('newColorInput');
    var errorEl = document.getElementById('newColorError');
    var select = document.getElementById('colorsSelect');
    if (!btn || !input || !select) return;

    function showError(message) {
        errorEl.textContent = message;
        errorEl.style.display = '';
    }

    btn.addEventListener('click', function () {
        var name = input.value.trim();
        errorEl.style.display = 'none';
        if (!name) return;

        btn.disabled = true;
        var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch(@json(route('admin.colors.store')), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ name: name }),
        })
            .then(function (res) {
                return res.json().then(function (data) { return { ok: res.ok, data: data }; });
            })
            .then(function (result) {
                if (!result.ok) {
                    var message = (result.data.errors && result.data.errors.name && result.data.errors.name[0]) ||
                        result.data.message || 'تعذّرت إضافة اللون.';
                    showError(message);
                    return;
                }

                var option = document.createElement('option');
                option.value = result.data.name;
                option.textContent = result.data.name;
                option.selected = true;
                select.appendChild(option);
                select.dispatchEvent(new Event('change'));
                input.value = '';
            })
            .catch(function () {
                showError('تعذّرت إضافة اللون، حاول مرة أخرى.');
            })
            .finally(function () {
                btn.disabled = false;
            });
    });
})();
</script>
