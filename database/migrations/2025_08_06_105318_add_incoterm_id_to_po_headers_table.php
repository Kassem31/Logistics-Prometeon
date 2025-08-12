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
        Schema::table('p_o_headers', function (Blueprint $table) {
            // add incoterm foreign key
            $table->unsignedBigInteger('incoterm_id')->nullable()->after('status');
            $table->foreign('incoterm_id')->references('id')->on('inco_terms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('p_o_headers', function (Blueprint $table) {
            $table->dropForeign(['incoterm_id']);
            $table->dropColumn('incoterm_id');
        });
    }
};
