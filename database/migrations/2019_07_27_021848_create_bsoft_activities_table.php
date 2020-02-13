<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsoftActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsoft_activities', function (Blueprint $table) {
            $table->bigIncrements('activity_id');
            $table->unsignedBigInteger('activity_of_user_id');
            $table->longText('activity_note');
            $table->unsignedBigInteger('activity_for_user_id')->nullable();
            $table->unsignedBigInteger('activity_project_id')->nullable();
            $table->unsignedBigInteger('activity_shift_id')->nullable();
            $table->unsignedBigInteger('activity_attendance_id')->nullable();
            $table->unsignedBigInteger('activity_item_id')->nullable();
            $table->unsignedBigInteger('activity_item_log_id')->nullable();
            $table->unsignedBigInteger('activity_payment_id')->nullable();
            $table->unsignedBigInteger('activity_option_id')->nullable();
            $table->unsignedBigInteger('activity_bank_id')->nullable();
            $table->unsignedBigInteger('activity_invoice_id')->nullable();
            $table->unsignedBigInteger('activity_loan_id')->nullable();
            $table->timestamp('created_at')->nullable();


            $table->foreign('activity_of_user_id')->references('id')->on('bsoft_users');
            /*$table->foreign('activity_for_user_id')->references('id')->on('bsoft_users');
            $table->foreign('activity_project_id')->references('project_id')->on('bsoft_projects');
            $table->foreign('activity_shift_id')->references('shift_id')->on('bsoft_working_shifts')->onDelete('cascade');
            $table->foreign('activity_attendance_id')->references('attendance_id')->on('bsoft_attendances');
            $table->foreign('activity_item_id')->references('item_id')->on('bsoft_items');
            $table->foreign('activity_item_log_id')->references('il_id')->on('bsoft_item_logs');
            $table->foreign('activity_payment_id')->references('payment_id')->on('bsoft_payments');
            $table->foreign('activity_option_id')->references('option_id')->on('bsoft_options');
            $table->foreign('activity_bank_id')->references('bank_id')->on('bsoft_bank_accounts');
            $table->foreign('activity_invoice_id')->references('invoice_id')->on('bsoft_invoices');
            $table->foreign('activity_loan_id')->references('loan_id')->on('bsoft_loans');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bsoft_activities');
    }
}
