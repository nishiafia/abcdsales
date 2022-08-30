<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchaseorders', function (Blueprint $table) {
            $table->id();
            $table->string('ordernumber');
            $table->integer('vendorid');
            $table->integer('refpo')->nullable();
            $table->longtext('notes')->nullable();
            $table->integer('systemid')->default(0);
            $table->integer('entryid')->default(0);
            $table->integer('companyid')->default(0);
            $table->integer('branchid')->default(0);
            $table->float('totalamount')->default(0);
            $table->float('paidamount')->default(0);
            $table->float('dueamount')->default(0);
            $table->float('totaltax')->default(0);
            $table->float('totaldiscount')->default(0);
            $table->float('shipping')->default(0);
            $table->date('currentdate');
            $table->date('deliverydate');
            $table->date('duedeliverydate')->nullable();
            $table->boolean('isactive')->default(true);
            $table->integer('orderdelivered')->default(0);
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
        Schema::dropIfExists('purchaseorders');
    }
}
