<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemVariant extends Model
{
    protected $fillable = [
        'item_id',
        'color',
        'size',
        'stock',
    ];

    protected $casts = [
        'stock' => 'integer',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
