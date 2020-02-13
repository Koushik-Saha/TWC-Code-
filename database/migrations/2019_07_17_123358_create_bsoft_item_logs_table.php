<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsoftItemLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsoft_item_logs', function (Blueprint $table) {
            $table->bigIncrements('il_id');
            $table->unsignedBigInteger('il_item_id');
            $table->unsignedBigInteger('il_vendor_id')->nullable();
            $table->unsignedBigInteger('il_project_from')->nullable();
            $table->unsignedBigInteger('il_project_id')->nullable();
            $table->float('il_quantity', 15, 2);
            $table->float('il_cost', 15, 2)->default(0);
            $table->float('il_per_cost', 15, 2)->default(0);
            $table->float('il_paid_amount', 15, 2)->default(0);
            $table->text('il_note')->nullable();
            $table->timestamps();

            $table->foreign('il_vendor_id')->references('id')->on('bsoft_users');
            $table->foreign('il_item_id')->references('item_id')->on('bsoft_items');
            $table->foreign('il_project_id')->references('project_id')->on('bsoft_projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bsoft_item_logs');
    }
}
