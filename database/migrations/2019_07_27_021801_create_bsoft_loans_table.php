<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsoftLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsoft_loans', function (Blueprint $table) {
            $table->bigIncrements('loan_id');
            $table->string('loan_name');
            $table->string('loan_from');
            $table->string('loan_no')->nullable();
            $table->double('loan_amount', 15, 2);
            $table->double('loan_paid', 15, 2)->default(0.00);
            $table->text('loan_note')->nullable();
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
        Schema::dropIfExists('bsoft_loans');
    }
}
