<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateColorsTable extends Migration
{
    private const COLORS = [
        'أسود', 'أبيض', 'أوف وايت', 'سكري', 'بيج', 'رمادي', 'فضي',
        'ذهبي', 'بني', 'جملي', 'كحلي', 'أزرق', 'سماوي', 'تركواز',
        'أخضر', 'زيتي', 'نعناعي', 'أصفر', 'خردلي', 'برتقالي',
        'أحمر', 'عنابي', 'وردي', 'فوشيا', 'بنفسجي', 'ليلكي',
        'موف', 'خوخي', 'نحاسي', 'شفاف', 'متعدد الألوان',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        $now = now();
        $rows = array_map(fn($name) => ['name' => $name, 'created_at' => $now, 'updated_at' => $now], self::COLORS);
        DB::table('colors')->insertOrIgnore($rows);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('colors');
    }
}
