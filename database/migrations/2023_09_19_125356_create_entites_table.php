<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entites', function (Blueprint $table) {
            $table->id();
            $table->string("designation")->unique()->nullable();
            $table->string("description")->nullable();
            $table->string("code")->unique()->nullable();
            $table->string("image")->nullable();
            $table->string("nomcompletnotaire")->nullable();
            $table->string("emailnotaire")->nullable();
            $table->string("telephone1notaire")->nullable();
            $table->string("nometudenotaire")->nullable();
            $table->string("emailetudenotaire")->nullable();
            $table->string("telephoneetudenotaire")->nullable();
            $table->string("assistantetudenotaire")->nullable();
            $table->string("adressenotaire")->nullable();
            $table->string("adresseetudenotaire")->nullable();
            $table->integer('location')->nullable();
            $table->integer('vente')->nullable();
            App\Outil::listenerUsers($table);
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
        Schema::dropIfExists('entites');
    }
}
