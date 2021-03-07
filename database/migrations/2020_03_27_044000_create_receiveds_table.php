
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceivedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receiveds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('date')->nullable();
            $table->integer('invoice_id')->unsigned();
            $table->text('particular')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('amount')->nullable();
            $table->integer('bank_id')->nullable();
            $table->integer('deposited_at_bank')->nullable();
            $table->string('transfered_to_wallet')->nullable();
            $table->integer('paymentgateway_id')->nullable();
            $table->string('cheque_of_bank')->nullable();
            $table->string('cheque_number')->nullable();
            $table->boolean('keep_at_office', 0, 1)->default(0);
            $table->text('narration')->nullable();
            $table->string('received_from')->nullable();
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
        Schema::dropIfExists('receiveds');
    }
}
