<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreorderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'image',
        'price_note',
        'estimated_delivery',
        'quantity',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'active' => 'boolean',
        'quantity' => 'integer',
    ];

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
