<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingClearancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_clearances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('shipping_id');
            $table->unsignedInteger('broker_id');
            $table->unsignedInteger('custom_system_id');
            $table->date('do_date')->nullable();
            $table->date('registeration_date')->nullable();
            $table->date('inspection_date')->nullable();
            $table->date('withdraw_date')->nullable();
            $table->date('result_date')->nullable();

            $table->date('lg_request_date')->nullable();
            $table->date('lg_issuance_date')->nullable();
            $table->date('sent_to_bank_date')->nullable();
            $table->date('lg_broker_receipt_date')->nullable();
            $table->decimal('lg_amount')->nullable();
            $table->unsignedInteger('lg_currency_id')->nullable();

            $table->date('form_date')->nullable();
            $table->date('broker_receipt_date')->nullable();
            $table->decimal('amount')->nullable();
            $table->unsignedInteger('form_currency_id')->nullable();
            $table->string('invoice_no')->nullable();


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
        Schema::dropIfExists('shipping_clearances');
    }
}
