<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataToPaypalSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paypal_subscriptions', function (Blueprint $table) {
            $table->string('country')->nullable()->after('paypal_email'); 
            $table->string('city')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paypal_subscriptions', function ($table) {
            $table->dropColumn('country');
            $table->dropColumn('city');
        });
    }
}
