<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsoftProjectLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsoft_project_logs', function (Blueprint $table) {
            $table->bigIncrements('pl_id');
            $table->unsignedBigInteger('pl_user_id');
            $table->unsignedBigInteger('pl_project_id');
            $table->timestamps();

            $table->foreign('pl_user_id')->references('id')->on('bsoft_users');
            $table->foreign('pl_project_id')->references('project_id')->on('bsoft_projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bsoft_project_logs');
    }
}
