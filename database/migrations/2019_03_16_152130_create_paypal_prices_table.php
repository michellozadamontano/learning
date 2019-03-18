<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypal_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('plan_id');
	        $table->foreign('plan_id')->references('id')->on('paypal_plans');
            $table->decimal('price',8,2);
            $table->string('paypal_code')->nullable();          
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
        Schema::dropIfExists('paypal_prices');
    }
}
