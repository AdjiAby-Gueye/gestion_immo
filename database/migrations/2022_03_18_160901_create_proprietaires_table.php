<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProprietairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proprietaires', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom');
            $table->string('adresse')->nullable();
            $table->string('prenom')->nullable();
            $table->string('telephone')->nullable();
            $table->string('profession')->nullable();
            $table->string('age')->nullable();
            $table->string('telephoneportable')->nullable();
            $table->string('prenomgestionnaire')->nullable(true);
            $table->integer('isgestionnaire')->nullable(true);
            $table->string('nomgestionnaire')->nullable(true);
            $table->string('adressegestionnaire')->nullable(true);
            $table->string('telephone1gestionnaire')->nullable(true);
            $table->string('telephone2gestionnaire')->nullable(true);
            $table->string('telephonebureau')->nullable(true);
            $table->string('mandataire')->nullable(true);
            $table->string('lieux_naissance')->nullable(true);
            // pays_naissance
            $table->string('pays_naissance')->nullable(true);

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
        Schema::dropIfExists('proprietaires');
    }
}
