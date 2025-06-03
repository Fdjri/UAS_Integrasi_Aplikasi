<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePaymentIdToStringOnPaymentsTable extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Ubah tipe kolom payment_id menjadi string dan unique
            $table->string('payment_id')->unique()->change();
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Jika rollback, ubah kembali ke bigint
            $table->bigInteger('payment_id')->change();
        });
    }
}
