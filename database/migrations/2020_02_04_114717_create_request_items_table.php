<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateRequestItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('mother_category_id')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('sub_category_id')->nullable();
            $table->bigInteger('manufacture_id')->nullable();
            $table->bigInteger('item_id')->nullable();
            $table->double('price',15, 2)->nullable();
            $table->double('vat',15, 2)->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->double('amount',15, 2)->nullable();
            $table->bigInteger('project_id')->nullable();
            $table->string('request_date')->nullable();
            $table->bigInteger('request_id')->nullable();
            $table->string('request_code')->nullable();
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
        Schema::dropIfExists('request_items');
    }
}
