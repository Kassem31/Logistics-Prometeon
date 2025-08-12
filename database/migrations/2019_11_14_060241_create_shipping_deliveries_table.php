<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_deliveries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('shipping_id');
            $table->date('atco_date')->nullable();
            $table->date('sap_date')->nullable();
            $table->date('bwh_date')->nullable();
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
        Schema::dropIfExists('shipping_deliveries');
    }
}
