<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeappartementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('typeappartements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('designation');
            $table->string('nombrecuisine')->nullable();
            $table->string('nombremezzanine')->nullable();
            $table->integer('usage')->nullable();
            $table->string('nombrechambre')->nullable();
            $table->string('nombrechambresalledebain')->nullable();
            $table->string('nombresallon')->nullable();
            $table->string('nombredoucheexterne')->nullable();
            $table->string('nombreespacefamilliale')->nullable();
            $table->string('nombrecouloire')->nullable();
            \App\Outil::statusOfObject($table);
            $table->rememberToken();
            \App\Outil::listenerUsers($table);
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
        Schema::dropIfExists('typeappartements');
    }
}
