<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsoftItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsoft_items', function (Blueprint $table) {
            $table->bigIncrements('item_id');
            $table->string('item_name')->unique();
            $table->string('item_unit');
            $table->bigInteger('item_price');
            $table->bigInteger('item_price_final');
            $table->boolean('item_reusable')->default(false);
            $table->string('item_image')->nullable();
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
        Schema::dropIfExists('bsoft_items');
    }
}
