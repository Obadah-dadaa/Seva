<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class BackfillItemVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Seed one variant row per (color, size) combination for every existing
     * item, using the item's current flat stock as the initial per-combination
     * value. The admin can correct real per-combination numbers afterwards.
     *
     * @return void
     */
    public function up()
    {
        $now = now();

        DB::table('items')->orderBy('id')->chunk(100, function ($items) use ($now) {
            $rows = [];

            foreach ($items as $item) {
                $colors = json_decode($item->colors, true) ?: [];
                $sizes = json_decode($item->sizes, true) ?: [];

                foreach ($colors as $color) {
                    foreach ($sizes as $size) {
                        $rows[] = [
                            'item_id' => $item->id,
                            'color' => $color,
                            'size' => $size,
                            'stock' => $item->stock,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
            }

            if (!empty($rows)) {
                DB::table('item_variants')->insertOrIgnore($rows);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('item_variants')->truncate();
    }
}
