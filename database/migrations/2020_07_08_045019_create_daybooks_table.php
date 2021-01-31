<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaybooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daybooks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('date')->nullable();
            $table->boolean('collection_data')->default(0);
            $table->boolean('purchase_data')->default(0);
            $table->boolean('payment_data')->default(0);
            $table->string('collection_from')->nullable();
            $table->string('collection_amount')->nullable();
            $table->string('purchase_from')->nullable();
            $table->string('purchase_item')->nullable();
            $table->string('purchase_amount')->nullable();
            $table->string('payment_to')->nullable();
            $table->string('payment_for')->nullable();
            $table->string('payment_amount')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->unsignedBigInteger('received_id')->nullable();
            $table->foreign('received_id')->references('id')->on('receiveds')->onDelete('cascade');
            $table->unsignedBigInteger('purchase_id')->nullable();
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
        Schema::dropIfExists('daybooks');
    }
}
