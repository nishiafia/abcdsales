<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryagentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveryagents', function (Blueprint $table) {
            $table->id();
            $table->string('agentname');
            $table->integer('systemid')->default(0);
            $table->integer('entryid')->default(0);
            $table->integer('companyid')->default(0);
            $table->integer('branchid')->default(0);
            $table->boolean('isactive')->default(true);
            $table->float('insiderate')->default(0);
            $table->float('insidecodcharge')->default(0);
            $table->float('outsiderate')->default(0);
            $table->float('outsidecodcharge')->default(0);
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
        Schema::dropIfExists('deliveryagents');
    }
}
