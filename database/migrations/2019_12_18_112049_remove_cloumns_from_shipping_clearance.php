<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCloumnsFromShippingClearance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_clearances', function (Blueprint $table) {
            $table->dropColumn('broker_id');
            $table->dropColumn('custom_system_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_clearances', function (Blueprint $table) {
            $table->unsignedInteger('broker_id')->nullable();
            $table->unsignedInteger('custom_system_id')->nullable();
        });
    }
}
