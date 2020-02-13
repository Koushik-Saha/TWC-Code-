<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsoftBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsoft_bank_accounts', function (Blueprint $table) {
            $table->bigIncrements('bank_id');
            $table->string('bank_account_name');
            $table->string('bank_account_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->unsignedBigInteger('bank_user_id')->nullable();
            $table->double('bank_balance', 15, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('bank_user_id')->references('id')->on('bsoft_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bsoft_bank_accounts');
    }
}
