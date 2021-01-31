<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_address')->nullable();
            $table->integer('client_id')->nullable();
            $table->string('contact')->nullable();
            $table->string('date')->nullable();
            $table->string('nepali_date')->nullable();
            $table->string('total')->nullable();
            $table->string('email')->nullable();
            $table->string('filename')->nullable();
            $table->text('serialized_cc')->nullable();
            $table->text('cc')->nullable();
            $table->string('vat')->nullable();
            $table->string('words')->nullable();
            $table->string('grand_total')->nullable();
            $table->integer('vat_id')->nullable();
            $table->integer('sales_id')->nullable();
            $table->boolean('collected')->default(0);
            $table->boolean('sales_without_vat')->nullable();
            $table->boolean('sales_without_vat_collected')->default(0);
            $table->string('collected_type')->nullable();
            $table->string('collected_amount')->default(0);
            $table->string('remaining_amount_to_be_collected')->default(0);
            $table->double('tds_amount',11,3)->default(0);
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
        Schema::dropIfExists('invoices');
    }
}
