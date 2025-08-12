<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInsuranceToBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_basic_infos', function (Blueprint $table) {
            $table->unsignedInteger('inbound_id')->nullable();
            $table->unsignedInteger('insurance_company_id')->nullable();
            $table->date('insurance_date')->nullable();
            $table->string('insurance_cert_no')->nullable();
            $table->dropColumn('container_size_id');
            $table->dropColumn('load_type_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_basic_infos', function (Blueprint $table) {
            $table->unsignedInteger('container_size_id')->nullable();
            $table->unsignedInteger('load_type_id')->nullable();
            $table->dropColumn('inbound_id');
            $table->dropColumn('insurance_company_id');
            $table->dropColumn('insurance_date');
            $table->dropColumn('insurance_cert_no');
        });
    }
}
