<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('vat_pan')->nullable();
            $table->string('particular')->nullable();
            $table->string('sales_to')->nullable();
            $table->integer('bill_no')->nullable();
            $table->string('type')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->string('vat_date')->nullable();
            $table->unsignedBigInteger('invoice_id')->unsigned();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->float('taxable_amount',15,4)->nullable();
            $table->float('vat_paid',15,4)->nullable();
            $table->float('total_paid',15,4)->nullable();
            $table->string('payment_type')->nullable();
            $table->integer('paid_from_bank')->nullable();
            $table->float('total',15,4)->nullable();
            $table->float('round_total',15,4)->nullable();
            $table->string('bill_image')->nullable();
            $table->boolean('not_vat')->default(0);
            $table->boolean('collected')->default(0);
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
        Schema::dropIfExists('sales');
    }
}
