<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagecompositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imagecompositions', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('image')->nullable();

            $table->unsignedBigInteger('composition_id')->nullable(true);
            $table->foreign('composition_id')->references('id')->on('compositions')->onDelete('cascade');
            $table->string('imagecompteur')->nullable();

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
        Schema::dropIfExists('imagecompositions');
    }
}
