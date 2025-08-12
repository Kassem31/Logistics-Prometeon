<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInboundContainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inbound_containers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('inbound_id');
            $table->string('container_no')->nullable();
            $table->unsignedInteger('container_size_id')->nullable();
            $table->unsignedInteger('load_type_id')->nullable();
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
        Schema::dropIfExists('inbound_containers');
    }
}
