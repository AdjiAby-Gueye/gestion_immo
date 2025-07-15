<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompteclientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compteclients', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('locataire_id')->nullable(true);
            $table->foreign('locataire_id')->references('id')->on('locataires')->onDelete('cascade');

            $table->string('montant')->nullable(true);

            $table->integer('typetransaction')->nullable(true);
            $table->integer('etat')->nullable(true);
            $table->integer('user_id')->nullable(true);
            $table->unsignedBigInteger('paiementecheance_id')->nullable(true);
            $table->foreign('paiementecheance_id')->references('id')->on('paiementecheances')->onDelete('cascade');

            $table->string('date')->nullable(true);

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
        Schema::dropIfExists('compteclients');
    }
}
