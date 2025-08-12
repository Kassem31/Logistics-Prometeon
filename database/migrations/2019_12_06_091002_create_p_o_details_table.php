<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePODetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_o_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('row_no')->nullable();
            $table->unsignedInteger('po_header_id');
            $table->unsignedInteger('raw_material_id')->nullable();
            $table->unsignedDecimal('qty')->nullable();
            $table->unsignedInteger('shipping_unit_id')->nullable();
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
        Schema::dropIfExists('p_o_details');
    }
}
