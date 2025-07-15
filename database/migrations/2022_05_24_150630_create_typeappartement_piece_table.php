<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeappartementPieceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('typeappartement_piece', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('designation');
            $table->unsignedBigInteger('typeappartement_id');
            $table->foreign('typeappartement_id')->references('id')->on('typeappartements')->onDelete('cascade');
            $table->unsignedBigInteger('typepiece_id');
            $table->foreign('typepiece_id')->references('id')->on('typepieces')->onDelete('cascade');
            $table->string('commentaire')->nullable();

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
        Schema::dropIfExists('typeappartement_piece');
    }
}
