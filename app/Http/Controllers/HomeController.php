<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\PreorderProduct;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $items = Item::with(['category', 'images'])
                ->where('active', true)
                ->latest()
                ->get()
                ->map(function (Item $item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name ?: '',
                        'type' => $item->type ?: '',
                        'cat' => optional($item->category)->name ?: '',
                        'img' => $item->public_image,
                        'images' => $item->images->map(function ($img) { return $img->public_image; })->filter()->values()->all(),
                        'price' => (float) $item->price,
                        'oldPrice' => $item->old_price ? (float) $item->old_price : null,
                        'discount' => $item->discount,
                        'sizes' => $item->sizes ?: [],
                        'colors' => $item->colors ?: [],
                        'quality' => $item->quality ?: '',
                        'material' => $item->material ?: '',
                        'origin' => $item->origin ?: '',
                        'stock' => (int) $item->stock,
                        'featured' => $item->featured,
                    ];
                });
        } catch (\Throwable $exception) {
            $items = collect();
        }

        try {
            $preorderProducts = PreorderProduct::where('active', true)
                ->orderBy('sort_order')
                ->latest()
                ->get()
                ->map(function (PreorderProduct $product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name ?: '',
                        'type' => $product->type ?: '',
                        'description' => $product->description ?: '',
                        'img' => $product->public_image,
                        'priceNote' => $product->price_note ?: '',
                        'estimatedDelivery' => $product->estimated_delivery ?: '',
                        'quantity' => (int) ($product->quantity ?: 1),
                    ];
                });
        } catch (\Throwable $exception) {
            $preorderProducts = collect();
        }

        try {
            $categories = Category::where('active', true)->orderBy('name')->pluck('name')->all();
        } catch (\Throwable $exception) {
            $categories = [];
        }

        $whatsappNumber = preg_replace('/\D+/', '', config('services.seva_whatsapp', '201234567890'));

        return view('home', compact('items', 'preorderProducts', 'whatsappNumber', 'categories'));
    }
}
