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
            $table->float('price',8,2); //edo den ksero an prepei na balo real, Double, or something else
            $table->string('reason');
            $table->integer('category_id'); //foreign key to category tabe
            $table->integer('user_id');     //foreign key to user table
            $table->integer('currency_id'); //foreign key to currency table
            $table->boolean('is_negative')->default(true);
            $table->timestamps();
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
