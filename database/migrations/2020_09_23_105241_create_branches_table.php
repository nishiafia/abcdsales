<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('branchname');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->integer('contactperson')->default(0);
            $table->integer('systemid')->default(0);
            $table->integer('entryid')->default(0);
			$table->integer('companyid')->default(0);
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
        Schema::dropIfExists('branches');
    }
}
