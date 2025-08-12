<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePOHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_o_headers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('supplier_id')->nullable();
            $table->unsignedInteger('person_in_charge_id')->nullable();
            $table->string('po_number')->nullable();
            $table->date('order_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('p_o_headers');
    }
}
