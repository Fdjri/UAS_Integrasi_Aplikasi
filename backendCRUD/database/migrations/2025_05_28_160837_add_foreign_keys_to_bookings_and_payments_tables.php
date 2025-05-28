<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('service_id')->references('service_id')->on('services')->onDelete('cascade');
    });

    Schema::table('payments', function (Blueprint $table) {
        $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropForeign(['customer_id']);
        $table->dropForeign(['service_id']);
    });

    Schema::table('payments', function (Blueprint $table) {
        $table->dropForeign(['booking_id']);
    });
}
};
