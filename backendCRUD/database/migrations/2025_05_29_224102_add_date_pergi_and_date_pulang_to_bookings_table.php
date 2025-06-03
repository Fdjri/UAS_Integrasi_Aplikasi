<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatePergiAndDatePulangToBookingsTable extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->date('date_pergi')->nullable()->after('trip_type');
            $table->date('date_pulang')->nullable()->after('date_pergi');
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['date_pergi', 'date_pulang']);
        });
    }
}
