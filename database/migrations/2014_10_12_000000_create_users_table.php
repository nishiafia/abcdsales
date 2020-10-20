<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('dialcode');
            $table->string('telephone')->unique();
            $table->timestamp('telephone_verified_at')->nullable();
            $table->string('password');
            $table->string('usertype');
            $table->integer('businesscategory')->default(0);
            $table->integer('systemid')->default(0);
            $table->integer('companyid')->default(0);
            $table->integer('partnertype')->default(0);
            $table->text('address')->nullable();
            $table->boolean('isactive')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}

 