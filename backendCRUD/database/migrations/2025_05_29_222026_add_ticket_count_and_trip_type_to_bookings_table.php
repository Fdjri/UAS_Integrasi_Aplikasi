<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTicketCountAndTripTypeToBookingsTable extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Jumlah tiket untuk event dan transportasi
            $table->unsignedInteger('ticket_count')->nullable()->after('check_out');

            // Tipe perjalanan untuk transportasi: pergi / pulang-pergi
            $table->enum('trip_type', ['pergi', 'pulang-pergi'])->nullable()->after('ticket_count');
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['ticket_count', 'trip_type']);
        });
    }
}
