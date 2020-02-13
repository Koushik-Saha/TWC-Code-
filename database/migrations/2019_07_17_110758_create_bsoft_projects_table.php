<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsoftProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsoft_projects', function (Blueprint $table) {
            $table->bigIncrements('project_id');
            $table->string('project_name');
            $table->string('project_location');
            $table->unsignedBigInteger('project_client_id')->nullable();
            $table->float('project_price',15,2);
            $table->string('project_status');
            $table->longText('project_description')->nullable();
            $table->string('project_image')->nullable();
            $table->timestamps();

            $table->foreign('project_client_id')->references('id')->on('bsoft_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bsoft_projects');
    }
}
