<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeEmailNullablePhoneUniqueInUsers extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE users MODIFY COLUMN email VARCHAR(255) NULL');

        Schema::table('users', function (Blueprint $table) {
            $table->unique('phone', 'users_phone_unique');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_phone_unique');
        });

        DB::statement('ALTER TABLE users MODIFY COLUMN email VARCHAR(255) NOT NULL');
    }
}
