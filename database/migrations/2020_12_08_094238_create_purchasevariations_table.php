<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasevariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasevariations', function (Blueprint $table) {
            $table->id();
            $table->integer('orderid')->default(0);
            $table->integer('peritemid')->default(0);
            $table->integer('itemid')->default(0);
            $table->integer('vlabelid')->default(0);
            $table->integer('variationid')->default(0);
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
        Schema::dropIfExists('purchasevariations');
    }
}
