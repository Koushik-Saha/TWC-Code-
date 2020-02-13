<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsoftPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsoft_payments', function (Blueprint $table) {
            $table->bigIncrements('payment_id');
            $table->string('payment_type')->nullable(); // debit, credit
            $table->unsignedBigInteger('payment_to_user')->nullable();
            $table->unsignedBigInteger('payment_from_user')->nullable();
            $table->unsignedBigInteger('payment_to_bank_account')->nullable();
            $table->unsignedBigInteger('payment_from_bank_account')->nullable();
            $table->unsignedBigInteger('payment_for_project')->nullable();
            $table->string('payment_purpose'); // salary, project_money, expense, buy_item, office_transfer
            $table->string('payment_by')->default('cash'); // check, bank
            $table->float('payment_amount', 15, 2);
            $table->date('payment_date');
            $table->boolean('payment_withdrawn')->default(false);
            $table->string('payment_image')->nullable();
            $table->text('payment_note')->nullable();
            $table->timestamps();

            $table->foreign('payment_to_user')->references('id')->on('bsoft_users');
            $table->foreign('payment_from_user')->references('id')->on('bsoft_users');

            $table->foreign('payment_to_bank_account')->references('bank_id')->on('bsoft_bank_accounts');
            $table->foreign('payment_from_bank_account')->references('bank_id')->on('bsoft_bank_accounts');

            $table->foreign('payment_for_project')->references('project_id')->on('bsoft_projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bsoft_payments');
    }
}
