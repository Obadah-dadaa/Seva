<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const PAYMENT_METHODS = [
        'cash_on_delivery' => 'كاش عند الاستلام',
        'vodafone_cash' => 'فودافون كاش',
        'instapay' => 'انستا باي',
    ];

    protected $fillable = [
        'customer_id',
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_city',
        'customer_address',
        'customer_notes',
        'payment_method',
        'payment_reference',
        'status',
        'total',
        'reviewed_at',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(OrderStatusLog::class)->orderBy('created_at', 'asc');
    }

    public function getPaymentMethodLabelAttribute()
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? $this->payment_method;
    }

    public function getStatusLabelAttribute()
    {
        return [
            'pending_payment' => 'بانتظار تأكيد الدفع',
            'new'             => 'جديد',
            'reviewed'        => 'تمت المراجعة',
            'completed'       => 'جاري الشحن',
            'delivered'       => 'تم التسليم',
            'cancelled'       => 'ملغي',
        ][$this->status] ?? $this->status;
    }
}
