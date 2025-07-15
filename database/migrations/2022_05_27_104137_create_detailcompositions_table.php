<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailcompositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detailcompositions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('composition_id')->nullable();
            $table->foreign('composition_id')->references('id')->on('compositions')->onDelete('cascade');
            $table->unsignedBigInteger('equipement_id')->nullable();
            $table->foreign('equipement_id')->references('id')->on('equipementpieces')->onDelete('cascade');
            $table->integer('idDetailtypeappartement')->nullable();


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
        Schema::dropIfExists('detailcompositions');
    }
}
