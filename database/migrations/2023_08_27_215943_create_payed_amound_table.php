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
        Schema::create('payed_amounds', function (Blueprint $table) {

            //den exo kanei akoma migrate to sugkekrimeno
            $table->id();
            $table->float('price',8,2); //edo den ksero an prepei na balo real, Double, or something else, na to do
            $table->string('reason');
            $table->integer('category_id'); //foreign key to category tabe
            $table->unsignedBigInteger('user_id');     //foreign key to user table 
            $table->unsignedBigInteger('currency_id'); //foreign key to currency table
            $table->boolean('is_negative')->default(true);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
                                    //add and more columns in the structure
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payed_amound');
    }
};
