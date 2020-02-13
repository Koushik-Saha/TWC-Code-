<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsoftInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsoft_invoices', function (Blueprint $table) {
            $table->bigIncrements('invoice_id');
            $table->unsignedBigInteger('invoice_item_log');
            $table->string('invoice_name')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('invoice_address', 255)->nullable();
            $table->string('invoice_address_from', 255)->nullable();
            $table->string('invoice_address_to', 255)->nullable();
            $table->timestamps();

            $table->foreign('invoice_item_log')->references('il_id')->on('bsoft_item_logs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bsoft_invoices');
    }
}
