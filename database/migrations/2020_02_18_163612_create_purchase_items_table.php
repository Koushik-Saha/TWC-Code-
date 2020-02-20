<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('item_id');
            $table->double('price',15, 2);
            $table->double('vat',15, 2)->nullable();
            $table->bigInteger('quantity');
            $table->double('amount',15, 2)->nullable();
            $table->bigInteger('user_id');
            $table->string('request_code');
            $table->boolean('status')->default(0);
            $table->double('payment_amount')->nullable();
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
        Schema::dropIfExists('purchase_items');
    }
}
