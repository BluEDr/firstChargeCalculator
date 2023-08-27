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
        Schema::create('payed_amound', function (Blueprint $table) {

            //den exo kanei akoma migrate to sugkekrimeno
            $table->id();
            $table->real('price'); //edo den ksero an prepei na balo real, Double, or something else
            $table->integer('user_id');
            $table->integer('user_id');
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
