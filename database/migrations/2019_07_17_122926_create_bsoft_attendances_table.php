<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsoftAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsoft_attendances', function (Blueprint $table) {
            $table->bigIncrements('attendance_id');
            $table->unsignedBigInteger('attendance_user_id');
            $table->unsignedBigInteger('attendance_project_id');
            $table->unsignedBigInteger('attendance_shift_id')->nullable();
            $table->date('attendance_date');
            $table->float('attendance_payable_amount', 15, 2);
            $table->float('attendance_paid_amount', 15, 2)->nullable();
            $table->text('attendance_note')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();

            $table->foreign('attendance_user_id')->references('id')->on('bsoft_users');
            $table->foreign('attendance_project_id')->references('project_id')->on('bsoft_projects');
            $table->foreign('attendance_shift_id')->references('shift_id')->on('bsoft_working_shifts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bsoft_attendances');
    }
}
