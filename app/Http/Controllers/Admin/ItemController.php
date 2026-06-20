<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    private const SIZES = [
        'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL',
        '28', '30', '32', '34', '36', '38', '40', '42', '44', '46',
        '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45',
        'One Size',
    ];

    private const COLORS = [
        'أسود', 'أبيض', 'أوف وايت', 'سكري', 'بيج', 'رمادي', 'فضي',
        'ذهبي', 'بني', 'جملي', 'كحلي', 'أزرق', 'سماوي', 'تركواز',
        'أخضر', 'زيتي', 'نعناعي', 'أصفر', 'خردلي', 'برتقالي',
        'أحمر', 'عنابي', 'وردي', 'فوشيا', 'بنفسجي', 'ليلكي',
        'موف', 'خوخي', 'نحاسي', 'شفاف', 'متعدد الألوان',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::with('category')->latest()->paginate(10);

        return view('admin.items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.items.create', [
            'item' => new Item(),
            'categories' => Category::where('active', true)->orderBy('name')->get(),
            'sizes' => self::SIZES,
            'colors' => self::COLORS,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        $request->validate([
            'gallery_images'   => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item = Item::create($data);

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $i => $file) {
                $item->images()->create([
                    'image'      => $file->store('items', 'public'),
                    'sort_order' => $i + 1,
                ]);
            }
        }

        return redirect()->route('admin.items.index')->with('success', 'تمت إضافة المنتج.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        return view('admin.items.edit', [
            'item' => $item->load('images'),
            'categories' => Category::orderBy('name')->get(),
            'sizes' => self::SIZES,
            'colors' => self::COLORS,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $data = $this->validatedData($request, $item);

        $request->validate([
            'gallery_images'   => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'max:4096'],
            'delete_images'    => ['nullable', 'array'],
            'delete_images.*'  => ['integer'],
        ]);

        if ($request->hasFile('image')) {
            if ($item->image && !preg_match('/^https?:\/\//', $item->image)) {
                Storage::disk('public')->delete($item->image);
            }

            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        // Delete selected gallery images
        $deleteIds = array_filter((array) $request->input('delete_images', []), 'is_numeric');
        if (!empty($deleteIds)) {
            foreach ($item->images()->whereIn('id', $deleteIds)->get() as $img) {
                if ($img->image && !preg_match('/^https?:\/\//', $img->image)) {
                    Storage::disk('public')->delete($img->image);
                }
                $img->delete();
            }
        }

        // Save new gallery images
        if ($request->hasFile('gallery_images')) {
            $maxSort = (int) $item->images()->max('sort_order');
            foreach ($request->file('gallery_images') as $i => $file) {
                $item->images()->create([
                    'image'      => $file->store('items', 'public'),
                    'sort_order' => $maxSort + $i + 1,
                ]);
            }
        }

        return redirect()->route('admin.items.index')->with('success', 'تم تعديل المنتج.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        if ($item->image && !preg_match('/^https?:\/\//', $item->image)) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('admin.items.index')->with('success', 'تم حذف المنتج.');
    }

    private function validatedData(Request $request, Item $item = null)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => [$item && $item->image ? 'nullable' : 'required', 'image', 'max:4096'],
            'price' => ['required', 'numeric', 'min:1'],
            'old_price' => ['required', 'numeric', 'min:1', 'gte:price'],
            'colors' => ['required', 'array', 'min:1'],
            'colors.*' => ['required', 'string', 'max:255'],
            'sizes' => ['required', 'array', 'min:1'],
            'sizes.*' => ['required', 'string', 'max:255'],
            'material' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'featured' => ['nullable', 'boolean'],
            'active' => ['nullable', 'boolean'],
        ]);

        $data['discount'] = $this->calculateDiscount($data['price'], $data['old_price']);
        $data['featured'] = $request->boolean('featured');
        $data['active'] = $request->boolean('active');

        return $data;
    }

    private function calculateDiscount($price, $oldPrice)
    {
        if (!$oldPrice || $oldPrice <= $price) {
            return null;
        }

        return (int) round((($oldPrice - $price) / $oldPrice) * 100);
    }
}
