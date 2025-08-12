<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('shipping_id');
            $table->date('invoice_copy')->nullable();
            $table->date('purchase_confirmation')->nullable();
            $table->date('original_invoice')->nullable();
            $table->date('stamped_invoice')->nullable();
            $table->date('copy_docs')->nullable();
            $table->date('original_docs')->nullable();
            $table->date('copy_docs_broker')->nullable();
            $table->date('original_docs_broker')->nullable();
            $table->date('stamped_invoice_broker')->nullable();
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
        Schema::dropIfExists('shipping_documents');
    }
}
