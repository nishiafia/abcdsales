<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesorderitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesorderitems', function (Blueprint $table) {
            $table->id();
            $table->integer('orderid')->default(0);
            $table->integer('pitem')->default(0);
            $table->integer('gcode')->default(0);
            $table->text('detail')->nullable();   
            $table->integer('qty')->default(0);
            $table->float('price')->default(0);
            $table->integer('excat')->nullable()->default(0);            
            $table->string('discountid')->default(0,0);
            $table->string('taxid')->default(0,0);
            $table->integer('systemid')->default(0);
            $table->integer('entryid')->default(0);
            $table->float('total')->default(0);
            $table->float('discountfigure')->default(0);
            $table->float('taxfigure')->default(0);
            $table->boolean('delivered')->default(false);
            $table->boolean('returnitem')->default(false);
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
        Schema::dropIfExists('salesorderitems');
    }
}
