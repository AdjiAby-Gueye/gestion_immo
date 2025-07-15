<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNiveauappartementTypepieceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('typepiece_niveauappartement', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('niveauappartement_id')->nullable();
            $table->unsignedBigInteger('typepiece_id')->nullable();

            $table->foreign('niveauappartement_id')->references('id')->on('niveauappartements')->onDelete('cascade');
            $table->foreign('typepiece_id')->references('id')->on('typepieces')->onDelete('cascade');
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
        Schema::dropIfExists('niveauappartement_typepiece');
    }
}
