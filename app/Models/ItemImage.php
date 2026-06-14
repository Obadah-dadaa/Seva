<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'image',
        'sort_order',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getPublicImageAttribute()
    {
        if (!$this->image) {
            return null;
        }

        if (preg_match('/^https?:\/\//', $this->image)) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }
}
