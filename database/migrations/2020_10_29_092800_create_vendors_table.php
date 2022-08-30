<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendorname');
            $table->string('contactperson');
            $table->string('email')->nullable();
            $table->string('dialcode');
            $table->string('telephone')->unique();
            $table->text('address')->nullable();
            $table->longtext('description')->nullable();
            $table->integer('businesscategory')->default(0);
            $table->integer('systemid')->default(0);
            $table->integer('entryid')->default(0);
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
        Schema::dropIfExists('vendors');
    }
}
