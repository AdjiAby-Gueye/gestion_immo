<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReponsequestionnairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reponsequestionnaires', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('contenu');


            $table->unsignedBigInteger('questionnairesatisfaction_id')->nullable(true);
            $table->foreign('questionnairesatisfaction_id')->references('id')->on('questionnairesatisfactions')->onDelete('cascade');
            $table->unsignedBigInteger('locataire_id')->nullable(true);
            $table->foreign('locataire_id')->references('id')->on('locataires')->onDelete('cascade');
            $table->unsignedBigInteger('proprietaire_id')->nullable(true);
            $table->foreign('proprietaire_id')->references('id')->on('proprietaires')->onDelete('cascade');

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
        Schema::dropIfExists('reponsequestionnaires');
    }
}
