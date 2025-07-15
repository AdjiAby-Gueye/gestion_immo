<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApportponctuelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apportponctuels', function (Blueprint $table) {
            $table->id();
            $table->integer('montant');
            $table->date('date');
            $table->unsignedBigInteger('contrat_id');
            $table->foreign('contrat_id')->references('id')->on('contrats')->onDelete;
            $table->unsignedBigInteger('typeapportponctuel_id');
            $table->foreign('typeapportponctuel_id')->references('id')->on('typeapportponctuels')->onDelete;
            $table->text('observations');
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
        Schema::dropIfExists('apportponctuels');
    }
}
