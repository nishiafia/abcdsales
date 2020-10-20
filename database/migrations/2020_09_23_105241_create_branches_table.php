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
            $table->text('address');
            $table->string('phone');
            $table->integer('branch_contact_person')->unsigned();
			$table->integer('branch_supervisor')->unsigned();
            $table->boolean('isactive')->default(true);
            $table->timestamps();
        });
        Schema::table('branches', function(Blueprint $table){
			$table->foreign('branch_contact_person')
					->references('id')
					->on('users');
			$table->foreign('branch_supervisor')
					->references('id')
					->on('users');
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
