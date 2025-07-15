<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailpaiementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detailpaiements', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('montant')->nullable();

            $table->unsignedBigInteger('paiementloyer_id')->nullable(true);
            $table->foreign('paiementloyer_id')->references('id')->on('paiementloyers')->onDelete('cascade');
            $table->string("type")->nullable(true);

            $table->unsignedBigInteger('periode_id')->nullable(true);
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('cascade');

            $table->date('date_paiement');

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
        Schema::dropIfExists('detailpaiements');
    }
}
