<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('shipping_id');
            $table->date('ets')->nullable();
            $table->date('eta')->nullable();
            $table->date('ats')->nullable();
            $table->date('ata')->nullable();
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
        Schema::dropIfExists('shipping_bookings');
    }
}
