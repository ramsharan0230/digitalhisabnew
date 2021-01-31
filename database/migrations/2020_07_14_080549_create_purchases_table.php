<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('particular')->nullable();
            $table->string('purchased_from')->nullable();
            $table->string('purchased_item')->nullable();
            $table->string('bill_no')->nullable();
            $table->string('vat_date')->nullable();
            $table->string('taxable_amount')->nullable();
            $table->string('vat_paid')->nullable();
            $table->string('total_paid')->nullable();
            $table->integer('vendor_id')->unsigned();
            // $table->string('payment_type')->nullable();
            // $table->integer('paid_from_bank')->nullable();
            $table->string('total')->nullable();
            $table->string('round_total')->nullable();
            $table->string('bill_image')->nullable();
            $table->string('total_amount_of_purchase_amount_paid')->nullable();
            $table->boolean('not_vat')->default(0);
            $table->boolean('collected')->default(0);
            $table->string('collected_type')->nullable();
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
        Schema::dropIfExists('purchases');
    }
}
