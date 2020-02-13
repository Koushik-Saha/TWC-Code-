<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateInventoryManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_managements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('mother_category_id');
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('sub_category_id')->nullable();
            $table->bigInteger('manufacture_id')->nullable();
            $table->string('item_name')->unique();
            $table->string('item_unit');
            $table->double('item_price', 15, 2);
            $table->boolean('item_reusable')->default(false);
            $table->string('item_image')->nullable();
            $table->timestamps();

//            $table->foreign('mother_category_id')->references('id')->on('mother_categories');
//            $table->foreign('category_id')->references('id')->on('category');
//            $table->foreign('sub_category_id')->references('id')->on('sub_categories');
//            $table->foreign('manufacture_id')->references('id')->on('manufactures');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_managements');
    }
}
