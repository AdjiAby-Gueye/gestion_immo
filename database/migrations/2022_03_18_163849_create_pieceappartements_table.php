<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePieceappartementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pieceappartements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('designation');

            $table->unsignedBigInteger('appartement_id')->nullable(true);
            $table->foreign('appartement_id')->references('id')->on('appartements')->onDelete('cascade');
            $table->unsignedBigInteger('immeuble_id')->nullable(true);
            $table->foreign('immeuble_id')->references('id')->on('immeubles')->onDelete('cascade');
            $table->unsignedBigInteger('typepiece_id')->nullable(true);
            $table->foreign('typepiece_id')->references('id')->on('typepieces')->onDelete('cascade');

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
        Schema::dropIfExists('pieceappartements');
    }
}
