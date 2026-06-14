<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'type',
        'description',
        'image',
        'price',
        'old_price',
        'discount',
        'colors',
        'sizes',
        'quality',
        'material',
        'origin',
        'stock',
        'featured',
        'active',
    ];

    protected $casts = [
        'colors' => 'array',
        'sizes' => 'array',
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'featured' => 'boolean',
        'active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class)->orderBy('sort_order');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getPublicImageAttribute()
    {
        if (!$this->image) {
            return asset('seva-logo-transparent.png');
        }

        if (preg_match('/^https?:\/\//', $this->image)) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }
}
