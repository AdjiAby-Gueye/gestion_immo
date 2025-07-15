<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBienQuestionnaireTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bien_questionnaire', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('etat');
            $table->string('nombre')->nullable(true);

            $table->unsignedBigInteger('appartement_id')->nullable(true);
            $table->foreign('appartement_id')->references('id')->on('appartements')->onDelete('cascade');
            $table->unsignedBigInteger('immeuble_id')->nullable(true);
            $table->foreign('immeuble_id')->references('id')->on('immeubles')->onDelete('cascade');
            $table->unsignedBigInteger('questionnaire_id');
            $table->foreign('questionnaire_id')->references('id')->on('questionnaires')->onDelete('cascade');

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
        Schema::dropIfExists('bien_questionnaire');
    }
}
