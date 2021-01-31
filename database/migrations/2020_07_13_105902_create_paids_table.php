<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paids', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('date')->nullable();
            $table->text('particular')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('amount')->nullable();
            //$table->integer('bank_id')->nullable();
            $table->integer('paid_through_bank')->nullable();
            $table->string('transfered_through_wallet')->nullable();
            $table->integer('paymentgateway_id')->nullable();
            $table->integer('purchase_id')->unsigned();
            $table->string('cheque_of_bank')->nullable();
            $table->text('narration')->nullable();
            $table->string('paid_to')->nullable();
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
        Schema::dropIfExists('paids');
    }
}
