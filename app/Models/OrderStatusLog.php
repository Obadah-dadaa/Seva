<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['order_id', 'status', 'created_at'];

    protected $casts = ['created_at' => 'datetime'];

    public function getStatusLabelAttribute(): string
    {
        return [
            'pending_payment' => 'بانتظار تأكيد الدفع',
            'new'             => 'تم استلام الطلب',
            'reviewed'        => 'تمت المراجعة',
            'completed'       => 'جاري الشحن',
            'delivered'       => 'تم التسليم',
            'cancelled'       => 'ملغي',
        ][$this->status] ?? $this->status;
    }

    public function getIconAttribute(): string
    {
        return [
            'pending_payment' => '⏳',
            'new'             => '🛍️',
            'reviewed'        => '🔍',
            'completed'       => '🚚',
            'delivered'       => '📦',
            'cancelled'       => '❌',
        ][$this->status] ?? '•';
    }
}
