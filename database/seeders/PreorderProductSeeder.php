<?php

namespace Database\Seeders;

use App\Models\PreorderProduct;
use Illuminate\Database\Seeder;

class PreorderProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'name' => 'عباية تفصيل كريب مطرزة',
                'type' => 'تفصيل حسب الطلب',
                'description' => 'موديل أنيق يمكن تنفيذه بالمقاس واللون المناسبين بعد الاتفاق على التفاصيل عبر واتساب.',
                'image' => 'https://media.base44.com/images/public/6a13fa7991dd03ed90e4aa3f/943a3cf7d_generated_image.png',
                'price_note' => 'السعر يحدد بعد اختيار القماش والتطريز',
                'estimated_delivery' => 'من 10 إلى 14 يوم',
                'quantity' => 1,
                'sort_order' => 1,
                'active' => true,
            ],
            [
                'name' => 'فستان سهرة بتفصيل خاص',
                'type' => 'وصاية خاصة',
                'description' => 'اختاري الفكرة أو الصورة المرجعية، ونرتب معك المقاس والخامة وموعد التسليم مباشرة.',
                'image' => 'https://media.base44.com/images/public/6a13fa7991dd03ed90e4aa3f/2a3b96142_generated_73656b53.png',
                'price_note' => 'يعطى السعر النهائي بعد تأكيد التصميم',
                'estimated_delivery' => 'من 14 إلى 21 يوم',
                'quantity' => 1,
                'sort_order' => 2,
                'active' => true,
            ],
            [
                'name' => 'طقم نسائي كتان صيفي',
                'type' => 'تأمين حسب الطلب',
                'description' => 'طقم عملي بخامة خفيفة، نثبت اللون والمقاس المتاحين قبل الطلب.',
                'image' => 'https://media.base44.com/images/public/6a13fa7991dd03ed90e4aa3f/520b4295e_generated_image.png',
                'price_note' => 'حسب الخامة والمقاس',
                'estimated_delivery' => 'من 7 إلى 12 يوم',
                'quantity' => 3,
                'sort_order' => 3,
                'active' => true,
            ],
            [
                'name' => 'عباية استقبال ناعمة',
                'type' => 'تفصيل خاص',
                'description' => 'تصميم رايق للزيارات والمناسبات الخفيفة مع إمكانية تعديل الكم والطول.',
                'image' => 'https://media.base44.com/images/public/6a13fa7991dd03ed90e4aa3f/0a736727f_generated_image.png',
                'price_note' => 'السعر بعد اختيار اللون والإضافات',
                'estimated_delivery' => 'من 10 إلى 16 يوم',
                'quantity' => 2,
                'sort_order' => 4,
                'active' => true,
            ],
            [
                'name' => 'شنطة كلاسيك جلد',
                'type' => 'وصاية إكسسوار',
                'description' => 'موديل كلاسيكي نبحث عنه أو نوفر بديله الأقرب حسب اللون والحجم المطلوب.',
                'image' => 'https://media.base44.com/images/public/6a13fa7991dd03ed90e4aa3f/b77e2ddc1_generated_1033d72c.png',
                'price_note' => 'حسب التوفر والماركة',
                'estimated_delivery' => 'من 5 إلى 10 أيام',
                'quantity' => 4,
                'sort_order' => 5,
                'active' => true,
            ],
            [
                'name' => 'قميص رجالي تفصيل',
                'type' => 'تفصيل بالمقاس',
                'description' => 'قميص رجالي بخيارات قماش متعددة، مع ضبط القياسات والتفاصيل عبر واتساب.',
                'image' => 'https://media.base44.com/images/public/6a13fa7991dd03ed90e4aa3f/b36d3924e_generated_e6f0e16b.png',
                'price_note' => 'حسب القماش وعدد القطع',
                'estimated_delivery' => 'من 8 إلى 14 يوم',
                'quantity' => 2,
                'sort_order' => 6,
                'active' => true,
            ],
            [
                'name' => 'تنورة ساتان مناسبة',
                'type' => 'تفصيل حسب اللون',
                'description' => 'تنورة ساتان بقصة ناعمة، مناسبة للتنسيق مع قمصان أو بلايز خاصة.',
                'image' => 'https://media.base44.com/images/public/6a13fa7991dd03ed90e4aa3f/ead4c6182_generated_image.png',
                'price_note' => 'السعر بعد تأكيد القماش والطول',
                'estimated_delivery' => 'من 9 إلى 13 يوم',
                'quantity' => 1,
                'sort_order' => 7,
                'active' => true,
            ],
        ];

        foreach ($products as $product) {
            PreorderProduct::updateOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}
