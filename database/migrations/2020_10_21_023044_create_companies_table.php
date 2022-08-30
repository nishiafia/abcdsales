<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('companyname');
            $table->integer('businesscategory')->default(0);
            $table->string('country');
            $table->integer('thanaid')->default(0);
            $table->longtext('address')->nullable();
            $table->integer('companytype')->default(0);
            $table->integer('systemid')->default(0);
            $table->integer('entryid')->default(0);
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
        Schema::dropIfExists('companies');
    }
}
