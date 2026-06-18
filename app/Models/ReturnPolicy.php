<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge',
        'title',
        'subtitle',
        'sections',
        'note',
    ];

    protected $casts = [
        'sections' => 'array',
    ];

    /**
     * Get the single return-policy record, creating it from the default
     * content the first time it is requested.
     */
    public static function current(): self
    {
        return static::first() ?: static::create(static::defaults());
    }

    /**
     * Default content — mirrors the original static returns page so the
     * public page looks identical until an admin edits it.
     */
    public static function defaults(): array
    {
        return [
            'badge' => 'خدمة العملاء',
            'title' => 'سياسة الاسترداد والتبديل',
            'subtitle' => 'رضاكم غايتنا — نضمن لكم تجربة تسوق مريحة وآمنة',
            'note' => '<strong>⚠️ استثناءات —</strong> لا يشمل الاسترداد: المنتجات المفصّلة أو المخصصة حسب الطلب، وبعض الإكسسوارات لأسباب صحية. في حال الشك، تواصل معنا قبل الشراء.',
            'sections' => [
                [
                    'icon' => '📅',
                    'title' => 'مدة الاسترداد',
                    'type' => 'paragraph',
                    'body' => 'يحق لك طلب الاسترداد أو التبديل خلال <strong>14 يوماً</strong> من تاريخ استلام طلبك.',
                ],
                [
                    'icon' => '✅',
                    'title' => 'شروط الاسترداد',
                    'type' => 'list',
                    'body' => "المنتج في حالته الأصلية دون استخدام أو غسيل\nالبطاقات والعلامات التجارية لا تزال مرفقة\nالتغليف الأصلي محفوظ قدر الإمكان\nرقم الطلب متاح للتحقق",
                ],
                [
                    'icon' => '🔄',
                    'title' => 'كيفية طلب الاسترداد',
                    'type' => 'list',
                    'body' => "تواصل معنا عبر واتساب أو الهاتف\nأذكر رقم طلبك والسبب\nسنتواصل معك لترتيب استلام المنتج",
                ],
                [
                    'icon' => '💳',
                    'title' => 'إعادة المبلغ',
                    'type' => 'paragraph',
                    'body' => 'يُعاد المبلغ بنفس طريقة الدفع الأصلية خلال <strong>3–5 أيام عمل</strong> من استلام المنتج والتحقق منه.',
                ],
            ],
        ];
    }
}
