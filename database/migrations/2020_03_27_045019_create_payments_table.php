<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('date')->nullable();
            $table->text('paid_to')->nullable();
            $table->integer('bank_id')->nullable();
            $table->string('bank')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('payment_for')->nullable();
            $table->text('cheque_number')->nullable();
            $table->string('amount')->nullable();
            $table->text('narration')->nullable();
            $table->integer('paymentgateway_id')->nullable();
            $table->string('payment_gateway')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
