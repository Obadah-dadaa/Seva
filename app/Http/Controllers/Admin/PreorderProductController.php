<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreorderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PreorderProductController extends Controller
{
    public function index()
    {
        $preorders = PreorderProduct::orderBy('sort_order')->latest()->paginate(10);

        return view('admin.preorders.index', compact('preorders'));
    }

    public function create()
    {
        return view('admin.preorders.create', [
            'preorder' => new PreorderProduct(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('preorders', 'public');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->input('image_url');
        }

        PreorderProduct::create($data);

        return redirect()->route('admin.preorders.index')->with('success', 'تمت إضافة توصية جديدة.');
    }

    public function edit(PreorderProduct $preorder)
    {
        return view('admin.preorders.edit', compact('preorder'));
    }

    public function update(Request $request, PreorderProduct $preorder)
    {
        $data = $this->validatedData($request, $preorder);

        if ($request->hasFile('image')) {
            $this->deleteLocalImage($preorder);
            $data['image'] = $request->file('image')->store('preorders', 'public');
        } elseif ($request->filled('image_url')) {
            if ($request->input('image_url') !== $preorder->image) {
                $this->deleteLocalImage($preorder);
            }
            $data['image'] = $request->input('image_url');
        }

        $preorder->update($data);

        return redirect()->route('admin.preorders.index')->with('success', 'تم تعديل التوصية.');
    }

    public function destroy(PreorderProduct $preorder)
    {
        $this->deleteLocalImage($preorder);
        $preorder->delete();

        return redirect()->route('admin.preorders.index')->with('success', 'تم حذف التوصية.');
    }

    private function validatedData(Request $request, PreorderProduct $preorder = null)
    {
        $hasStoredImage = $preorder && $preorder->image;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => [$hasStoredImage ? 'nullable' : Rule::requiredIf(!$request->filled('image_url')), 'image', 'max:4096'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'price_note' => ['required', 'string', 'max:255'],
            'estimated_delivery' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'active' => ['nullable', 'boolean'],
        ]);

        unset($data['image_url']);
        $data['active'] = $request->boolean('active');

        return $data;
    }

    private function deleteLocalImage(PreorderProduct $preorder)
    {
        if ($preorder->image && !preg_match('/^https?:\/\//', $preorder->image)) {
            Storage::disk('public')->delete($preorder->image);
        }
    }
}
