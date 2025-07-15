<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstituantpiecesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constituantpieces', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('designation');
            $table->string('commentaire');
            $table->string('etat');

            $table->unsignedBigInteger('observation_id')->nullable(true);
            $table->foreign('observation_id')->references('id')->on('observations')->onDelete('cascade');

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
        Schema::dropIfExists('constituantpieces');
    }
}
