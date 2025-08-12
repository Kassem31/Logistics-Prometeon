<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingBasicInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_basic_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->unsignedInteger('raw_material_id')->nullable();
            // $table->unsignedInteger('person_in_charge_id')->nullable();
            // $table->unsignedInteger('supplier_id')->nullable();
            // $table->unsignedInteger('shipping_unit_id')->nullable();
            $table->unsignedInteger('container_size_id')->nullable();
            $table->unsignedInteger('load_type_id')->nullable();
            $table->unsignedInteger('origin_country_id')->nullable();
            $table->unsignedInteger('loading_port_id')->nullable();
            $table->unsignedInteger('inco_term_id')->nullable();
            $table->unsignedInteger('inco_forwarder_id')->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->unsignedInteger('shipping_line_id')->nullable();
            $table->unsignedInteger('container_count')->nullable();
            $table->unsignedDecimal('rate')->nullable();
            // $table->unsignedInteger('qty')->nullable();
            // $table->string('sap_inbound')->nullable();
            // $table->string('po_number')->nullable();
            // $table->date('order_date')->nullable();
            // $table->date('due_date')->nullable();
            $table->string('vessel_name')->nullable();
            $table->string('bl_number')->nullable();
            $table->string('other_shipping_line')->nullable();

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
        Schema::dropIfExists('shipping_basic_infos');
    }
}
