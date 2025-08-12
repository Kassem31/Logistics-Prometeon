<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('level_1');
            $table->string('l1_route')->nullable();
            $table->unsignedInteger('l1_order')->nullable();
            $table->text('l1_meta')->nullable();
            $table->string('level_2')->nullable();
            $table->string('l2_route')->nullable();
            $table->unsignedInteger('l2_order')->nullable();
            $table->text('l2_meta')->nullable();
            $table->string('level_3')->nullable();
            $table->string('l3_route')->nullable();
            $table->unsignedInteger('l3_order')->nullable();
            $table->text('l3_meta')->nullable();
            $table->unsignedInteger('permission_id')->nullable();
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
        Schema::dropIfExists('menus');
    }
}
