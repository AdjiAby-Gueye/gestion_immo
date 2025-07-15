<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImmeubleTypepieceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('immeuble_typepiece', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('immeuble_id');
            $table->foreign('immeuble_id')->references('id')->on('immeubles')->onDelete('cascade');
            $table->unsignedBigInteger('typepiece_id');
            $table->foreign('typepiece_id')->references('id')->on('typepieces')->onDelete('cascade');
            $table->integer('iscopropriete')->nullable(true);

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
        Schema::dropIfExists('immeuble_typepiece');
    }
}
