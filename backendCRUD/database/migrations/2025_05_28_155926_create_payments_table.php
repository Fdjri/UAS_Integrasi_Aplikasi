<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('payment_id');
            $table->unsignedBigInteger('booking_id');
            $table->dateTime('payment_date');
            $table->decimal('amount', 12, 2);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');

            // Tambahan field opsional
            $table->string('method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->dateTime('payment_expiry')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->decimal('gross_amount', 12, 2)->nullable();
            $table->decimal('fee', 12, 2)->nullable();
            $table->string('currency')->default('IDR');
            $table->text('notes')->nullable();
            $table->string('status_reason')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
