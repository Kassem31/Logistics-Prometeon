<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('p_o_details', function (Blueprint $table) {
            // Removed incoterm_id per requirements
            $table->unsignedBigInteger('origin_country_id')->nullable()->after('shipping_unit_id');
            $table->date('item_due_date')->nullable()->after('origin_country_id');
            $table->date('amendment_date')->nullable()->after('item_due_date');
            
            $table->foreign('origin_country_id')->references('id')->on('countries')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('p_o_details', function (Blueprint $table) {
            $table->dropForeign(['origin_country_id']);
            $table->dropColumn(['origin_country_id', 'item_due_date', 'amendment_date']);
        });
    }
};
