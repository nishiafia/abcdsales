<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('pid')->default(0);
            $table->string('productcode')->unique();
            $table->integer('groupid')->default(0);
            $table->string('productname');
            $table->longtext('details')->nullable();
            $table->integer('productunit')->default(0);
            $table->integer('unittype')->default(0);
            $table->float('productcost');
            $table->float('sellprice');
            $table->integer('purchaseproduct')->default(0);
            $table->integer('salesproduct')->default(0);
            $table->integer('systemid')->default(0);
            $table->integer('entryid')->default(0);
            $table->integer('companyid')->default(0);
            $table->integer('branchid')->default(0);
            $table->boolean('isactive')->default(true);
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
        Schema::dropIfExists('products');
    }
}
