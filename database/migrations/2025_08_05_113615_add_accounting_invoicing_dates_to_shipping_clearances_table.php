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
        Schema::table('shipping_clearances', function (Blueprint $table) {
            $table->date('received_accounting_date')->nullable()->after('bank_rec_date');
            $table->date('invoicing_date')->nullable()->after('received_accounting_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_clearances', function (Blueprint $table) {
            $table->dropColumn(['received_accounting_date', 'invoicing_date']);
        });
    }
};
