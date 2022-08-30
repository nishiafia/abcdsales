<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->string('taxtitle')->nullable();
            $table->string('abbreviation')->nullable();
            $table->float('taxrate')->default(0);
            $table->integer('recoverable')->default(0);
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
        Schema::dropIfExists('taxes');
    }
}
