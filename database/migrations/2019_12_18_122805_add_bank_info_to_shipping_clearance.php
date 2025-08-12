<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankInfoToShippingClearance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_clearances', function (Blueprint $table) {
            $table->unsignedInteger('broker_id')->nullable();
            $table->unsignedInteger('custom_system_id')->nullable();

            $table->unsignedInteger('bank_id')->nullable();
            $table->unsignedInteger('invoice_currency_id')->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('bank_letter_date')->nullable();
            $table->date('delivery_bank_date')->nullable();
            $table->date('form4_issue_date')->nullable();
            $table->date('form4_rec_date')->nullable();
            $table->string('form4_number')->nullable();
            $table->date('form6_issue_date')->nullable();
            $table->date('form6_rec_date')->nullable();
            $table->date('transit_issue_date')->nullable();
            $table->date('transit_rec_date')->nullable();
            $table->string('transit_storage_letter')->nullable();
            $table->string('lg_number')->nullable();
            $table->date('bank_in_date')->nullable();
            $table->date('bank_out_date')->nullable();
            $table->date('bank_rec_date')->nullable();
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
            $table->dropColumn('broker_id');
            $table->dropColumn('custom_system_id');

            $table->dropColumn('bank_id');
            $table->dropColumn('invoice_currency_id');
            $table->dropColumn('invoice_date');
            $table->dropColumn('bank_letter_date');
            $table->dropColumn('delivery_bank_date');
            $table->dropColumn('form4_issue_date');
            $table->dropColumn('form4_rec_date');
            $table->dropColumn('form4_number');
            $table->dropColumn('form6_issue_date');
            $table->dropColumn('form6_rec_date');
            $table->dropColumn('transit_issue_date');
            $table->dropColumn('transit_rec_date');
            $table->dropColumn('transit_storage_letter');
            $table->dropColumn('lg_number');
            $table->dropColumn('bank_in_date');
            $table->dropColumn('bank_out_date');
            $table->dropColumn('bank_rec_date');
        });
    }
}
