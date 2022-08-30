<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesordercommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesordercomments', function (Blueprint $table) {
            $table->id();
            $table->integer('orderid')->default(0);
            $table->integer('systemid')->default(0);
            $table->integer('entryid')->default(0);
            $table->integer('companyid')->default(0);
            $table->integer('branchid')->default(0);
            $table->longtext('comments')->nullable();
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
        Schema::dropIfExists('salesordercomments');
    }
}
