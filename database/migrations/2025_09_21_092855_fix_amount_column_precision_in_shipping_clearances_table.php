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
            // Change amount column to have proper precision (15 digits total, 2 decimal places)
            $table->decimal('amount', 15, 2)->nullable()->change();
            // Also fix lg_amount column if it has the same issue
            $table->decimal('lg_amount', 15, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_clearances', function (Blueprint $table) {
            // Revert to original decimal() definition
            $table->decimal('amount')->nullable()->change();
            $table->decimal('lg_amount')->nullable()->change();
        });
    }
};
