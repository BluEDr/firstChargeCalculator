<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payed_amounds', function (Blueprint $table) {
            // $table->integer('user_id')->unsigned();
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // $table->integer('currency_id')->unsigned();
            // $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');

            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payed_amound', function (Blueprint $table) {
            //
        });
    }
};
