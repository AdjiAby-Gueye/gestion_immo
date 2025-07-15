<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('datefacture');
            $table->string('moisfacture');
            $table->string('documentfacture');
            $table->string('recupaiement');
            $table->string('montant');
            $table->string('intervenantassocie')->nullable(true);
            $table->string('partiecommune')->nullable(true);

            $table->unsignedBigInteger('typefacture_id')->nullable(true);
            $table->foreign('typefacture_id')->references('id')->on('typefactures')->onDelete('cascade');
            $table->unsignedBigInteger('appartement_id')->nullable(true);
            $table->foreign('appartement_id')->references('id')->on('appartements')->onDelete('cascade');
            $table->string('periode')->nullable(true);

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
        Schema::dropIfExists('factures');
    }
}
