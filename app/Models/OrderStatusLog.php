<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['order_id', 'status', 'created_at'];

    protected $casts = ['created_at' => 'datetime'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Customer-facing message for this status update.
     */
    public function getCustomerMessageAttribute(): string
    {
        return [
            'pending_payment' => 'طلبك بانتظار تأكيد الدفع',
            'new'             => 'تم تسجيل طلبك بنجاح',
            'reviewed'        => 'تمت مراجعة طلبك',
            'completed'       => 'طلبك في الطريق إليك 🚚',
            'delivered'       => 'تم تسليم طلبك ✅',
            'cancelled'       => 'تم إلغاء طلبك',
        ][$this->status] ?? 'تحديث على طلبك';
    }

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
