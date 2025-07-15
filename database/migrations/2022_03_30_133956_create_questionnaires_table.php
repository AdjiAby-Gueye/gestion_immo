<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionnairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('designation')->nullable(true);

            $table->unsignedBigInteger('typequestionnaire_id')->nullable(true);
            $table->foreign('typequestionnaire_id')->references('id')->on('typequestionnaires')->onDelete('cascade');
            $table->string('nom')->nullable(true);
            $table->string('nombre')->nullable(true);
            \App\Outil::statusOfObject($table);
            $table->string('reponsetype')->nullable(true);

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
        Schema::dropIfExists('questionnaires');
    }
}
