<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PreorderProductSeeder::class);

        User::updateOrCreate(
            ['email' => 'admin@seva.com'],
            [
                'name' => 'SEVA Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        $categories = [
            ['name' => 'عبايات', 'slug' => 'abayas'],
            ['name' => 'ملابس نسائي', 'slug' => 'women'],
            ['name' => 'ملابس رجالي', 'slug' => 'men'],
            ['name' => 'اكسسوارات', 'slug' => 'accessories'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category + ['active' => true]);
        }

        $abayas = Category::where('slug', 'abayas')->first();

        if ($abayas && Item::count() === 0) {
            Item::create([
                'category_id' => $abayas->id,
                'name' => 'عباية مطرزة بالذهب',
                'type' => 'عباية',
                'image' => 'https://media.base44.com/images/public/6a13fa7991dd03ed90e4aa3f/943a3cf7d_generated_image.png',
                'price' => 1530,
                'old_price' => 1800,
                'discount' => 15,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['أسود', 'ذهبي'],
                'quality' => 'قماش فاخر مطرز',
                'material' => 'كريب',
                'origin' => 'تركيا',
                'stock' => 12,
                'featured' => true,
                'active' => true,
            ]);
        }
    }
}
