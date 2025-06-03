<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePaymentIdToBigintOnPaymentsTable extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Ubah payment_id menjadi bigint unsigned auto-increment primary key
            $table->bigIncrements('payment_id')->change();
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Ubah kembali ke tipe sebelumnya (misal int)
            $table->increments('payment_id')->change();
        });
    }
}

