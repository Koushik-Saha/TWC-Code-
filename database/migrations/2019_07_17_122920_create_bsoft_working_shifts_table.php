<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsoftWorkingShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsoft_working_shifts', function (Blueprint $table) {
            $table->bigIncrements('shift_id');
            $table->unsignedBigInteger('shift_project_id');
            $table->string('shift_name');
            $table->time('shift_start');
            $table->time('shift_end');

            $table->foreign('shift_project_id')->references('project_id')
                ->on('bsoft_projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bsoft_working_shifts');
    }
}
