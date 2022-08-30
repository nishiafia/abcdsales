<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseaccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchaseaccounts', function (Blueprint $table) {
            $table->id();
            $table->integer('orderid')->default(0);
            $table->integer('systemid')->default(0);
            $table->integer('entryid')->default(0);
            $table->boolean('refund')->default(false);
            $table->date('paymentdate')->nullable;
            $table->integer('paymentmethod')->nullable();
            $table->float('payamount')->default(0);
            $table->string('dialcode')->nullable();
            $table->string('bkashnumber')->nullable();
            $table->string('bkashpin')->nullable();
            $table->string('cashpay')->nullable();
            $table->string('bankid')->nullable();
            $table->string('accountholder')->nullable();
            $table->string('accountnumber')->nullable();
            $table->string('branchname')->nullable();
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
        Schema::dropIfExists('purchaseaccounts');
    }
}
